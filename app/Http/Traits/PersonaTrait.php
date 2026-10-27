<?php

namespace App\Http\Traits;

use App\Helpers\Helper;
use App\Models\Personal;
use App\Models\User;
use Carbon\Carbon;

trait PersonaTrait
{
    public static function checkPersona()
    {
        $userId = auth()->id() ?? (User::first()?->id ?? 1);
        $persona = Personal::where(['status' => 1, 'user_id' => $userId])->get();
        if ($persona->isEmpty()) {
            $persona = Personal::where('status', 1)->get();
        }
        return $persona;
    }

    public static function getActivePersonaByAuthUser()
    {
        if (!auth()->check()) {
            return null;
        }
        $persona = Personal::withTrashed()->where(['status' => 1, 'user_id' => auth()->user()->id])->first();
        if (!$persona) {
            $persona = Personal::withTrashed()->where('status', 1)->first();
        }
        if (!$persona) {
            $persona = Personal::where('user_id', auth()->user()->id)->first();
        }
        if (!$persona) {
            $persona = Personal::first();
        }
        return $persona;
    }

    public static function countActiveStatusByUser($id = null)
    {
        $id = $id ?? (auth()->id() ?? (User::first()?->id ?? 1));
        return Personal::where(['status' => 1, 'user_id' => $id])->count();
    }

    public static function getActivePersonaInformations($userId = null)
    {
        $userId = $userId ?? (auth()->id() ?? (User::first()?->id ?? 1));
        $persona = Personal::with(['attachments', 'educations', 'experiences', 'interests', 'languages', 'objectives', 'projects', 'skills', 'socials'])
            ->where(['status' => 1, 'user_id' => $userId])->first();
        if (!$persona) {
            $persona = Personal::with(['attachments', 'educations', 'experiences', 'interests', 'languages', 'objectives', 'projects', 'skills', 'socials'])
                ->where('status', 1)->first();
        }
        return $persona;
    }

    public static function deletePersona($id)
    {
        $persona = Personal::findOrFail($id);
        if (auth()->check()) {
            $persona->deleted_by = auth()->user()->id;
            $persona->save();
        }
        $persona->delete();
        return true;
    }

    public static function restorePersona($id)
    {
        $persona = Personal::withTrashed()->findOrFail($id);
        $persona->deleted_by = null;
        $persona->save();
        $persona->restore();
        return true;
    }
}
