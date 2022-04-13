<?php

namespace App\Enums;

enum QuestionTypeEnum : int
{
    case OPEN_ENDED = 1;
    case CODE = 2;
    case MULTIPLE_CHOICE = 3;
    case FILE_UPLOAD = 4;
}
