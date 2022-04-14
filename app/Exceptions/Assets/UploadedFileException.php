<?php

namespace App\Exceptions\Assets;

use DomainException;

class UploadedFileException extends DomainException
{
    public static function fileAlreadyExists()
    {
        return new self('File Already Exists!');
    }
}
