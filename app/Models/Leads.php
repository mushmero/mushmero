<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Leads extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Leads')
        ->logOnly([
            'name',
            'email',
            'subdomain',
            'activate',
            'activate_time',
            'deactivate_time',
            'reason',
            'created_at',
            'updated_at',
        ]);
    } 

    protected $table = 'leads';
    
    protected $fillable = [
        'name',
        'email',
        'subdomain',
        'activate',
        'activate_time',
        'deactivate_time',
        'reason',
    ];
}
