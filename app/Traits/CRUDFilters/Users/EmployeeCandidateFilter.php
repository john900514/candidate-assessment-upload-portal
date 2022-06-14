<?php

namespace App\Traits\CRUDFilters\Users;

use Illuminate\Support\Facades\DB;

trait EmployeeCandidateFilter
{
    public function addEmployeeCandidateFilter()
    {
        $this->crud->addFilter([
            'name'  => 'employee_status',
            'type'  => 'dropdown',
            'label' => 'Employee Status'
        ], [
            1 => 'All',
            2 => 'Employees',
            3 => 'Candidates',
        ], function($value) { // if the filter is active
            switch($value)
            {
                case 2:
                    $this->crud->addClause('join', 'user_details', 'users.id', '='
                        , DB::raw("user_details.user_id AND user_details.name = 'employee_status' AND user_details.value = 'employee'")
                    );
                    break;
                case 3:
                    $this->crud->addClause('join', 'user_details', 'users.id', '='
                        , DB::raw("user_details.user_id AND user_details.name = 'employee_status' AND user_details.value = 'non-employee'")
                        );
            }

        });
    }

    public function addCandidatesButton()
    {
        $this->crud->addButtonFromModelFunction('top', 'candidates-only', 'openCandidates', 'end');
    }

    public function addEmployeesButton()
    {
        $this->crud->addButtonFromModelFunction('top', 'employees-only', 'openEmployees', 'end');
    }

    public function addClearButton()
    {
        $this->crud->addButtonFromModelFunction('top', 'clear-employee-filter', 'openClearFilter', 'end');
    }
}
