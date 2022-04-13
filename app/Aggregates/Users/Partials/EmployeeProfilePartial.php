<?php

namespace App\Aggregates\Users\Partials;

use App\StorableEvents\Users\UserCreated;
use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

class EmployeeProfilePartial extends AggregatePartial
{
    protected string $employee_status = 'non-employee';

    public function applyUserCreated(UserCreated $event)
    {
        switch($event->role)
        {
            case 'FE_CANDIDATE':
            case 'BE_CANDIDATE':
            case 'FS_CANDIDATE':
            case 'MGNT_CANDIDATE':
            case 'APPLICANT':
                $this->employee_status = 'non-employee';
                break;

            default:
                $this->employee_status = 'employee';
        }
    }
}
