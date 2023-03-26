<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SettingsPDF extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('SettingsPDF')
        ->logOnly([
            'name',
            'margin_top',
            'margin_bottom',
            'margin_left',
            'margin_right',
            'header',
            'footer',
            'user_id',
            'created_at',
            'updated_at',
        ]);
    } 

    protected $table = 'settings_pdfs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'margin_top',
        'margin_bottom',
        'margin_left',
        'margin_right',
        'header',
        'footer',
        'user_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id')->withTrashed();
    }
}
