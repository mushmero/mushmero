<?php

namespace App\Console\Commands;

use App\Models\AppsVersion;
use App\Helpers\Helper;
use Illuminate\Console\Command;

class GetAppVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appversion:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get current app version';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $version = Helper::appVer();
        dump('Current application version is '.$version);
        return Command::SUCCESS;
    }
}
