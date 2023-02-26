<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Personal extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Personal')
        ->logOnly([
            'fullname',
            'tagline',
            'email',
            'phone',
            'website',
            'status',
            'attachment_id',
            'user_id',
            'created_at',
            'updated_at',
        ]);
    } 
    protected $table = 'personals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'tagline',
        'email',
        'phone',
        'website',
        'status',
        'attachment_id',
        'user_id',
    ];

    public function attachments()
    {
        return $this->hasOne('App\Models\Attachments','id','attachment_id');
    }
}
