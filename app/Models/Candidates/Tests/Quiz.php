<?php

namespace App\Models\Candidates\Tests;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use CrudTrait, HasFactory, SoftDeletes;

    protected $fillable = ['id', 'name', 'concentration', 'active'];
}
