<?php

namespace App\Aggregates\Users\Partials;

use App\StorableEvents\Users\UserCreated;
use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

class CandidateProfilePartial extends AggregatePartial
{
    protected string $candidate_status = 'unqualified-candidate';

    public function applyUserCreated(UserCreated $event)
    {
        switch($event->role)
        {
            case 'FE_CANDIDATE':
            case 'BE_CANDIDATE':
            case 'FS_CANDIDATE':
            case 'MGNT_CANDIDATE':
                $this->candidate_status = 'qualified-candidate';
                break;

            case 'APPLICANT':
                $this->candidate_status = 'unqualified-candidate';
                break;

            default:
                $this->candidate_status = 'non-candidate';
        }
    }
}
