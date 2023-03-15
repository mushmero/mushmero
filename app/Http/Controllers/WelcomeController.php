<?php

namespace App\Http\Controllers;

use App\Http\Traits\PersonaTrait;
use App\Models\Leads;
use App\Models\Personal;
use App\Models\Visitors;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Validator;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->saveVisitor();

        $data['author'] = 'Amirul Hakim';
        $data['description'] = 'Resumed is a powerful and user-friendly tool designed to help job seekers create professional resumes online quickly and easily';

        if(PersonaTrait::checkPersona()->count() > 0){  
            $activePersona = $this->getInformation();
    
            $data['profile-image'] = $activePersona->attachments->path.'/'.$activePersona->attachments->filename;
            $data['name'] = $activePersona->fullname;
            $data['position'] = $activePersona->tagline;
            $data['experiences'] = $activePersona->experiences;    
            $data['skills'] = $activePersona->skills->shuffle();
            $data['email'] = $activePersona->email;
            $data['phone'] = $activePersona->phone;
            $data['website'] = $activePersona->website;
            $data['educations'] = $activePersona->educations;
            $data['languages'] = $activePersona->languages;

            if($activePersona->projects->count() > 0){
                $projects = $activePersona->projects->shuffle()->toArray();
                $interests = $activePersona->interests->shuffle()->toArray();
    
                foreach($projects as $project){
                    $interests_projects[] = array(
                        'name' => $project['name'],
                        'description' => $project['description'],
                    );
                }
    
                foreach($interests as $interest){
                    $interests_projects[] = array(
                        'name' => $interest['name'],
                    );
                }
                $data['projects'] = $interests_projects; 
            }else{
                $data['projects'] = array();
            }   
            $data['socials'] = $activePersona->socials->shuffle();      
            return view('welcome.content.content', compact('data'));
        }else{
            $data['info'] = '
            Resumed is a powerful and user-friendly tool designed to help job seekers create professional resumes online quickly and easily. The platform features an intuitive interface that allow users through each step of the resume creation process, from adding personal information to work experience, skills, education and many more. Resumed features an intuitive and user-friendly interface that makes it easy for anyone to create a professional-looking resume quickly. The platform allows users to customize their resumes to suit their unique career goals and experience.';
            return view('welcome', compact('data'));
        }
    }

    public function saveVisitor(){
        $data = Helper::visitor();
        $store = Visitors::create([
            'userip' => $data['ip'],
            'referrer' => $data['referrer'],
            'useragent' => $data['useragent'],
            'datetime' => $data['timestamp'],
            'city' => $data['city'],
            'country' => $data['country'],
            'countryCode' => $data['countryCode'],
            'continent' => $data['continent'],
            'continentCode' => $data['continentCode'],
            'timezone' => $data['timezone'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ]);
        if($store){
            return true;
        }else{
            return false;
        }
    }

    public function getInformation()
    {
        $persona = PersonaTrait::getActivePersonaInformations();
        return $persona;
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'email' => 'required|unique:leads',
        ]);

        if($validate->fails()){
            return response()->json(['validate_error' => $validate->errors()]);
        }

        $store = Leads::create([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if($store){
            return response()->json(['success' => 'Thank you for your interest']);
        }else{
            return response()->json(['error' => 'Unable to process. Please try later']);
        }
    }
}
