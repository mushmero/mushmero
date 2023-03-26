<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class ActivityLog extends SpatieActivity
{
    use HasFactory;    

    public function user()
    {
        return $this->hasOne(User::class,'id','causer_id')->withTrashed();
    }
}
