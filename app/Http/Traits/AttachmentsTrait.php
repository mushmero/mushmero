<?php

namespace App\Http\Traits;

use App\Helpers\Helper;
use App\Models\Attachments;
use App\Models\User;
use Str;
use Carbon\Carbon;

trait AttachmentsTrait {
    public static function storeImage($request, $fieldname, $dirname = 'assets', $tag = 'default')
    {
        if(empty($request->file($fieldname))){
            return false;
        }else{
            $file = $request->file($fieldname);
            $fileExtension = $file->extension();
            $rawFileName = Carbon::Now()->timestamp.'_'.Str::random(10);
            $filename = $rawFileName.'.'.$fileExtension;

            // check folder exist & create if not
            Helper::createDirectory('storage/'.$dirname);
            // move the file
            $file->move('storage/'.$dirname, $filename);

            // create records in database
            $attachment = Attachments::create([
                'altname' => $rawFileName,
                'filename' => $filename,
                'path' => 'storage/'.$dirname,
                'type' => $fileExtension,
                'tag' => $tag,
                'user_id' => auth()->user()->id,
            ]);

            return $attachment;
        }
    }
}