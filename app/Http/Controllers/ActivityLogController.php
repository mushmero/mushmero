<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use App\Helpers\Helper;

class ActivityLogController extends Controller
{
    protected $btnEdit, $btnDelete, $btnDetails;

    /**
     * Declare default template for button edit, button delete & button view
     */
    public function __construct()
    {
        $this->middleware('autologout');
        $this->middleware(function ($request, $next) {
            if (\Illuminate\Support\Facades\Gate::denies('view-logs')) {
                abort(403, 'Logs are not accessible for demo users.');
            }
            return $next($request);
        });
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
        $array = ActivityLog::with('user')->latest()->limit(500)->get();

        $heads = [
            'User',
            'Module',
            'Description',
            ['label' => 'Timestamp', 'width' => 20],
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $userName = 'System';
            if (!is_null($arr->causer_id)) {
                $userName = $arr->user ? $arr->user->name : 'Deleted User';
            }
            $data[] = array(
                $userName,
                $arr->log_name,
                $arr->description,
                Helper::formatDate($arr->created_at),
                '<nobr><a href="'.url('user_access/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [],
            'columns' => [null, null, null, null, null],
        ];

        return view('logs.list', [
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = array();
        $new = '';
        $old = '';
        $activity = ActivityLog::where('id', $id)->firstOrFail();

        if(isset($activity->changes)){
            $newChanges = isset($activity->changes['attributes']) ? $activity->changes['attributes'] : '';
            $oldChanges = isset($activity->changes['old']) ? $activity->changes['old'] : '';
        }

        if(!empty($oldChanges) && !empty($newChanges)){
            if(is_array($newChanges) && is_array($oldChanges)){
                $new = array_diff_assoc($newChanges, $oldChanges);
                $old = array_diff_assoc($oldChanges, $newChanges);
            }        
        }else{
            $new = $newChanges;
            $old = '';
        }

        if(!empty($activity->subject_type)){
            $name = $activity->subject_type::find($activity->subject_id)->getFillable()[0];
        }

        if(isset($activity)){
            $data = array(
                'user' => !empty($activity->causer_id) ? $activity->user->name : 'System',
                'action' => $activity->log_name,
                'description' => $activity->description,
                'target' => isset($activity->subject_id) ? $activity->subject_type::find($activity->subject_id)->$name : '',
                'event' => isset($activity->event) ? $activity->event : '',
                'new' => $new,
                'old' => $old,
                'timestamp' => Helper::formatDate($activity->created_at),
            );
        }
        $timestamp = ['created_at', 'updated_at'];

        return view('logs.show', compact('data', 'timestamp'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
