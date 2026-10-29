<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Traits\AttachmentsTrait;
use App\Http\Traits\PersonaTrait;
use Illuminate\Http\Request;
use App\Models\Personal;
use App\Models\Attachments;
use App\Models\User;

class PersonalController extends Controller
{

    protected $btnEdit, $btnDelete, $btnDetails, $btnRestore;

    /**
     * Declare default template for button edit, button delete & button view
     */
    public function __construct()
    {
        $this->middleware('autologout');
        $this->btnEdit = config('adminlte.btn-edit');
        $this->btnDelete = config('adminlte.btn-delete');
        $this->btnDetails = config('adminlte.btn-view');
        $this->btnRestore = config('adminlte.btn-restore');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $user = auth()->user();
        $array = Personal::with(['attachments', 'user'])->where('user_id', $user->id)->get();
        if ($array->isEmpty()) {
            $array = Personal::with(['attachments', 'user'])->get();
        }

        $heads = [
            'Profile',
            'Status',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach ($array as $arr) {
            $imgSrc = ($arr->attachments && $arr->attachments->path && $arr->attachments->filename) 
                ? url($arr->attachments->path . '/' . $arr->attachments->filename) 
                : url('assets/images/user.png');
            $altName = $arr->attachments->altname ?? 'Avatar';
            $userName = $arr->user ? $arr->user->name : 'N/A';

            $data[] = array(
                '<div class="row"><div class="col-1"><img src="' . $imgSrc . '" alt="' . $altName . '" width="50" height="50" class="img-center"></div><div class="col-11">' . e($arr->fullname) . '&nbsp;' . (!empty($arr->tagline) ? '(' . e($arr->tagline) . ')' : '') . (!empty($arr->phone) ? '<br><i class="fa-fw fas fa-mobile-alt"></i>&nbsp;' . e($arr->phone) : '') . (!empty($arr->email) ? '&nbsp;<i class="fa-fw fas fa-envelope"></i>&nbsp;' . e($arr->email) : '') . (!empty($arr->website) ? '<br><i class="fa-fw fas fa-globe"></i>&nbsp;' . e($arr->website) : '') . '</div></div>',
                '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="activeSwitch' . $arr->id . '" name="activeSwitch' . $arr->id . '" onchange="switchAjax(' . $arr->id . ')" ' . ($arr->status == 1 ? "checked" : "") . ' ' . (auth()->user()->id != $arr->user_id ? 'disabled' : '') . ' /><label class="active_switch custom-control-label" for="activeSwitch' . $arr->id . '">' . ($arr->status == 1 ? "Active" : "Inactive") . '</label></div>',
                e($userName),
                '<nobr><a class="' . (!$arr->trashed() ? 'disabled' : '') . '" href="' . url('personal/' . $arr->id . '/restore') . '">' . $this->btnRestore . '</a><a class="' . ($arr->trashed() ? 'disabled' : '') . '" href="' . url('personal/' . $arr->id . '/edit') . '">' . $this->btnEdit . '</a><a class="' . ($arr->trashed() ? 'disabled' : '') . '" href="' . url('personal/' . $arr->id . '/delete') . '">' . $this->btnDelete . '</a><a class="' . ($arr->trashed() ? 'disabled' : '') . '" href="' . url('personal/' . $arr->id . '/show') . '">' . $this->btnDetails . '</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null],
        ];

        return view('modules.personal.list', [
            'heads' => $heads,
            'config' => $config,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('modules.personal.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'fullname' => 'required',
            'profilephoto' => 'image|mimes:png,jpg|max:5120',
        ]);

        $data = $request->all();

        // process upload file
        $attachmentID = '';
        if ($request->file('profilephoto')) {
            $uploadedFile = AttachmentsTrait::storeImage($request, 'profilephoto', 'uploads/personal', 'personal');
            if ($uploadedFile) {
                $attachmentID = $uploadedFile->id;
            } else {
                $attachmentID = '';
            }
        }

        $store = Personal::create([
            'fullname' => $data['fullname'],
            'tagline' => $data['tagline'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'website' => $data['website'],
            'attachment_id' => $attachmentID,
            'user_id' => auth()->user()->id,
        ]);

        if ($store) {
            flash(
                'Persona created successfully',
            )->success();
        } else {
            flash(
                'Unable to create Persona',
            )->error();
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Personal::findOrFail($id);
        if ($data->user_id && $data->user_id != auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        $user = $data->user;
        $attachment = Attachments::find($data->attachment_id);

        return view('modules.personal.show', compact('data', 'user', 'attachment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Personal::findOrFail($id);
        if ($data->user_id && $data->user_id != auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        $attachment = Attachments::find($data->attachment_id);

        return view('modules.personal.edit', compact('data', 'attachment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $personal = Personal::findOrFail($id);
        if ($personal->user_id && $personal->user_id != auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        $this->validate($request, [
            'fullname' => 'required',
            'profilephoto' => 'nullable|image|mimes:png,jpg|max:5120',
        ]);

        $data = [];
        if ($request->file('profilephoto')) {
            $uploadedFile = AttachmentsTrait::storeImage($request, 'profilephoto', 'uploads/personal', 'personal');
            if ($uploadedFile) {
                $data['attachment_id'] = $uploadedFile->id;
            }
        }

        $data['fullname'] = $request->input('fullname');
        $data['tagline'] = $request->input('tagline');
        $data['phone'] = $request->input('phone');
        $data['email'] = $request->input('email');
        $data['website'] = $request->input('website');

        $update = $personal->update($data);

        if ($update) {
            flash('Persona updated successfully')->success();
        } else {
            flash('Unable to update Persona')->error();
        }

        return redirect()->route('personal');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $personal = Personal::findOrFail($id);
        if ($personal->user_id && $personal->user_id != auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        PersonaTrait::deletePersona($id);
        flash('Persona deleted successfully')->success();
        return redirect()->route('personal');
    }

    public function restore($id)
    {
        $personal = Personal::withTrashed()->findOrFail($id);
        if ($personal->user_id && $personal->user_id != auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        PersonaTrait::restorePersona($id);
        flash('Persona restored successfully')->success();
        return redirect()->route('personal');
    }

    public function countstatus()
    {
        $count = PersonaTrait::countActiveStatusByUser(auth()->user()->id);
        return $count;
    }

    public function checkStatusById($id)
    {
        $result = Personal::where(['id' => $id, 'status' => 1, 'user_id' => auth()->user()->id])->get();
        if ($result->count() > 0) {
            return response()->json(['exist' => 1, 'status' => 1]);
        } else {
            return response()->json(['exist' => 0, 'status' => 1]);
        }
    }

    public function updateStatusById(Request $request, $id = null)
    {
        $targetId = $id ?? $request->input('id');
        $status = $request->input('status');
        Personal::findOrFail($targetId)->update(['status' => $status]);
        return response()->json(['success' => true]);
    }

    public function switchActivePersona(Request $request, $id = null)
    {
        $targetId = $id ?? $request->input('id');
        $userId = auth()->id();

        $targetPersona = Personal::where('user_id', $userId)->findOrFail($targetId);

        // Deactivate all personas for this user
        Personal::where('user_id', $userId)->update(['status' => 0]);

        // Activate target persona
        $targetPersona->update(['status' => 1]);

        return response()->json([
            'success' => true,
            'message' => "Switched active persona to {$targetPersona->fullname}",
            'persona' => [
                'id' => $targetPersona->id,
                'fullname' => $targetPersona->fullname,
                'tagline' => $targetPersona->tagline,
            ]
        ]);
    }
}
