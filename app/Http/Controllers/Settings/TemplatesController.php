<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Traits\ExportTrait;
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
            flash('Template created successfully')->success();
            return redirect()->route('settings_template');
        }else{
            flash('Unable to create template')->error();
            return back();
        }
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

        $update = SettingsTemplate::findOrFail($id)->update($data);

        if($update){
            flash('Template updated successfully')->success();
        }else{
            flash('Unable to update template')->error();
        }

        return redirect()->route('settings_template');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SettingsTemplate::findOrFail($id)->delete();
        flash(
            'Template deleted successfully',
        )->success();
        return redirect()->route('settings_template');
    }

    public function preview($id)
    {
        $template = SettingsTemplate::findOrFail($id);
        if(PersonaTrait::checkPersona()->count() > 0){
            $content = $this->generateFullContent($template);
            $author = 'Amirul Hakim';
            $description = 'Resumed is a powerful and user-friendly tool designed to help job seekers create professional resumes online quickly and easily';
            return view('settings.template.preview', compact('id','author','description','template','content'));
        }else{
            return view('errors.inactive');
        }
    }

    private function generateFullContent($template)
    {
        $templateFields = Helper::templatefields();
        $content = PersonaTrait::getActivePersonaInformations();
        if (!$content) {
            return $template->design ?? '';
        }
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
                    $profile_image = "<div class=\"profile-image\"><img width=\"100\" height=\"100\" src=\"".url($content->attachments->path."/".$content->attachments->filename)."\" alt=\"$content->fullname\"></div>";
                    $fullcontent = Str::replace($field, $profile_image, $fullcontent);
                }
            }
            if($field == 'profile-name'){
                if(isset($content->fullname)){
                    $fullname = "<div class=\"fullname\">".$content->fullname."</div>";
                    $fullcontent = Str::replace($field, $fullname, $fullcontent);
                }
            }
            if($field == 'profile-tagline'){
                if(isset($content->tagline)){
                    $tagline = "<div class=\"tagline\">".$content->tagline."</div>";
                    $fullcontent = Str::replace($field, $tagline, $fullcontent);
                }
            }
            if($field == 'profile-email'){
                if(isset($content->email)){
                    $email = "<div class=\"row email text-color-primary\"><i class=\"fas fa-envelope\"></i><a class=\"text-color-primary\" href=\"mailto:$content->email\" target=\"_blank\">$content->email</a></div>";
                    $fullcontent = Str::replace($field, $email, $fullcontent);
                }
            }
            if($field == 'profile-phone'){
                if(isset($content->phone)){
                    $phone = "<div class=\"row phone text-color-primary\"><i class=\"fas fa-phone\"></i><a class=\"text-color-primary\" href=\"tel:$content->phone\" target=\"_blank\">$content->phone</a></div>";
                    $fullcontent = Str::replace($field, $phone, $fullcontent);
                }
            }
            if($field == 'profile-website'){
                if(isset($content->website)){
                    $website = "<div class=\"row website text-color-primary\"><i class=\"fas fa-globe\"></i><a class=\"text-color-primary\" href=\"$content->website\" target=\"_blank\">$content->website</a></div>";
                    $fullcontent = Str::replace($field, $website, $fullcontent);
                }
            }
            if($field == 'social-info'){
                if(isset($content->socials)){
                    $social_info = '';
                    foreach($content->socials as $social){
                        $iconClass = $social->allicons ? $social->allicons->fullname : 'fas fa-link';
                        $social_info .= "<div class=\"row social text-color-primary\"><i class=\"".$iconClass."\"></i><a class=\"text-color-primary\" href=\"".$social->link."\" target=\"_blank\"> ".$social->name."</a></div>";
                    }
                    $fullcontent = Str::replace($field, $social_info, $fullcontent);
                }
            }
            if($field == 'education-info'){
                if(isset($content->educations)){
                    $education_info = '';
                    foreach($content->educations as $education){
                        $education_info .= "<div class=\"row edu-item text-color-primary\"><div>".$education->name."</div><div>".$education->institution." (".$education->start."-".$education->end.")</div></div>";
                    }
                    $fullcontent = Str::replace($field, $education_info, $fullcontent);
                }
            }
            if($field == 'language-info'){
                if(isset($content->languages)){
                    $language_info = '';
                    foreach($content->languages as $language){
                        $proficiencyName = $language->allproficiency ? $language->allproficiency->proficiency : '';
                        $language_info .= "<div class=\"row lang-item text-color-primary\">".$language->name." (<span class=\"proficiency\">".$proficiencyName."</span>)</div>";
                    }
                    $fullcontent = Str::replace($field, $language_info, $fullcontent);
                }
            }
            if($field == 'interest-info'){
                if(isset($content->interests)){
                    $interest_info = '';
                    foreach($content->interests as $interest){
                        $interest_info .= "<div class=\"row interest-item text-color-primary\">".$interest->name."</div>";
                    }
                    $fullcontent = Str::replace($field, $interest_info, $fullcontent);
                }
            }
            if($field == 'objective-info'){
                $objectives = $content->objectives->where('is_active', 1)->first();
                if(isset($objectives)){
                    $objective = "<div class=\"row objective-info text-color-secondary\">".$objectives->content."</div>";
                    $fullcontent = Str::replace($field, $objective , $fullcontent);
                }
            }
            if($field == 'experience-info'){
                if(isset($content->experiences)){
                    $experience_info = '';
                    $sorted_experiences = $content->experiences->sortByDesc(function ($exp) {
                        try {
                            return Carbon::createFromFormat('d/m/Y', $exp['start'] ?? '')->timestamp;
                        } catch (\Throwable $e) {
                            return strtotime($exp['start'] ?? 'now');
                        }
                    });
                    foreach($sorted_experiences as $experience){
                        try {
                            $start = Carbon::createFromFormat('d/m/Y', $experience->start)->format('m/Y');
                        } catch (\Throwable $e) {
                            $start = $experience->start ?? '';
                        }

                        if($experience->end != 'current' && !empty($experience->end)){
                            try {
                                $end = Carbon::createFromFormat('d/m/Y', $experience->end)->format('m/Y');
                            } catch (\Throwable $e) {
                                $end = $experience->end;
                            }
                        }else{
                            $end = Str::ucfirst($experience->end ?? 'Current');
                        }
                        $experience_info .= "<div class=\"row exp-item text-color-secondary\"><div class=\"exp-position col-6\">".e($experience->position)."</div><div class=\"exp-length col-6 right\">".e($start)." - ".e($end)."</div><div class=\"exp-employer col-12\">".e($experience->employer)."</div><div class=\"col-12\">".$experience->description."</div></div>";
                    }
                    $fullcontent = Str::replace($field, $experience_info, $fullcontent);
                }
            }
            if($field == 'project-info'){
                if(isset($content->projects)){
                    $project_info = '';
                    foreach($content->projects as $project){
                        $statusName = $project->allstatus ? $project->allstatus->status : 'Completed';
                        $project_info .= "<div class=\"row proj-item text-color-secondary\"><div class=\"col-12 proj-title\">".e($project->name)." - <small>(".e($statusName).")</small></div><div class=\"col-12\">".$project->description."</div></div>";
                    }
                    $fullcontent = Str::replace($field, $project_info, $fullcontent);
                }                
            }
            if($field == 'skill-info'){
                if(isset($content->skills)){
                    $skill_info = '';
                    foreach($content->skills as $skill){
                        $iconClass = $skill->allicons ? $skill->allicons->fullname : 'fas fa-star';
                        $skill_info .= "<div class=\"row skill-item text-color-secondary\"><div class=\"col-2\"><i class=\"".e($iconClass)."\"></i> ".e($skill->name)."</div><div class=\"col-6\"><div class=\"progress\"><div class=\"progress-bar text-bold main-color\" role=\"progressbar\" aria-valuenow=\"".$skill->percentage."\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: ".$skill->percentage."%;\">".$skill->percentage."</div></div></div></div>";
                    }
                    $fullcontent = Str::replace($field, $skill_info, $fullcontent);
                }
            }
        }
        return $fullcontent;
    }

    public function generatePDF($id)
    {
        $persona = PersonaTrait::getActivePersonaInformations(auth()->id());
        if (!$persona) {
            flash('Please create and activate your Personal profile before generating a PDF resume.')->warning();
            return redirect()->route('personal');
        }

        $template = SettingsTemplate::findOrFail($id);
        $content = $this->generateFullContent($template);
        $author = $persona->fullname ?? 'Resumed User';
        $description = 'Resumed is a powerful and user-friendly tool designed to help job seekers create professional resumes online quickly and easily';
        $filename = Str::slug($persona->fullname ?? 'Resume').'_'.Str::slug($template->name).'_'.Carbon::now()->format('Ymd');
        $html = response()->view('settings.template.preview', compact('id','author','description','template','content'))->getContent();

        return ExportTrait::generatePDF($html, $filename, ['dpi' => 170]);
    }
}
