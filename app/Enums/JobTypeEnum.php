<?php

namespace App\Enums;

enum JobTypeEnum : int
{
    case BACKEND = 1;
    case FRONTEND = 2;
    case FULLSTACK = 3;
    case ARCHITECT = 5;
    case TEAM_LEAD = 6;
    case SPECIALIST = 7;
    case SCRUM = 8;

}
