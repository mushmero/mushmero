<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SettingsTemplate;
use App\Helpers\Helper;

class TemplatesController extends Controller
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
        $array = SettingsTemplate::all();

        $heads = [
            'Name',
            'Primary Colour',
            'Secondary Colour',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->name,
                $arr->primary_colour,
                $arr->secondary_colour,
                '<nobr><a href="'.url('settings_template/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('settings_template/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null],
        ];

        return view('settings.template.list', [
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
        $fields = Helper::templatefields();
        $configFields = [
            "placeholder" => "Select field",
            "allowClear" => true,
        ];

        $colors = Helper::bgcolor();
        $configColor = [
            "placeholder" => "Select color",
            "allowClear" => true,
        ];

        $configTE = [
            "height" => "300",
            "toolbar" => [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
        ];

        return view('settings.template.create', compact('fields','configFields','colors','configColor','configTE'));
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
            'name' => 'required|unique:settings_templates',
            'design' => 'required',
            'custom_css' => 'nullable',
            'primary_colour' => 'required',
            'secondary_colour' => 'required',
        ]);

        $data = $request->all();

        $store = SettingsTemplate::create([
            'name' => $data['name'],
            'design' => $data['design'],
            'custom_css' => $data['custom_css'],
            'primary_colour' => $data['primary_colour'],
            'secondary_colour' => $data['secondary_colour'],
        ]);

        if($store){
            flash(
                'Template created successfully',
            )->success();
        }else{
            flash(
                'Unable to create template',
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
        $data = SettingsTemplate::where('id', $id)->firstOrFail();

        return view('settings.template.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = SettingsTemplate::where('id', $id)->firstOrFail();
        $fields = Helper::templatefields();
        $configFields = [
            "placeholder" => "Select field",
            "allowClear" => true,
        ];

        $colors = Helper::bgcolor();
        $configColor = [
            "placeholder" => "Select color",
            "allowClear" => true,
        ];

        $configTE = [
            "height" => "300",
            "toolbar" => [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
        ];

        return view('settings.template.edit', compact('data','fields','configFields','colors','configColor','configTE'));
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
            'name' => 'required|unique:settings_templates',
            'design' => 'required',
            'custom_css' => 'nullable',
            'primary_colour' => 'required',
            'secondary_colour' => 'required',
        ]);

        $data['name'] = $request->input('name');
        $data['design'] = $request->input('design');
        $data['custom_css'] = $request->input('custom_css');
        $data['primary_colour'] = $request->input('primary_colour');
        $data['secondary_colour'] = $request->input('secondary_colour');

        $update = SettingsTemplate::find($id)->update($data);

        if($update){
            flash(
                'Template updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update template',
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
        SettingsTemplate::find($id)->delete();
        flash(
            'Template deleted successfully',
        )->success();
        return redirect()->route('settings_template');
    }
}
