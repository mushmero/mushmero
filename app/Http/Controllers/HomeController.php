<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Traits\PersonaTrait;
use App\Models\Educations;
use App\Models\Experiences;
use App\Models\Interests;
use App\Models\Languages;
use App\Models\Objectives;
use App\Models\Personal;
use App\Models\Projects;
use App\Models\Skills;
use App\Models\Socials;
use DB;
use Illuminate\Http\Request;
use Illuminate\Foundation\Inspiring;
use Carbon\Carbon;
use Nette\Utils\Json;
use PragmaRX\Countries\Package\Countries;

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
        $activePersona = PersonaTrait::getActivePersonaByAuthUser();
        if($activePersona !== null){
            $allPersonas = Personal::where('user_id', auth()->user()->id)->get();
            $experience = $this->getExperience();
            $skill = $this->getSkills();
            $project = $this->getProjects();
            $social = $this->getSocials();
            $education = $this->getEducations();
            $language = $this->getLanguages();
            $interest = $this->getInterests();
            $objective = $this->getObjectives();
            $profile = $this->getProfile($activePersona);
            $visitorStats = $this->getVisitorStats();
            $recentActivities = $this->getRecentActivities();
            $readiness = $this->calculateReadiness($activePersona, $experience['count'] ?? 0, $skill['count'] ?? 0, $project['count'] ?? 0, $social['count'] ?? 0);

            return view('home', [
                'inspiration' => $this->getQuotes(),
                'activePersona' => $activePersona,
                'allPersonas' => $allPersonas,
                'profile' => $profile,
                'experience' => $experience,
                'skill' => $skill,
                'language' => $language,
                'education' => $education,
                'interest' => $interest,
                'objective' => $objective,
                'project' => $project,
                'social' => $social,
                'visitorStats' => $visitorStats,
                'recentActivities' => $recentActivities,
                'readiness' => $readiness,
            ]);
        }else{
            return view('no-access');
        }
    }

    public function getQuotes()
    {
        return Inspiring::quotes()->random();
    }

    public function getProfile($persona = null)
    {
        $profile = $persona ?? Personal::where(['user_id' => auth()->user()->id, 'status' => 1])->first() ?? Personal::where('user_id', auth()->user()->id)->first();
        $data = array(
            'id' => isset($profile->id) ? $profile->id : null,
            'fullname' => isset($profile->fullname) ? $profile->fullname : '',
            'tagline' => isset($profile->tagline) ? $profile->tagline : '',
            'phone' => isset($profile->phone) ? $profile->phone : '',
            'email' => isset($profile->email) ? $profile->email : '',
            'website' => isset($profile->website) ? $profile->website : '',
            'profile_image' => isset($profile->attachment_id) && $profile->attachments ? url($profile->attachments->path.'/'.$profile->attachments->filename) : '',
        );
        return $data;
    }

    public function getExperience()
    {
        $experiences = Experiences::where('user_id',auth()->user()->id)->get();
        $data = array();
        $data['length'] = 0;
        foreach($experiences as $experience){
            $data['position'][] = $experience->position;
            $data['employer'][] = $experience->employer;
            $startYear = $experiences->min('start') ? Carbon::parse($experiences->min('start'))->format('Y') : Carbon::now()->format('Y');
            $data['length'] = max(0, Carbon::now()->format('Y') - $startYear);
        }
        $data['count'] = isset($experiences) ? $experiences->count() : 0;
        $data['items'] = $experiences;
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
        $data['items'] = $skills;
        return $data;
    }

    public function getEducations()
    {
        $educations = Educations::with('alllevel')->where('user_id',auth()->user()->id)->get();
        $data = array();
        foreach($educations as $education){
            $data['name'][] = $education->name;
            $data['institution'][] = $education->institution;
            $data['level'][] = $education->alllevel ? $education->alllevel->name : 'Standard';
        }
        $data['count'] = isset($educations) ? $educations->count() : 0;
        return $data;
    }

    public function getLanguages()
    {
        $languages = Languages::with('allproficiency')->where('user_id',auth()->user()->id)->get();
        $data = array();
        foreach($languages as $language){
            $data['name'][] = $language->name;
            $data['proficiency'][] = $language->allproficiency ? $language->allproficiency->proficiency : 'Fluent';
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
        $data['count'] = isset($interests) ? $interests->count() : 0;
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
        $projects = Projects::with('allstatus')->where(['user_id' => auth()->user()->id])->get();
        $data = array();
        $completed = 0;
        $inProgress = 0;
        foreach($projects as $project){
            $statusName = $project->allstatus ? $project->allstatus->status : 'Active';
            $data['name'][] = $project->name;
            $data['status'][] = $statusName;
            if (stripos($statusName, 'complete') !== false) {
                $completed++;
            } else {
                $inProgress++;
            }
        }
        $data['count'] = isset($projects) ? $projects->count() : 0;
        $data['completed'] = $completed;
        $data['in_progress'] = $inProgress;
        $data['items'] = $projects;
        return $data;
    }

    public function getSocials()
    {
        $socials = Socials::with('allicons')->where(['user_id' => auth()->user()->id])->get();
        $data = array();
        foreach($socials as $social){
            $data['name'][] = $social->name;
            $data['status'][] = $social->link;
            $data['icon'][] = $social->allicons ? $social->allicons->fullname : 'fas fa-link';
        }
        $data['count'] = isset($socials) ? $socials->count() : 0;
        return $data;
    }

    public function getVisitorStats()
    {
        try {
            $total = DB::table('visitors')->count();
            $countriesCount = DB::table('visitors')
                ->whereNotNull('countryCode')
                ->distinct('countryCode')
                ->count('countryCode');

            $topCountries = DB::table('visitors')
                ->selectRaw('count(*) as count, country, countryCode')
                ->whereNotNull('country')
                ->whereNotNull('countryCode')
                ->groupBy('country', 'countryCode')
                ->orderByDesc('count')
                ->limit(4)
                ->get();

            return [
                'total' => $total,
                'countries_count' => $countriesCount,
                'top_countries' => $topCountries,
            ];
        } catch (\Throwable $e) {
            return [
                'total' => 0,
                'countries_count' => 0,
                'top_countries' => collect(),
            ];
        }
    }

    public function getRecentActivities()
    {
        try {
            return DB::table('activity_log')
                ->latest('id')
                ->limit(4)
                ->get()
                ->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'log_name' => $log->log_name ?? 'System',
                        'description' => $log->description,
                        'created_at' => Carbon::parse($log->created_at)->diffForHumans(),
                    ];
                });
        } catch (\Throwable $e) {
            return collect();
        }
    }

    public function calculateReadiness($persona, $experienceCount, $skillsCount, $projectsCount, $socialsCount)
    {
        $score = 0;
        $missing = [];

        if ($persona && !empty($persona->attachment_id)) {
            $score += 15;
        } else {
            $missing[] = 'Upload profile photo';
        }

        if ($persona && !empty($persona->tagline)) {
            $score += 15;
        } else {
            $missing[] = 'Add professional tagline';
        }

        if ($experienceCount >= 2) {
            $score += 20;
        } elseif ($experienceCount === 1) {
            $score += 10;
            $missing[] = 'Add 1 more work experience';
        } else {
            $missing[] = 'Add work experience';
        }

        if ($skillsCount >= 3) {
            $score += 20;
        } elseif ($skillsCount > 0) {
            $score += 10;
            $missing[] = 'Add more technical skills';
        } else {
            $missing[] = 'Add technical skills';
        }

        if ($projectsCount >= 1) {
            $score += 15;
        } else {
            $missing[] = 'Showcase at least 1 project';
        }

        if ($socialsCount >= 1) {
            $score += 15;
        } else {
            $missing[] = 'Add a social profile link';
        }

        $label = 'Needs Attention';
        if ($score >= 85) {
            $label = 'Ready';
        } elseif ($score >= 60) {
            $label = 'Good';
        }

        return [
            'score' => $score,
            'label' => $label,
            'missing' => $missing,
            'first_missing' => !empty($missing) ? $missing[0] : 'All key sections complete!',
        ];
    }

    function getCountryList()
    {
        $countries = Countries::all()
            ->map(function ($country) {
                $commonName = $country->name->common;
            
                $languages = $country->languages ?? collect();
            
                $language = $languages->keys()->first() ?? null;
            
                $nativeNames = $country->name->native ?? null;
            
                if (
                    filled($language) &&
                        filled($nativeNames) &&
                        filled($nativeNames[$language]) ?? null
                ) {
                    $native = $nativeNames[$language]['common'] ?? null;
                }
            
                if (blank($native ?? null) && filled($nativeNames)) {
                    $native = $nativeNames->first()['common'] ?? null;
                }
            
                $native = $native ?? $commonName;
            
                if ($native !== $commonName && filled($native)) {
                    $native = "$native ($commonName)";
                }
            
                return [$country->cca2 => $native];
            })
            ->values()->toArray();
            $countries = Helper::transformArray($countries);
        return Json::encode($countries);
    }

    function getVisitorByCountry()
    {
        $visitors = DB::table('visitors')
        ->selectRaw('count(*) as visitor, country, countryCode, MIN(latitude) as latitude, MIN(longitude) as longitude')
        ->whereNotNull('country')
        ->whereNotNull('countryCode')
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->groupBy('country', 'countryCode')
        ->get();

        $visitor = [];
        $country = [];
        $countryCode = [];
        $latitude = [];
        $longitude = [];

        foreach($visitors as $key => $value){
            $visitor[] = (int)$value->visitor;
            $country[] = $value->country;
            $countryCode[] = $value->countryCode;
            $latitude[] = $value->latitude;
            $longitude[] = $value->longitude;
        }
        
        $data = array(
            'data' => Json::encode($visitors),
            'info' => Json::encode(
                array(
                    'visitor' => $visitor,
                    'country' => $country,
                    'countryCode' => $countryCode,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                )
            ),
        );
        return Json::encode($data);
    }
}
