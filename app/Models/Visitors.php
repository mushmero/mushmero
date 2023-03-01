<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Visitors extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Visitors')
        ->logOnly([
            'userip',
            'referrer',
            'useragent',
            'datetime',
            'city',
            'country',
            'countryCode',
            'continent',
            'continentCode',
            'timezone',
            'latitude',
            'longitude',
            'created_at',
            'updated_at',
        ]);
    } 
    protected $table = 'visitors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userip',
        'referrer',
        'useragent',
        'datetime',
        'city',
        'country',
        'countryCode',
        'continent',
        'continentCode',
        'timezone',
        'latitude',
        'longitude',
    ];
}
