<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Traits\PersonaTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SettingsTemplate;
use App\Helpers\Helper;
use Str;

class TemplatesController extends Controller
{
    protected $btnEdit, $btnDelete, $btnDetails, $btnPreview;

    /**
     * Declare default template for button edit, button delete & button view
     */
    public function __construct()
    {
        $this->middleware('autologout');
        $this->btnEdit = config('adminlte.btn-edit');
        $this->btnDelete = config('adminlte.btn-delete');
        $this->btnDetails = config('adminlte.btn-view');
        $this->btnPreview = config('adminlte.btn-preview');
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
                '<nobr><a href="'.url('settings_template/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('settings_template/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('settings_template/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
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
            'custom_css' => Helper::minifyCSSJS($data['custom_css'], 'css'),
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

        return view('settings.template.show', compact('data','id'));
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
            'name' => 'required',
            'design' => 'required',
            'custom_css' => 'nullable',
            'primary_colour' => 'required',
            'secondary_colour' => 'required',
        ]);

        $data['name'] = $request->input('name');
        $data['design'] = $request->input('design');
        $data['custom_css'] = Helper::minifyCSSJS($request->input('custom_css'),'css');
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

    public function preview($id)
    {
        $template = SettingsTemplate::where('id', $id)->first();
        $content = $this->generateFullContent($template);
        $author = 'Amirul Hakim';
        $description = 'Resumed is a powerful and user-friendly tool designed to help job seekers create professional resumes online quickly and easily';
        return view('settings.template.preview', compact('id','author','description','template','content'));
    }

    private function generateFullContent($template)
    {
        $templateFields = Helper::templatefields();
        $content = PersonaTrait::getActivePersonaInformations();
        $fullcontent = $template->design;
        foreach($templateFields as $field){
            if($field == 'main-color'){
                if(isset($template->primary_colour)){
                    $fullcontent = Str::replace($field, $template->primary_colour, $fullcontent);
                }else{
                    $fullcontent = Str::replace($field, 'bg-gray-dark', $fullcontent);
                }                
            }
            if($field == 'accent-color'){
                if(isset($template->secondary_colour)){
                    $fullcontent = Str::replace($field, $template->secondary_colour, $fullcontent);
                }else{
                    $fullcontent = Str::replace($field, 'bg-white', $fullcontent);
                }             
            }
            if($field == 'text-color-primary'){
                if(isset($template->primary_colour)){
                    $primary_color = Helper::gettextcolor($template->primary_colour);
                }else{
                    $primary_color = 'bg-dark-gray';
                }                
                $fullcontent = Str::replace($field, $primary_color, $fullcontent);
            }
            if($field == 'text-color-secondary'){
                if(isset($template->secondary_colour)){
                    $secondary_color = Helper::gettextcolor($template->secondary_colour);
                }else{
                    $secondary_color = 'bg-white';
                }                
                $fullcontent = Str::replace($field, $secondary_color, $fullcontent);
            }
            if($field == 'profile-image'){
                if(isset($content->attachments->filename)){
                    $profile_image = "<img width=\"100\" height=\"100\" src=\"".url($content->attachments->path."/".$content->attachments->filename)."\" alt=\"$content->fullname\">";
                    $fullcontent = Str::replace($field, $profile_image, $fullcontent);
                }
            }
            if($field == 'profile-name'){
                if(isset($content->fullname)){
                    $fullcontent = Str::replace($field, $content->fullname, $fullcontent);
                }
            }
            if($field == 'profile-tagline'){
                if(isset($content->tagline)){
                    $fullcontent = Str::replace($field, $content->tagline, $fullcontent);
                }
            }
            if($field == 'profile-email'){
                if(isset($content->email)){
                    $email = "<a class=\"text-color-primary\" href=\"mailto:$content->email\" target=\"_blank\">$content->email</a>";
                    $fullcontent = Str::replace($field, $email, $fullcontent);
                }
            }
            if($field == 'profile-phone'){
                if(isset($content->phone)){
                    $phone = "<a class=\"text-color-primary\" href=\"tel:$content->phone\" target=\"_blank\">$content->phone</a>";
                    $fullcontent = Str::replace($field, $phone, $fullcontent);
                }
            }
            if($field == 'profile-website'){
                if(isset($content->website)){
                    $website = "<a class=\"text-color-primary\" href=\"$content->website\" target=\"_blank\">$content->website</a>";
                    $fullcontent = Str::replace($field, $website, $fullcontent);
                }
            }
            if($field == 'social-info'){
                if(isset($content->socials)){
                    $social_info = '';
                    foreach($content->socials as $social){
                        $social_info .= "<div class=\"row social text-color-primary\"><i class=\"".$social->allicons->fullname."\"></i><a class=\"text-color-primary\" href=\"".$social->link."\" target=\"_blank\"> ".$social->name."</a></div>";
                    }
                    $fullcontent = Str::replace($field, $social_info, $fullcontent);
                }
            }
            if($field == 'education-info'){
                if(isset($content->educations)){
                    $education_info = '';
                    foreach($content->educations as $education){
                        $education_info .= "<div class=\"row education text-color-primary\"><div>".$education->name."</div><div>".$education->institution." (".$education->start."-".$education->end.")</div></div>";
                    }
                    $fullcontent = Str::replace($field, $education_info, $fullcontent);
                }
            }
            if($field == 'language-info'){
                if(isset($content->languages)){
                    $language_info = '';
                    foreach($content->languages as $language){
                        $language_info .= "<div class=\"row language text-color-primary\">".$language->name." (<span class=\"proficiency\">".$language->allproficiency->proficiency."</span>)</div>";
                    }
                    $fullcontent = Str::replace($field, $language_info, $fullcontent);
                }
            }
            if($field == 'interest-info'){
                if(isset($content->interests)){
                    $interest_info = '';
                    foreach($content->interests as $interest){
                        $interest_info .= "<div class=\"row language text-color-primary\">".$interest->name."</div>";
                    }
                    $fullcontent = Str::replace($field, $interest_info, $fullcontent);
                }
            }
            if($field == 'objective-info'){
                $objectives = $content->objectives->where('is_active', 1)->first();
                if(isset($objectives)){
                    $fullcontent = Str::replace($field, $objectives->content, $fullcontent);
                }
            }
            if($field == 'experience-info'){
                if(isset($content->experiences)){
                    $experience_info = '';
                    foreach($content->experiences as $experience){
                        $start = Carbon::createFromFormat('d/m/Y',$experience->start)->format('m/Y');
                        if($experience->end != 'current'){
                            $end = Carbon::createFromFormat('d/m/Y',$experience->end)->format('m/Y');
                        }else{
                            $end = Str::ucfirst($experience->end);
                        }
                        $experience_info .= "<div class=\"row experience text-color-secondary\"><div class=\"col-6\">".$experience->position."</div><div class=\"col-6 right\">".$start." - ".$end."</div><div class=\"col-12\">".$experience->employer."</div><div class=\"col-12\">".$experience->description."</div></div>";
                    }
                    $fullcontent = Str::replace($field, $experience_info, $fullcontent);
                }
            }
            if($field == 'project-info'){
                if(isset($content->projects)){
                    $project_info = '';
                    foreach($content->projects as $project){
                        $project_info .= "<div class=\"row project text-color-secondary\"><div class=\"col-12 title\">".$project->name." - (<smal>".$project->allstatus->status."</smal>)</div><div class=\"col-12\">".$project->description."</div></div>";
                    }
                    $fullcontent = Str::replace($field, $project_info, $fullcontent);
                }                
            }
            if($field == 'skill-info'){
                if(isset($content->skills)){
                    $skill_info = '';
                    foreach($content->skills as $skill){
                        $skill_info .= "<div class=\"row skill text-color-secondary\"><div class=\"col-4\"><i class=\"".$skill->allicons->fullname."\"></i> ".$skill->name."</div><div class=\"col-8\"><div class=\"progress\"><div class=\"progress-bar text-bold progress-bar-striped progress-bar-animated main-color\" role=\"progressbar\" aria-valuenow=\"".$skill->percentage."\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: ".$skill->percentage."%; background-color: rgb(52, 125, 241);\">".$skill->percentage."</div></div></div></div>";
                    }
                    $fullcontent = Str::replace($field, $skill_info, $fullcontent);
                }
            }
        }
        return $fullcontent;
    }
}
