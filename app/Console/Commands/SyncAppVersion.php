<?php

namespace App\Console\Commands;

use App\Models\AppsVersion;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Str;

class SyncAppVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appversion:sync {--major=} {--minor=} {--build=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update app version to latest';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $major = $this->option('major') ? $this->option('major') : $this->ask('Enter major version');
        $minor = $this->option('minor') ? $this->option('minor') : $this->ask('Enter minor version');
        $build = $this->option('build') ? $this->option('build') : $this->ask('Enter build version');

        $exist = AppsVersion::first();

        if($exist){
            $data['major'] = !empty($major) ? $major : $exist->major;
            $data['minor'] = !empty($minor) ? $minor : $exist->minor;
            $data['build'] = !empty($build) ? $build : $exist->build;
            $data['date'] = Carbon::now('Asia/Kuala_Lumpur')->format('Ymd');
            $data['uuid'] = Str::uuid()->toString();

            $exist->update($data);
            dump('success - update app version to '.$data['major'].'.'.$data['minor'].'.'.$data['build']);

        }else{
            $data['major'] = !empty($major) ? $major : 1;
            $data['minor'] = !empty($minor) ? $minor : 0;
            $data['build'] = !empty($build) ? $build : 0;
            $data['date'] = Carbon::now('Asia/Kuala_Lumpur')->format('Ymd');
            $data['uuid'] = Str::uuid()->toString();

            AppsVersion::create($data);
            dump('success - create app version to '.$data['major'].'.'.$data['minor'].'.'.$data['build']);
        }

        return self::SUCCESS;
    }
}
