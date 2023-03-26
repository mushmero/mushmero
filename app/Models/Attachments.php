<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Attachments extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Attachments')
        ->logOnly([
            'altname',
            'filename',
            'path',
            'type',
            'tag',
            'user_id',
            'created_at',
            'updated_at',
        ]);
    } 
    protected $table = 'attachments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'altname',
        'filename',
        'path',
        'type',
        'tag',
        'user_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id')->withTrashed();
    }
}
