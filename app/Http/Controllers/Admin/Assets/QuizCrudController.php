<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Aggregates\Candidates\Assessments\QuizAggregate;
use App\Enums\JobTypeEnum;
use App\Http\Requests\Assets\QuizRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Ramsey\Uuid\Uuid;

/**
 * Class QuizCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class QuizCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Candidates\Tests\Quiz::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/assets/quizzes');
        CRUD::setEntityNameStrings('Quiz', 'Assessment Quizzes');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name')->type('text');

        CRUD::column('concentration')->type('closure')
            ->function(function ($entry) {
                $role = strtoupper($entry->concentration);
                return JobTypeEnum::from($role)->name;
            });

        CRUD::column('active')->type('boolean');
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(QuizRequest::class);

        CRUD::field('name')->type('text')->wrapper(['class' => 'col-6'])
            ;

        $job_types = [];
        foreach(collect(JobTypeEnum::cases()) as $enum)
        {
            $job_types[$enum->value] = $enum->name;
        }

        $this->crud->field('concentration')->type('select_from_array')
            ->options($job_types)->wrapper(['class' => 'col-6']);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    public function store()
    {
        $data = request()->all();
        $uuid = Uuid::uuid4()->toString();

        QuizAggregate::retrieve($uuid)
            ->createQuiz($data['name'], $data['concentration'])
            ->persist();

        $this->crud->setSaveAction();
        return $this->crud->performSaveAction($uuid);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();

        $this->crud->field('active')->type('boolean')
            ->wrapper(['class'=> 'pt-4 col-12'])
            ->hint('Question(s) must be added before Activating');

        $aggy = QuizAggregate::retrieve($this->crud->getCurrentEntryId());

        $tasks = $aggy->getQuestions();
        $this->crud->field('table_of_tasks')->type('view')
            ->view('card-bodies.quiz-questions-table')->value($tasks)
            ->tab('Questions');
    }

    public function update()
    {
        $data = request()->all();

        $aggy = QuizAggregate::retrieve($this->crud->getCurrentEntryId());

        $valid = true;
        if(array_key_exists('active', $data) && $data['active'] == '1')
        {
            // Check if there are questions already, or fail if active is true
            if(count($aggy->getQuestions()) == 0)
            {
                \Alert::add('error', 'Questions have to be added before activating the quiz!', )->flash();
                $valid = false;
            }
            else
            {
                if(!$aggy->isActive())
                {
                    $aggy = $aggy->activateQuiz();
                    \Alert::add('success', 'Quiz Activated.', )->flash();
                }
            }
        }

        if($valid)
        {
            if(empty($data['name']))
            {
                \Alert::add('error', 'Quiz Name cannot be empty!', )->flash();
                $valid = false;
            }
            elseif($aggy->getName() != $data['name'])
            {
                $aggy = $aggy->updateQuizName($data['name']);
                \Alert::add('success', 'Quiz name changed.', )->flash();
            }

            if($valid)
            {
                if(empty($data['concentration']))
                {
                    \Alert::add('error', 'The quiz needs a concentration!', )->flash();
                    $valid = false;
                }
                elseif($aggy->getConcentration() != $data['concentration'])
                {
                    $aggy = $aggy->updateConcentration($data['concentration']);
                    \Alert::add('success', 'Quiz concentration changed.', )->flash();
                }

                if($aggy->isActive() && (array_key_exists('active', $data) && $data['active'] == '0'))
                {
                    $aggy = $aggy->deactivateQuiz();
                    \Alert::add('success', 'Quiz Deactivated.', )->flash();
                }
            }
        }

        if($valid)
        {
            $aggy->persist();
            \Alert::add('success', 'Quiz successfully Updated!!', )->flash();

        }
        /**
         * STEPS
         * 1.
         * 2. Use the aggregate to update the name concentration
         * 3. Try and catch QuizExceptions
         */


        $this->crud->setSaveAction();
        return $this->crud->performSaveAction($this->crud->getCurrentEntryId());
    }
}
