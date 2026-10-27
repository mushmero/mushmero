<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitors;
use App\Models\User;
use Carbon\Carbon;
use App\Helpers\Helper;

class VisitorsLogController extends Controller
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
    public function index()
    {
        $data = array();
        $array = Visitors::latest()->limit(500)->get();

        $heads = [
            'User IP',
            'Visit Date',
            'User Agent',
            ['label' => 'Timestamp', 'width' => 20],
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->userip,
                Helper::formatDate($arr->datetime),
                $arr->useragent,
                Helper::formatDate($arr->created_at),
                '<nobr><a href="'.url('visitors/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [],
            'columns' => [null, null, null, null, null],
        ];

        return view('visitors.list', [
            'heads' => $heads,
            'config' => $config,
        ]);
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        //
    }
    public function show($id)
    {
        $data = Visitors::where('id', $id)->firstOrFail();

        return view('visitors.show', compact('data'));
    }
    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        //
    }
    public function destroy($id)
    {
        //
    }
}
