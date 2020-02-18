<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Amethyst\Models\RelationSchema;
use Symfony\Component\Yaml\Yaml;

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

        $this->call('mapper:generate');
        $this->call('migrate:fresh');
        $this->call('cache:clear');
        $this->call('responsecache:clear');
        /*
        $this->call('amethyst:permission:flush');
        $this->call('amethyst:user:install');

        RelationSchema::firstOrCreate([
            'name'   => 'groups',
            'type'   => 'MorphToMany',
            'data' => 'user',
            'payload' => Yaml::dump([
                'target' => 'group',
                'key' => 'user-group'
            ])
        ]);

        RelationSchema::firstOrCreate([
            'name'   => 'users',
            'type'   => 'MorphToMany',
            'data' => 'group',
            'payload' => Yaml::dump([
                'target' => 'user',
                'key' => 'user-group'
            ])
        ]);

        $group = \Amethyst\Models\Group::create([
            'name' => 'can-access-admin'
        ]);

        \App\Models\User::where('id', 1)->first()->groups()->attach($group->id);

        // $this->call('amethyst:data-builder:seed');
        // $this->call('amethyst:exporter:seed');
        // $this->call('amethyst:importer:seed');
        $this->call('db:seed', ['--class' => \Amethyst\Database\Seeds\TaxonomySeeder::class]);

        $this->call('passport:install', []);
        $this->call('amethyst:data-view:seed');*/
    }
}
