<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Socials extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Socials')
        ->logOnly([
            'name',
            'link',
            'icon_id',
            'user_id',
            'created_at',
            'updated_at',
        ]);
    } 
    protected $table = 'socials';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'link',
        'icon_id',
        'user_id',
    ];

    public function allicons()
    {
        return $this->hasOne('App\Models\SettingsSocial','id','icon_id');
    }
}
