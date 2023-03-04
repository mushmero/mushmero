<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Experiences extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Experiences')
        ->logOnly([
            'position',
            'start',
            'end',
            'employer',
            'description',
            'user_id',
            'created_at',
            'updated_at',
            'personal_id',
        ]);
    } 
    protected $table = 'experiences';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'position',
        'start',
        'end',
        'employer',
        'description',
        'user_id',
        'personal_id',
    ];

    public function persona()
    {
        return $this->belongsTo('App\Models\Personal','id','personal_id');
    }
}
