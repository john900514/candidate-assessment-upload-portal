<?php

namespace App\Http\Controllers\Admin\Users;

use App\Actions\Users\Query\GetAllCandidateUUIDs;
use App\Actions\Users\Query\GetAllEmployeeUUIDs;
use App\Actions\Users\Query\GetAllUserUUIDs;
use App\Aggregates\Users\UserAggregate;
use App\Http\Requests\Users\UserManagementRequest;
use App\Models\Candidates\JobPosition;
use App\Traits\CRUDFilters\Users\EmployeeCandidateFilter;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

/**
 * Class UserManagementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserManagementCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation  { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation  { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use EmployeeCandidateFilter;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/users');
        CRUD::setEntityNameStrings('User', 'User Management');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $user = backpack_user();
        if($user->can('view_employees') && $user->can('view_candidates'))
        {
            if(!request()->has('employee-status'))
            {
                $this->addCandidatesButton();
                $this->addEmployeesButton();
                $user_uuids = GetAllUserUUIDs::run();
                $this->crud->addClause('whereIn', 'users.id', $user_uuids);
            }
            else
            {
                switch(request()->get('employee-status'))
                {
                    case 1:
                        $this->addCandidatesButton();
                        $this->addClearButton();
                        $user_uuids = GetAllEmployeeUUIDs::run();
                        $this->crud->addClause('whereIn', 'id', $user_uuids);
                        break;

                    case 2:
                        $this->addEmployeesButton();
                        $this->addClearButton();
                        $user_uuids = GetAllCandidateUUIDs::run();
                        $this->crud->addClause('whereIn', 'id', $user_uuids);
                        break;

                        default:
                            $this->addEmployeesButton();
                        $this->addCandidatesButton();
                        $user_uuids = GetAllUserUUIDs::run();
                        $this->crud->addClause('whereIn', 'users.id', $user_uuids);
                }
            }

        }
        else if($user->can('view_employees'))
        {
            $user_uuids = GetAllEmployeeUUIDs::run();
            $this->crud->addClause('whereIn', 'id', $user_uuids);
        }
        else if($user->can('view_candidates'))
        {
            $user_uuids = GetAllCandidateUUIDs::run();
            $this->crud->addClause('whereIn', 'id', $user_uuids);
        }
        else
        {
            $this->crud->hasAccessOrFail('nope');
        }

        if($user->cannot('delete_users'))
        {
            $this->crud->denyAccess('delete');
        }

        if($user->cannot('edit_users'))
        {
            $this->crud->denyAccess('update');
        }

        CRUD::column('email')->type('text');
        CRUD::column('employee_status')->type('closure')
            ->function(function($entry) {
                return $entry->employee_status->value;
            });

        CRUD::column('role')->type('closure')
            ->function(function($entry) {
                return $entry->getRoles()[0];
            });

        CRUD::column('email_verified_at')->label('Active')->type('boolean');

        if($user->can('view_candidates'))
        {
            $this->crud->addButtonFromView('line','Impersonate','impersonate-user', 'beginning');
        }
        $this->crud->addButtonFromView('line','Resend Email','resend-welcome-email', 'beginning');

        $this->crud->enableResponsiveTable();
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserManagementRequest::class);
        //CRUD::setFromDb(); // columns
        $data = request()->all();

        $status = $data['status'] ?? '';
        CRUD::field('new_user')->type('user-create-form')->value($status);

        if($status == 'candidate')
        {
            CRUD::field('email');
            CRUD::field('first_name')->wrapper(['class' => 'col-sm-12 col-md-6']);
            CRUD::field('last_name')->wrapper(['class' => 'col-sm-12 col-md-6']);
            CRUD::field('email');

            $roles = [
                'FE_CANDIDATE'   => 'Frontend Applicant',
                'BE_CANDIDATE'   => 'Backend Candidate',
                'FS_CANDIDATE'   => 'FullStack Candidate',
                //'MGNT_CANDIDATE' => 'Management Candidate',
                //'APPLICANT'      => 'Unqualified Applicant'
            ];

            $this->crud->field('role')->type('select_from_array')
                ->wrapper(['class' => 'col-sm-12 col-md-6'])
                ->options($roles);

            $departments = [
                'ENGINEERING' => 'Development'
            ];
            $this->crud->field('department')->type('select_from_array')
                ->wrapper(['class' => 'col-sm-12 col-md-6'])
                ->options($departments);

            $this->crud->addField([
                'label' => 'Link Job Position(s) to Applicant',
                'type' => 'select_multiple',
                'name' => 'positions',
                'model' => JobPosition::class,
                'attribute' => 'job_title',
                'options'   => (function ($query) {
                    return $query->where('active', 1)->get();
                })
            ]);

            $this->crud->field('send_welcome_email')->type('boolean')->default(true)
                ->label('Send Welcome Email?');

        }
        else if($status == 'employee')
        {
            CRUD::field('status')->type('select_from_array')->options(['fuck you', 'kiss my ass']);
        }


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
        $dev = [
            'first_name' => $data['first_name'] ?? '',
            'last_name' => $data['last_name'] ?? '',
            'email' => $data['email'],
            'name' => $data['name'] ?? '',
            'password' => bcrypt('er]sawrkaxgjkpwrbdmbpqe]'),
            'dept' => $data['department']
        ];
        $role = strtolower($data['role']);

        try {
            $aggy = UserAggregate::retrieve($uuid)
                ->createUser($dev, $role)
                ->updateCandidatesAvailablePositions($data['positions']);

            if($data['send_welcome_email'])
            {
                $aggy = $aggy->sendWelcomeEmail($data['status']);
            }

            $aggy->persist();
        }
        catch(\DomainException $e)
        {
            \Alert::add('error','Could not do the thing. Sorry. '.$e->getMessage())->flash();
            $this->crud->setSaveAction();
            return redirect()->back();
        }


        \Alert::add('success','Done.')->flash();
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
        $entry = $this->crud->getCurrentEntry();
        $status = $entry->employee_status()->first();
        $aggy = UserAggregate::retrieve($entry->id);

        if($status->value == 'employee')
        {
            if(backpack_user()->can('edit_users'))
            {
                CRUD::field('first_name');

            }
            else
            {
                $this->crud->hasAccessOrFail('nope');
            }
        }
        elseif(backpack_user()->can('create_candidates'))
        {
            $this->crud->field('first_name')->attributes(['disabled' => 'disabled'])->wrapper(['class' => 'col-6']);
            $this->crud->field('last_name')->attributes(['disabled' => 'disabled'])->wrapper(['class' => 'col-6']);
            $this->crud->field('email')->attributes(['disabled' => 'disabled']);

            $roles = [
                'FE_CANDIDATE'   => 'Frontend Applicant',
                'BE_CANDIDATE'   => 'Backend Candidate',
                'FS_CANDIDATE'   => 'FullStack Candidate',
                'MGNT_CANDIDATE' => 'Management Candidate',
                'APPLICANT'      => 'Unqualified Applicant'
            ];

            $this->crud->field('role')->type('select_from_array')
                ->wrapper(['class' => 'col-sm-12 col-md-6'])
                ->options($roles)->value(strtoupper($entry->getRoles()[0]));

            $departments = [
                'ENGINEERING' => 'Development'
            ];

            $this->crud->field('department')->type('select_from_array')
                ->wrapper(['class' => 'col-sm-12 col-md-6'])
                ->options($departments)->value($aggy->getDepartment());

            // @todo - get data recall on this.
            $this->crud->addField([
                'label' => 'Link Job Position(s) to Applicant',
                'type' => 'select_multiple',
                'name' => 'positions',
                'model' => JobPosition::class,
                'attribute' => 'job_title',
                'options'   => (function ($query) {
                    return $query->where('active', 1)->get();
                }),
                'value' => $aggy->getOpenJobPositions()
            ]);
        }
    else
        {
            $this->crud->hasAccessOrFail('nope');
        }
    }

    public function update()
    {
        $data = request()->all();
        $aggy = UserAggregate::retrieve($data['id']);

        switch($aggy->getRole())
        {
            case 'applicant':
            case 'APPLICANT':
            case 'fe_candidate':
            case 'FE_CANDIATE':
            case 'be_candidate':
            case 'BE_CANDIDATE':
            case 'fs_candidate':
            case 'FS_CANDIDATE':
            case 'mgnt_candidate':
            case 'MGNT_CANDIDATE':

            if(!is_null($data['positions']))
                {
                    // @todo - unqualified candidate cannot be linked to a job position
                    if(($data['role'] == 'APPLICANT') && (count($data['positions']) > 0))
                    {
                        \Alert::add('warning','Qualify an Applicant to Link them to open positions')->flash();
                        $this->crud->setSaveAction();
                        return redirect()->back();
                    }
                    // @todo - chosen position must match the level of the Job Position
                    $aggy->updateCandidateRole($data['role'])
                        ->updateCandidatesAvailablePositions($data['positions'])
                        ->persist();

                    \Alert::add('success','Applicant Updated!')->flash();

                }
                else
                {
                    \Alert::add('error','Job Positions Required')->flash();
                    return redirect()->back();
                }
            break;

            default:
        }

        $this->crud->setSaveAction();
        return $this->crud->performSaveAction($data['id']);
    }
}
