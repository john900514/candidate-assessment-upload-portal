<?php

namespace App\Http\Controllers\Admin\Candidates;

use App\Actions\Candidate\Assessments\CreateNewAssessmentAction;
use App\Aggregates\Candidates\Assessments\AssessmentAggregate;
use App\Aggregates\Candidates\Assessments\QuizAggregate;
use App\Enums\JobTypeEnum;
use Silber\Bouncer\BouncerFacade as Bouncer;
use App\Exceptions\Candidates\AssessmentException;
use App\Http\Requests\Candidates\AssessmentRequest;
use App\Models\Assets\SourceCodeUpload;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AssessmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AssessmentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Candidates\Assessment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/candidates/assessments');
        CRUD::setEntityNameStrings('assessment', 'assessments');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        CRUD::column('assessment_name')->type('text')->label('Name');
        CRUD::column('concentration')->type('closure')
            ->function(function ($entry) {
                $role = strtoupper($entry->concentration);
                return JobTypeEnum::from($role)->name;
            });
        CRUD::column('has_source_code')->type('boolean')->label('Has Code');
        CRUD::column('has_quizzes')->type('boolean')->label('Has Quiz');
        CRUD::column('active')->type('boolean');
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
        $this->crud->denyAccess('show');
        if(!Bouncer::is(backpack_user())->a('admin', 'dept_head', 'dev_lead'))
        {
            $this->crud->denyAccess('create');
            $this->crud->denyAccess('update');
            $this->crud->denyAccess('delete');
        }
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(AssessmentRequest::class);

        if(backpack_user()->cannot('create_assessments'))
        {
            $this->crud->hasAccessOrFail('nope');
        }

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
        $aggy = AssessmentAggregate::retrieve($this->crud->getCurrentEntryId());

        CRUD::field('assessment_name')->type('text')->attributes(['required' => 'required'])->tab('Config');

        $job_types = [];
        foreach(collect(JobTypeEnum::cases()) as $enum)
        {
            $job_types[$enum->value] = $enum->name;
        }

        $this->crud->field('concentration')->type('select_from_array')
            ->options($job_types)->wrapper(['class' => 'col-6'])
            ->tab('Config');

        $this->crud->field('has_quizzes')->type('boolean')
            ->hint('(Optional) Assessment has questions to be submitted.')
            ->wrapper(['class' => 'col-3'])
            ->tab('Config');

        $this->crud->field('has_source_code')->type('boolean')
            ->hint('(Optional)')
            ->wrapper(['class' => 'col-3'])
            ->tab('Config');
    }

    public function store()
    {
        if(backpack_user()->cannot('create_assessments'))
        {
            $this->crud->hasAccessOrFail('nope');
        }

        $data = request()->all();
        if(!is_null($data['assessment_name']))
        {
            $payload = [
                'assessment_name' => $data['assessment_name'],
                'concentration' => $data['concentration'],
                'active' => 0
            ];

            if($id = CreateNewAssessmentAction::run($payload, $data['has_quizzes'], $data['has_source_code']))
            {
                \Alert::add('success','Assessment Created!.')->flash();
                $this->crud->setSaveAction();
                return $this->crud->performSaveAction($id);
            }
            else
            {
                \Alert::add('error','Creating a new assessment failed.')->flash();
            }
        }
        else
        {
            \Alert::add('warning','Assessment Name Required')->flash();
        }


        $this->crud->setSaveAction();
        return redirect()->back();

    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        if(backpack_user()->cannot('create_assessments'))
        {
            $this->crud->hasAccessOrFail('nope');

        }

        $this->setupCreateOperation();
        $aggy = AssessmentAggregate::retrieve($this->crud->getCurrentEntryId());

        if($aggy->hasCodeWork())
        {
            $options = [];
            $records = SourceCodeUpload::whereActive(1)->get();

            foreach ($records as $record)
            {
                $options[$record->id] = $record->file_nickname;
            }
            CRUD::field('source_code_record')->attributes(['required' => 'required'])
                ->type('select_from_array')->options($options)
                ->attributes(['required' => 'required'])->label('Add Source Code')
                ->value($aggy->getCodeWorkId() ?? '')->tab('Config');
        }

        if(($aggy->hasCodeWork() || $aggy->hasQuizzes()) && backpack_user()->can('approve_assessments'))
        {
            $this->crud->field('active')->type('boolean')
                ->hint('Must have either source code or quizzes toggled and selected to activate!')
                ->tab('Config');
        }

        $tasks = $aggy->getTasks();

        $this->crud->field('table_of_tasks')->type('view')
            ->view('card-bodies.assessment-tasks-table')->value($tasks)
            ->tab('Tasks');

        $quiz_ids = $aggy->getQuizzes();
        $quizzes = [];
        foreach($quiz_ids as $quiz_id)
        {
            $quiz_aggy = QuizAggregate::retrieve($quiz_id);
            $quizzes[] = [
                'quiz_id' => $quiz_id,
                'name' => $quiz_aggy->getName(),
                'concentration' => JobTypeEnum::tryFrom($quiz_aggy->getConcentration())->name
            ];
        }
        $this->crud->field('table_of_tests')->type('view')
            ->view('card-bodies.assessment-quizzes-table')->value($quizzes)
            ->tab('Quizzes');
    }

    public function update()
    {
        if(backpack_user()->cannot('create_assessments'))
        {
            $this->crud->hasAccessOrFail('nope');
        }

        $data = request()->all();

        if(array_key_exists('active', $data) && $data['active'])
        {
            if(backpack_user()->cannot('approve_assessments'))
            {
                \Alert::add('error','Not Authorized to Activate an Assessment.')->flash();
                $this->crud->setSaveAction();
                return redirect()->back();
            }
        }

        if(!is_null($data['assessment_name']))
        {
            if($data['has_quizzes'])
            {
                if(backpack_user()->cannot('assign_quiz_to_assessment'))
                {
                    \Alert::add('error','Not Authorized to Assign Quizzes to an Assessment.')->flash();
                }
            }

            if($data['has_source_code'])
            {
                if(backpack_user()->cannot('create_code_download_requirement'))
                {
                    \Alert::add('error','Not Authorized to Assign Source Code to an Assessment.')->flash();
                }
            }

            $payload = [
                'assessment_name' => $data['assessment_name'],
                'concentration' => $data['concentration'],
                'active' => $data['active'] ?? 0
            ];

            $aggy = AssessmentAggregate::retrieve($this->crud->getCurrentEntryId())
                ->updateAssessment($payload, $data['has_quizzes'], $data['has_source_code'], backpack_user()->id);

            if($data['has_source_code'] &&  $data['source_code_record'])
            {
                try {
                    $id = $aggy->getCodeWorkId();
                    if(is_null($id))
                    {
                        $aggy = $aggy->addSourceCodeRecord($data['source_code_record']);
                    }
                    else
                    {
                        if($id != $data['source_code_record'])
                        {
                            // @todo - remove the record follows by
                            // $aggy = $aggy->addSourceCodeRecord($data['source_code_record']);
                        }
                    }

                }
                catch(AssessmentException $e)
                {
                    \Alert::add('error', $e->getMessage())->flash();
                }

            }
            else
            {
                // @todo - check if there is a source code assigned and remove it
            }

            // @todo - validate an active toggle for having quizzes selected
            $aggy->persist();

        }
        else
        {
            \Alert::add('warning','Assessment Name Required')->flash();
        }

        $this->crud->setSaveAction();
        return redirect()->back();
    }
}
