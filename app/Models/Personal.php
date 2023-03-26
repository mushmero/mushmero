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

    public function educations()
    {
        return $this->hasMany(Educations::class, 'personal_id','id');
    }

    public function experiences()
    {
        return $this->hasMany(Experiences::class,'personal_id','id');
    }

    public function interests()
    {
        return $this->hasMany(Interests::class,'personal_id','id');
    }

    public function languages()
    {
        return $this->hasMany(Languages::class,'personal_id','id');
    }

    public function objectives()
    {
        return $this->hasMany(Objectives::class,'personal_id','id');
    }

    public function projects()
    {
        return $this->hasMany(Projects::class,'personal_id','id');
    }

    public function skills()
    {
        return $this->hasMany(Skills::class,'personal_id','id');
    }

    public function socials()
    {
        return $this->hasMany(Socials::class,'personal_id','id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id')->withTrashed();
    }
}
