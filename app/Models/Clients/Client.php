<?php

namespace App\Models\Clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    public static function getClientID(string $name) : string | false
    {
        $results = false;

        $model = self::select('id')->where('name', '=', $name)->first();

        if(!is_null($model))
        {
            $results = $model->id;
        }

        return $results;
    }
}
