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

    protected $btnEdit, $btnDelete, $btnDetails;

    /**
     * Declare default template for button edit, button delete & button view
     */
    public function __construct()
    {
        $this->middleware('autologout');
        $this->btnEdit = config('adminlte.btn-edit');
        $this->btnDelete = config('adminlte.btn-delete');
        $this->btnDetails = config('adminlte.btn-view');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $array = Personal::all();

        $heads = [
            'Profile',
            'Status',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            
            $data[] = array(
                '<div class="row"><div class="col-1"><img src="'.url($arr->attachments->path.'/'.$arr->attachments->filename).'" alt="'.$arr->attachments->altname.'" width="50" height="50" class="img-center"></div><div class="col-11">'.$arr->fullname.'&nbsp;'.(!empty($arr->tagline) ? '('.$arr->tagline.')' : '').(!empty($arr->phone) ? '<br><i class="fa-fw fas fa-mobile-alt"></i>&nbsp;'.$arr->phone : '').(!empty($arr->email) ? '&nbsp;<i class="fa-fw fas fa-envelope"></i>&nbsp;'.$arr->email : '').(!empty($arr->website) ? '<br><i class="fa-fw fas fa-globe"></i>&nbsp;'.$arr->website : '').'</div></div>',
                '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="activeSwitch'.$arr->id.'" name="activeSwitch'.$arr->id.'" onchange="switchAjax('.$arr->id.')" '.($arr->status == 1 ? "checked" : "").'  /><label class="active_switch custom-control-label" for="activeSwitch'.$arr->id.'">'.($arr->status == 1 ? "Active" : "Inactive").'</label></div>',
                User::find($arr->user_id)->name,
                '<nobr><a href="'.url('personal/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('personal/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('personal/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
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
        if($request->file('profilephoto')){
            $uploadedFile = AttachmentsTrait::storeImage($request, 'profilephoto', 'uploads/personal', 'personal');
            if($uploadedFile){
                $attachmentID = $uploadedFile->id;
            }else{
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

        if($store){
            flash(
                'Persona created successfully',
            )->success();
        }else{
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
        $data = Personal::where('id', $id)->firstOrFail();

        $user = User::find($data->user_id);

        $attachment = Attachments::find($data->attachment_id);

        return view('modules.personal.show', compact('data','user','attachment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Personal::where('id', $id)->firstOrFail();

        $attachment = Attachments::find($data->attachment_id);

        return view('modules.personal.edit', compact('data','attachment'));
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
        $this->validate($request, [
            'fullname' => 'required',       
            'profilephoto' => 'image|mimes:png,jpg|max:5120',
        ]);

        if($request->file('profilephoto')){
            $uploadedFile = AttachmentsTrait::storeImage($request, 'profilephoto', 'uploads/personal', 'personal');
            if($uploadedFile){
                $attachmentID = $uploadedFile->id;
                $data['attachment_id'] = $attachmentID;
            }
        }

        $data['fullname'] = $request->input('fullname');
        $data['tagline'] = $request->input('tagline');
        $data['phone'] = $request->input('phone');
        $data['email'] = $request->input('email');
        $data['website'] = $request->input('website');
        $data['user_id'] = auth()->user()->id;

        $update = Personal::find($id)->update($data);

        if($update){
            flash(
                'Persona updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update Persona',
            )->error();
        }

         return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Personal::find($id)->delete();
        flash(
            'Persona deleted successfully',
        )->success();
        return redirect()->route('personal');
    }

    public function countstatus()
    {
        $count = PersonaTrait::countActiveStatusByUser(auth()->user()->id);
        return $count;
    }

    public function checkStatusById($id)
    {
        $result = Personal::where(['id' => $id, 'user_id' => auth()->user()->id])->get();

        if(isset($result)){
            return true;
        }else{
            return false;
        }
    }

    public function updateStatusById(Request $request, $id)
    {
        $data['status'] = $request->input('status');
        Personal::find($id)->update($data);
        return true;
    }
}
