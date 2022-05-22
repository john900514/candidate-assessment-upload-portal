<?php

namespace App\Models\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFileUpload extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['file_id', 'user_id', 'file_nickname', 'description','active'];


    public function uploaded_file()
    {
        return $this->belongsTo(UploadedFile::class, 'file_id', 'id');
    }
}
