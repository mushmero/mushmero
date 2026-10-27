<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Projects extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Projects')
        ->logOnly([
            'name',
            'description',
            'status_id',
            'user_id',
            'created_at',
            'updated_at',
            'personal_id',
        ]);
    } 
    protected $table = 'projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'status_id',
        'user_id',
        'personal_id',
    ];

    public function allstatus()
    {
        return $this->hasOne('App\Models\SettingsProject','id','status_id');
    }

    public function persona()
    {
        return $this->belongsTo(Personal::class, 'personal_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id')->withTrashed();
    }
}
