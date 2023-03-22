<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Leads;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeadsController extends Controller
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
        $array = Leads::all()->sortByDesc("created_at");

        $heads = [
            'Name',
            'Email',
            'Proposed Link',
            'Activate',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->name,
                $arr->email,
                $arr->subdomain ?? 'Not Applicable',
                '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="activeSwitch'.$arr->id.'" name="activeSwitch'.$arr->id.'" onchange="switchAjax('.$arr->id.')" '.($arr->activate == 1 ? "checked" : "").' /><label class="active_switch custom-control-label" for="activeSwitch'.$arr->id.'">'.($arr->activate == 1 ? "Yes" : "No").'</label></div>',
                '<nobr><a href="'.url('leads/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [],
            'columns' => [null, null, null, null, null],
            'dom' => '<"top row"<"col-1 left"l><"pl-4 col-5"B><"col-6 right"f>>rtip',
            'buttons' => [
                [
                    'text' => 'Generate Link',
                    'className' => 'btn-generate-link btn-success btn-sm',
                ],
            ],
        ];

        return view('leads.list', [
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
        $data = Leads::where('id', $id)->firstOrFail();

        return view('leads.show', compact('data'));
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

    public function generatelink()
    {
        $generate = \Artisan::call('sublink:generate');
        if($generate){
            return response()->json(['success' => 'Links generated']);
        }else{
            return response()->json(['error' => 'No links generated']);
        }
    }

    public function checkLinkExist(Request $request)
    {
        $data = $request->all();
        $check = Leads::where(['id' => $data['id'], 'activate' => 1])->get();
        if($check->count() > 0){
            return response()->json(['activate' => true]);
        }else{
            $exist = Leads::where(['id' => $data['id'], 'activate' => 0])->where('subdomain','<>','')->get();
            if($exist->count() > 0){
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => 'Link not exists. Please generate']);
            }
        }
    }

    public function activateLink(Request $request)
    {
        $data = $request->all();
        $update = [];
        if($data['activate'] == 1){
            $update = [
                'activate' => 1,
                'activate_time' => Helper::formatDate(now(), 'Asia/Kuala_Lumpur', 'Y-m-d H:i:s'),
                'deactivate_time' => null,
                'reason' => 'Link activated',
            ];
        }else{
            $update = [
                'activate' => 0,
                'activate_time' => null,
                'deactivate_time' => Helper::formatDate(now(), 'Asia/Kuala_Lumpur', 'Y-m-d H:i:s'),
                'reason' => 'Link deactivated',
            ];
        }
        Leads::find($data['id'])->update($update);
        return true;
    }
}
