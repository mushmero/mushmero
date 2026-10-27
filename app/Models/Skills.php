<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Skills extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Skills')
        ->logOnly([
            'name',
            'percentage',
            'user_id',
            'created_at',
            'updated_at',
            'personal_id',
            'icon_id',
        ]);
    } 
    protected $table = 'skills';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'percentage',
        'user_id',
        'personal_id',
        'icon_id'
    ];

    public function persona()
    {
        return $this->belongsTo(Personal::class, 'personal_id', 'id');
    }

    public function allicons()
    {
        return $this->hasOne('App\Models\SettingsSocial','id','icon_id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id')->withTrashed();
    }
}
