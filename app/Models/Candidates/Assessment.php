<?php

namespace App\Models\Candidates;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    use HasFactory, SoftDeletes, CrudTrait;

    public static function getAssessmentsArray() : array
    {
        $results = [];

        if(count($records = self::whereActive(1)->get()) > 0)
        {
            foreach($records as $idx => $record)
            {
                $results[$record->id] = $record->assessment_name;
            }
        }

        return $results;
    }
}
