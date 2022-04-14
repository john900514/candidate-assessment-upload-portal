<?php

namespace App\Models\Assets;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SourceCodeUpload extends Model
{
    use HasFactory, SoftDeletes, CrudTrait;
}
