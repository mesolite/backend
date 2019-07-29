<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Amethyst\Models\Taxonomy;

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
        $this->call('permission:cache-reset');
        $this->call('amethyst:user:install');
        $this->call('amethyst:data-builder:seed');
        $this->call('amethyst:exporter:seed');
        $this->call('amethyst:importer:seed');
        $this->call('db:seed', ['--class' => \Amethyst\Database\Seeds\TaxonomySeeder::class]);

        $parentAttendance = \Amethyst\Models\Taxonomy::firstOrCreate(['name' => 'Attendance Type']);

        $parent = \Amethyst\Models\Taxonomy::firstOrCreate(['name' => 'Festività', 'parent_id' => $parentAttendance->id]);
        Taxonomy::firstOrCreate(['code' => "FEGO", "name" => "FESTIVITA' GODUTA", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "FELA", "name" => "FESTIVITA' LAVORATA", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "FENA", "name" => "FESTIVITA' ACCANTONATE A ROL", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "FENG", "name" => "FESTIVITA' NON GODUTA", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "FEPG", "name" => "FESTIVITA' PATRONO GODUTA", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "FEPN", "name" => "FESTIVITA' PATRONO NON GODUTA", "parent_id" => $parent->id]);


        $parent = \Amethyst\Models\Taxonomy::firstOrCreate(['name' => 'Permesso', 'parent_id' => $parentAttendance->id]);
        Taxonomy::firstOrCreate(['code' => "PMCM", "name" => "PERMESSO PER CURE MEDICHE", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "PMEL", "name" => "PERMESSO ELEZIONI", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "PMES", "name" => "PERMESSO ESAMI", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "PMLO", "name" => "PERM. PER LUTTO GESTITO A ORE ANCHE PER MENSILI", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "PMLU", "name" => "PERMESSO PER LUTTO", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "PMMF", "name" => "PERMESSO MOTIVI FAMILIARI L.53/2000", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "PMNR", "name" => "PERMESSO NON RETRIBUITO", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "PMNS", "name" => "PERMESSO NON RETRIBUITO CON COP. PREVIDENZIALE", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "PMRE", "name" => "PERMESSO RETRIBUITO", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "PMSI", "name" => "PERMESSO SINDACALE", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "PMST", "name" => "PERMESSO DI STUDIO", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "PNAT", "name" => "PERMESSO ESAMI PRENATALI", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "PCIV", "name" => "PERMESSO PROTEZIONE CIVILE", "parent_id" => $parent->id]);

        $parent = \Amethyst\Models\Taxonomy::firstOrCreate(['name' => 'Presenza', 'parent_id' => $parentAttendance->id]);
        Taxonomy::firstOrCreate(['code' => "SD", "name" => "STR. DIURNO", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SD6", "name" => "STR. DIURNO 6^ GIORN", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SDD", "name" => "STR. DIURNO DOM.", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SDDF", "name" => "STR. DIU DOM.FEST.", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SDF", "name" => "STR. DIURNO FEST.", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SDFC", "name" => "STR. DIURNO FESTIVO ALTRI CASI", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SDR", "name" => "STR. DIURNO RIP.COM.", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SDRD", "name" => "STR. DIURNO RIP. C.DO", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SDRF", "name" => "STR. DIU. RIP.C.FEST", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SDRS", "name" => "STR. DIU RIP.C. SAB.", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SDS", "name" => "STR. DIURNO DI SAB.", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SDSF", "name" => "STR. DIU SAB FEST.", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SH", "name" => "STR. DIURNO ALTRI CASI", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SN", "name" => "STR. NOTT. NO TURNO", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SNC", "name" => "STR. NOTT. (NO TURNI) ALTRI CASI", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SND", "name" => "STR. NOTT.  DOMENICA", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SNDF", "name" => "STR. NOTT.DOM. FEST.", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SNF", "name" => "STR. NOTT. FEST.", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SNFC", "name" => "STR. NOTT. FEST. ALTRI CASI", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SNR", "name" => "STR. NOTT. RIP.COMP.", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SNRD", "name" => "STR. NOTT. RIP.C. DO", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SNRF", "name" => "STR. NOTT.RIP.C.FEST", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SNRS", "name" => "STR. NOTT.RIP.C. SAB", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SNS", "name" => "STR. NOTT. SAB.", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SNSF", "name" => "STR NOTT. SAB FEST.", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "SNT", "name" => "STR. NOTT. IN TURNO", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "OL", "name" => "ORE LAVORATE", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "TR", "name" => "TRASFERTA", "parent_id" => $parent->id]);


        $parent = \Amethyst\Models\Taxonomy::firstOrCreate(['name' => 'Cassa Integrazione', 'parent_id' => $parentAttendance->id]);
        Taxonomy::firstOrCreate(['code' => "CGDA", "name" => "CIG IN DEROGA  CON ANTICIPO", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "CGDD", "name" => "CIG IN DEROGA  AUTORIZZATA", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "CGDO", "name" => "CIG ORDINARIA/EDILI EVENTI ATM. AUTORIZZATA", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "CGDS", "name" => "CIG STRAORDINARIA AUTORIZZATA", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "CGSA", "name" => "CIG STRAORDINARIA CON ANTICIPO", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "CIEM", "name" => "CIG EDILI EVENTI ATMOSFERICI", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "CIES", "name" => "CIG EDILI SENZA ANTICIPO EVENTI ATMOSFERICI", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "CIGD", "name" => "CIG IN DEROGA SENZA ANTICIPO", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "CIGM", "name" => "CIG ORDINARIA CON ANTICIPO", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "CIGN", "name" => "CIG ORDINARIA SENZA ANTICIPO", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "CIGS", "name" => "CIG STRAORDINARIA SENZA ANTICIPO", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "CIGT", "name" => "CIGS  CON ANTICIPO (sostituito con CGSA)", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "CINI", "name" => "CIG ORDINARIA NON INTEGRATA", "parent_id" => $parent->id]);


        $parent = \Amethyst\Models\Taxonomy::firstOrCreate(['name' => 'Assenza', 'parent_id' => $parentAttendance->id]);
        Taxonomy::firstOrCreate(['code' => "ASS0", "name" => "ASSENZA INGIUSTIFICATA", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "ASS1", "name" => "ASS. NON RETRIBUITA NON SCALA ORE/GG RETRIBUITI", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "ASS2", "name" => "ASS. NON RETRIBUITA SCALA ORE/GG RETRIBUITI", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "ASS3", "name" => "ASSENZA CON VOCE 0009", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "ASS4", "name" => "ASS. NON RETRIBUITA SCALA ORE/GG RETRIBUITI", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "ASS5", "name" => "ASS. NON RETRIBUITA SCALA ORE/GG RETRIBUITI", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "ASS6", "name" => "ASS. NON GIUSTIFICATA EDILI SCALA ORE/GG RETRIB.", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "EDI1", "name" => "ASSENZA GIUSTIFICATA PROSPETTO EDILI VOCE 0839", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "EDIL", "name" => "ASSENZA GIUSTIFICATA PROSPETTO EDILI VOCE 0839", "parent_id" => $parent->id]);

        $parent = \Amethyst\Models\Taxonomy::firstOrCreate(['name' => 'Congedo', 'parent_id' => $parentAttendance->id]);
        Taxonomy::firstOrCreate(['code' => "CMT1", "name" => "CONGEDO MATRIMONIALE DITTA", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "CMT2", "name" => "CONGEDO MATRIMONIALE INPS", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "CPAF", "name" => "CONGEDO PATERNITA' FACOLTATIVA", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "CPAO", "name" => "CONGEDO PATERNITA' OBBLIGATORIO", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "DVO", "name" => "CONGEDO D.LGS 80/2015 ART. 24 (utilizzo a ore)", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "DVV", "name" => "CONGEDO D.LGS 80/2015 ART. 24 (utilizzo a giorni)", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "MC1", "name" => "CONGEDO STRAORDINARIO INPS", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "PAP", "name" => "CONGEDO PARTO PREMATURO", "parent_id" => $parent->id]);


        $parent = \Amethyst\Models\Taxonomy::firstOrCreate(['name' => 'Donazioni', 'parent_id' => $parentAttendance->id]);
        Taxonomy::firstOrCreate(['code' => "DMO", "name" => "DON. MIDOLLO OSSEO CON MAGG 1,20", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "DMO2", "name" => "DON. MIDOLLO OSSEO SENZA MAGG 1,20", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "DON", "name" => "DONAZIONE SANGUE CON MAGG 1,20", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "DON2", "name" => "DONAZIONE SANGUE SENZA MAGG 1,20", "parent_id" => $parent->id]);
        Taxonomy::firstOrCreate(['code' => "DON3", "name" => "DONAZIONE SANGUE PER MENSILIZZATI", "parent_id" => $parent->id]);


        (new \Amethyst\Managers\EmployeeManager())->createOrFail(\Amethyst\Fakers\EmployeeFaker::make()->parameters()->toArray());

        $this->call('passport:install', []);
        $this->call('amethyst:data-view:seed');
        $this->call('amethyst:permission:flush');
        \Amethyst\Models\ModelHasRole::create(['role_id' => 1,'model_type' => 'user', 'model_id' => 1]);

        $fgm = new \Amethyst\Managers\FileGeneratorManager();
        $dbm = new \Amethyst\Managers\DataBuilderManager();
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
