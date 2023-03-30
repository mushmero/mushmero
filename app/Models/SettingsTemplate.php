<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SettingsTemplate extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('SettingsTemplate')
        ->logOnly([
            'name',
            'design',
            'custom_css',
            'primary_colour',
            'secondary_colour',
            'created_at',
            'updated_at',
        ]);
    } 

    protected $table = 'settings_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'design',
        'custom_css',
        'primary_colour',
        'secondary_colour',
    ];
}
