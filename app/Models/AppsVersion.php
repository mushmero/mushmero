<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppsVersion extends Model
{
    use HasFactory;
    protected $table = 'apps_versions';
    
    protected $fillable = [
        'major',
        'minor',
        'build',
        'date',
        'uuid',
    ];
}
