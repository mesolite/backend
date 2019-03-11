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
        if (!$this->option('force') && !$this->confirm('Do you wish to continue?')) {
            return;
        }

        $this->call('passport:install');
        $this->call('amethyst:user:install');
        $this->call('db:seed', ['--class' => \Railken\Amethyst\Database\Seeds\TaxonomySeeder::class]);
    }
}
