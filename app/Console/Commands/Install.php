<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('All data will be erased, Do you wish to continue?')) {
            return;
        }

        $this->call('passport:install');
        $this->call('cache:clear');

        $this->call('responsecache:clear');
        $this->call('mapper:generate');
        $this->call('amethyst:data-view:seed');
        $this->call('amethyst:user:install');
        $this->call('db:seed');
        $this->call('amethyst:permission:flush');
    }
}
