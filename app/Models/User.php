<?php

namespace App\Models;

use App\Aggregates\Users\UserAggregate;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Impersonate,
        HasRolesAndAbilities, CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'email_verified_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function details()
    {
        return $this->hasMany('App\Models\UserDetails', 'user_id', 'id');
    }

    public function detail()
    {
        return $this->hasOne('App\Models\UserDetails', 'user_id', 'id');
    }

    public function api_token()
    {
        return $this->detail()->where('name', '=', 'api-token');
    }

    public function employee_status()
    {
        return $this->detail()->where('name', '=', 'employee_status');
    }

    public function available_positions()
    {
        return $this->detail()->where('name', '=', 'available_positions');
    }

    public function getAggregate(): UserAggregate | false
    {
        return is_null($this->id) ? false : UserAggregate::retrieve($this->id);
    }

    public function openCandidates($crud = false)
    {
        return '<button type="button" class="btn btn-danger" onclick="window.location.href = \''.backpack_url().'/users?employee-status=2\'" data-toggle="tooltip" title="Toggle the Employees Filter."><i class="las la-magic"></i> Candidates</a>';
    }

    public function openEmployees($crud = false)
    {
        return '<button type="button" class="btn btn-warning" onclick="window.location.href = \''.backpack_url().'/users?employee-status=1\'" data-toggle="tooltip" title="Toggle the Candidate Filter."><i class="las la-magic"></i> Employees</a>';
    }

    public function openClearFilter($crud = false)
    {
        return '<button type="button" class="btn btn-success" onclick="window.location.href = \''.backpack_url().'/users\'" data-toggle="tooltip" title="Toggle Clearing the Employee Status Filter."><i class="las la-magic"></i> Clear</a>';
    }
}
