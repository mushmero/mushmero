<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;
use App\Models\SettingsSocial;

class SocialIconsRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socialicons:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update fontawesome icon list';

    protected $filename;

    function __construct()
    {
        $this->filename = 'fa.json';
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fileIcons = $this->readFile($this->filename);
        $this->createRecord($fileIcons);

        return Command::SUCCESS;
    }

    function readFile($filename)
    {
        $jsonData = '';
        $fontawesomeIcons = '';

        $filepath = public_path('storage/'.$filename);

        if(File::exists($filepath)){
            $jsonData = File::get($filepath);
            $fontawesomeIcons = json_decode($jsonData);
        }else{
            $this->info('File not found.');
            exit;
        }

        return $fontawesomeIcons;
    }

    function createRecord($icons)
    {
        foreach($icons as $icon){
            $iconExist = SettingsSocial::where('name', $icon->class)->first();

            if(is_null($iconExist)){
                SettingsSocial::create(['name' => $icon->class, 'type' => $icon->label]);
            }
        }

        $this->info('Fontawesome data added successfully.');
    }
}
