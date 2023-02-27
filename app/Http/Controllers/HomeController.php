<?php

namespace App\Http\Controllers;

use App\Models\Educations;
use App\Models\Experiences;
use App\Models\Interests;
use App\Models\Languages;
use App\Models\Objectives;
use App\Models\Personal;
use App\Models\Projects;
use App\Models\Skills;
use App\Models\Socials;
use Illuminate\Http\Request;
use Illuminate\Foundation\Inspiring;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('autologout');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home', [
            'inspiration' => $this->getQuotes(),
            'profile' => $this->getProfile(),
            'experience' => $this->getExperience(),
            'skill' => $this->getSkills(),
            'language' => $this->getLanguages(),
            'education' => $this->getEducations(),
            'interest' => $this->getInterests(),
            'objective' => $this->getObjectives(),
            'project' => $this->getProjects(),
            'social' => $this->getSocials(),
        ]);
    }

    public function getQuotes()
    {
        return Inspiring::quotes()->random();
    }

    public function getProfile()
    {
        $profile = Personal::where('user_id',auth()->user()->id)->firstOrFail();
        $data = array(
            'fullname' => isset($profile->fullname) ? $profile->fullname : '',
            'tagline' => isset($profile->tagline) ? $profile->tagline : '',
            'phone' => isset($profile->phone) ? $profile->phone : '',
            'email' => isset($profile->email) ? $profile->email : '',
            'profile_image' => isset($profile->attachment_id) ? url($profile->attachments->path.'/'.$profile->attachments->filename) : '',
        );
        return $data;
    }

    public function getExperience()
    {
        $experiences = Experiences::where('user_id',auth()->user()->id)->get();
        $data = array();
        foreach($experiences as $experience){
            $data['position'][] = $experience->position;
            $data['employer'][] = $experience->employer;
            $data['length'] = Carbon::now()->format('Y') - ($experiences->min('start') ? Carbon::parse($experiences->min('start'))->format('Y') : Carbon::now()->format('Y'));
        }
        $data['count'] = isset($experiences) ? $experiences->count() : 0;
        return $data;
    }

    public function getSkills()
    {
        $skills = Skills::where('user_id',auth()->user()->id)->get();
        $data = array();
        foreach($skills as $skill){
            $data['name'][] = $skill->name;
            $data['percentage'][] = $skill->percentage;
        }
        $data['count'] = isset($skills) ? $skills->count() : 0;
        return $data;
    }

    public function getEducations()
    {
        $educations = Educations::where('user_id',auth()->user()->id)->get();
        $data = array();
        foreach($educations as $education){
            $data['name'][] = $education->name;
            $data['institution'][] = $education->institution;
            $data['level'][] = $education->alllevel->name;
        }
        $data['count'] = isset($educations) ? $educations->count() : 0;
        return $data;
    }

    public function getLanguages()
    {
        $languages = Languages::where('user_id',auth()->user()->id)->get();
        $data = array();
        foreach($languages as $language){
            $data['name'][] = $language->name;
            $data['proficiency'][] = $language->allproficiency->proficiency;
        }
        $data['count'] = isset($languages) ? $languages->count() : 0;
        return $data;
    }

    public function getInterests()
    {
        $interests = Interests::where('user_id',auth()->user()->id)->get();
        $data = array();
        foreach($interests as $interest){
            $data['name'][] = $interest->name;
        }
        $data['count'] = isset($interest) ? $interests->count() : 0;
        return $data;
    }

    public function getObjectives()
    {
        $objectives = Objectives::where(['user_id' => auth()->user()->id])->get();
        $data = array();
        foreach($objectives as $objective){
            $data['name'][] = $objective->content;
        }
        $data['count'] = isset($objectives) ? $objectives->count() : 0;
        return $data;
    }

    public function getProjects()
    {
        $projects = Projects::where(['user_id' => auth()->user()->id])->get();
        $data = array();
        foreach($projects as $project){
            $data['name'][] = $project->name;
            $data['status'][] = $project->allstatus->status;
        }
        $data['count'] = isset($projects) ? $projects->count() : 0;
        return $data;
    }

    public function getSocials()
    {
        $socials = Socials::where(['user_id' => auth()->user()->id])->get();
        $data = array();
        foreach($socials as $social){
            $data['name'][] = $social->name;
            $data['status'][] = $social->link;
            $data['icon'][] = $social->allicons->fullname;
        }
        $data['count'] = isset($socials) ? $socials->count() : 0;
        return $data;
    }
}
