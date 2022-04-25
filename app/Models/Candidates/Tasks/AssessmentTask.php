<?php

namespace App\Models\Candidates\Tasks;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentTask extends Model
{
    use HasFactory, CrudTrait, SoftDeletes;
}
