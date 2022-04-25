<?php

namespace App\Models\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UploadedFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['file_path', 'entity_id', 'entity', 'active'];
}
