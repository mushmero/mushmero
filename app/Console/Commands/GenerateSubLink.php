<?php

namespace App\Console\Commands;

use App\Models\Leads;
use Illuminate\Console\Command;
use Str;

class GenerateSubLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sublink:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate subdomain link for new registration request';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $newRequests = Leads::where(['subdomain' => null, 'activate' => 0, 'activate_time' => null])->get();
        if($newRequests->count() > 0){
            foreach($newRequests as $singleRequest){
                $singleRequest->subdomain = Str::replace(' ', '-', $singleRequest->name);
                $singleRequest->save();
                $this->info('Request granted for '.$singleRequest->email);
            }
            return true;
        }else{
            $this->info('No new request');
            return false;
        }
    }
}
