<?php

namespace App\Enums;

enum UserRoleEnum : string
{
    case ADMIN           = 'Application Admin';
    case EXECUTIVE       = 'Company Executive';
    case PROJECT_MANAGER = 'Project Manager';
    case DEPT_HEAD       = 'Department Head';
    case DEV_LEAD        = 'Dev Team Lead';
    case SENIOR_DEV      = 'Senior Developer';
    case DEV             = 'Developer';
    case JUNIOR_DEV      = 'Junior Developer';
    case FE_CANDIDATE    = 'Frontend Developer Candidate';
    case BE_CANDIDATE    = 'Backend Developer Applicant';
    case FS_CANDIDATE    = 'Full Stack Developer Applicant';
    // @todo - other department candidates go here
    case MGNT_CANDIDATE  = 'Management Level Applicant';
    case APPLICANT       = 'Uncategorized Developer Applicant';
}
