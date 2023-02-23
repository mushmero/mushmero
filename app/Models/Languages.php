<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Languages extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Languages')
        ->logOnly([
            'name',
            'proficiency_id',
            'user_id',
            'created_at',
            'updated_at',
        ]);
    } 
    protected $table = 'languages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'proficiency_id',
        'user_id',
    ];

    public function allproficiency()
    {
        return $this->hasOne('App\Models\SettingsLanguage','id','proficiency_id');
    }
}
