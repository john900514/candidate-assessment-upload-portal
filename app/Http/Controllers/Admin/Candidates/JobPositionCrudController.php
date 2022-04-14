<?php

namespace App\Http\Controllers\Admin\Candidates;

use App\Aggregates\Candidates\JobPositionAggregate;
use App\Enums\JobTypeEnum;
use App\Enums\SecurityGroupEnum;
use App\Enums\UserRoleEnum;
use App\Http\Requests\Candidates\JobPositionRequest;
use App\Models\Candidates\Assessment;
use App\Models\Candidates\Tests\Quiz;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class JobPositionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class JobPositionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation  { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Candidates\JobPosition::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/candidates/job-positions');
        CRUD::setEntityNameStrings('job position', 'job positions');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('job_title')->label('Job Title')->type('text');
        CRUD::column('concentration')->type('closure')
            ->function(function ($entry) {
                $role = strtoupper($entry->concentration);
                return JobTypeEnum::from($role)->name;
            });
        CRUD::column('awarded_role')->label('Awarded Role')->type('text');
        CRUD::column('active')->label('Live?')->type('boolean');

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
        CRUD::setValidation(JobPositionRequest::class);


        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $aggy = JobPositionAggregate::retrieve($this->crud->getCurrentEntryId());
        $this->crud->field('job_title')->attributes(['required' => 'required'])
            ->wrapper(['class' => 'col-6']);

        $job_types = [];
        foreach(collect(JobTypeEnum::cases()) as $enum)
        {
            $job_types[$enum->value] = $enum->name;
        }

        $this->crud->field('concentration')->type('select_from_array')
            ->options($job_types)->wrapper(['class' => 'col-6']);

        $this->crud->field('description')->type('textarea')
            ->value($aggy->getDesc() ?? '');

        $roles = [];
        foreach(collect(UserRoleEnum::cases()) as $enum)
        {
            $roles[$enum->value] = $enum->name;
        }
        $this->crud->field('awarded_role')->attributes(['required' => 'required'])
            ->type('select_from_array')->options($roles)
            ->wrapper(['class' => 'col-12']);

        $this->crud->field('assessments')->type('select_multiple')
            ->attributes(['required' => 'required'])->label('Add or Remove Assessments')
            ->model(Assessment::class)->attribute('assessment_name')
            ->options(function ($query) {
                return $query->where('active', 1)->get();
            })->value($aggy->getAssessments());

        $this->crud->field('active')->type('boolean')
            ->hint('Assessments must be added before Activating');

    }

    public function update()
    {
        $data = request()->all();
        $aggy = JobPositionAggregate::retrieve($data['id']);

        if(!array_key_exists('active', $data) || (!$data['active'] ?? false))
        {

            $payload = [
                'position' => $data['job_title'],
                'concentration' => ['value' => $data['concentration']],
                'awarded_role' => ['value' => $data['awarded_role']],
                'active' => 0
            ];
            $aggy = $aggy->updateJobPosition($payload);

            if($data['description'] ?? null)
            {
                $aggy = $aggy->updateDescription($data['description']);
            }

            $aggy->persist();
        }
        else
        {
            if(!is_null($data['assessments']))
            {
                $payload = [
                    'position' => $data['job_title'],
                    'concentration' => ['value' => $data['concentration']],
                    'awarded_role' => ['value' => $data['awarded_role']],
                    'active' => 1
                ];
                $aggy = $aggy->updateJobPosition($payload)
                    ->updateAssessments($data['assessments']);

                if($data['description'] ?? null)
                {
                    $aggy = $aggy->updateDescription($data['description']);
                }

                $aggy->persist();
            }
            else
            {
                \Alert::add('error','Assessments Required')->flash();
                return redirect()->back();
            }
        }

        $this->crud->setSaveAction();
        return $this->crud->performSaveAction($this->crud->getCurrentEntryId());
    }
}
