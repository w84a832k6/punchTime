<?php

namespace App\Models;

use DateTime;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'alias',
        'password',
        'enable',
        'type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function isAdmin()
    {
        return $this->type === 'admin';
    }

    public function punchTime()
    {
        return $this->hasMany(PunchTime::class, 'user_id', 'id');
    }

    public function getLastMonthPunchTimeAttribute()
    {
        return $this->punchTime()->whereBetween('on_time', [
            (new DateTime('last month'))->format('Y-m-01 00:00:00'),
            (new DateTime('last month'))->format('Y-m-t 23:59:59'),
        ])->get();
    }

    public function getThisMonthPunchTimeAttribute()
    {
        return $this->punchTime()->whereBetween('on_time', [
            (new DateTime('this month'))->format('Y-m-01 00:00:00'),
            (new DateTime('this month'))->format('Y-m-t 23:59:59'),
        ])->get();
    }
}
