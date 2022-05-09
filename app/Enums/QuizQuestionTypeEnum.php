<?php

namespace App\Enums;

enum QuizQuestionTypeEnum : string
{
    case OPEN_ENDED = 'Open Ended';
    case MULTIPLE_CHOICE = 'Multiple Choice';
    case TRUE_FALSE = 'True or False';
}
