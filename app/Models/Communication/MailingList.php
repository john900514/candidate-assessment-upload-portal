<?php

namespace App\Models\Communication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailingList extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id', 'lit_name', 'concentration', 'active'];
}
