<?php

namespace App\Models\Candidates\Tests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id', 'quiz_id','question_name', 'question_type', 'available_choices', 'answer', 'order', 'active'];

    protected $casts = [
        'available_choices' => 'array'
    ];
}
