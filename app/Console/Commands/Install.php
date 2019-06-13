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

        $this->call('migrate:fresh');
        $this->call('cache:clear');
        $this->call('responsecache:clear');
        $this->call('permission:cache-reset');
        $this->call('amethyst:user:install');
        $this->call('amethyst:data-builder:seed');
        $this->call('amethyst:exporter:seed');
        $this->call('amethyst:importer:seed');
        $this->call('db:seed', ['--class' => \Railken\Amethyst\Database\Seeds\TaxonomySeeder::class]);
        $this->call('passport:install', []);
        $this->call('amethyst:data-view:seed');
        $this->call('amethyst:permission:flush');

        (new \Railken\Amethyst\Managers\EmployeeManager())->createOrFail(\Railken\Amethyst\Fakers\EmployeeFaker::make()->parameters()->toArray());

        \Railken\Amethyst\Models\ModelHasRole::create(['role_id' => 1,'model_type' => 'user', 'model_id' => 1]);


        $fgm = new \Railken\Amethyst\Managers\FileGeneratorManager();
        $dbm = new \Railken\Amethyst\Managers\DataBuilderManager();
        $dataBuilder = $dbm->getRepository()->findOneBy(['name' => 'office by id']);
        $fgm->createOrFail([
            'name'     => 'Authentication QR Code',
            'data_builder_id' => $dataBuilder->id,
            'body'     => file_get_contents(resource_path('seed/auth.twig')),
            'filename' => 'qr.pdf',
            'filetype' => 'application/pdf'
        ]);

    }
}
