<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Railken\Lem\Attributes;

class Generate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate {--force}';

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
        $helper = app('amethyst');

        $content = "";

        foreach ($helper->getData() as $data) {

            $name = $helper->getNameDataByModel(Arr::get($data, 'model'));

            if (!in_array($name, ['employee', 'taxonomy', 'office', 'company', 'attendance', 'legal_entity', 'address'])) {
                continue;
            }
            $manager = app(Arr::get($data, 'manager'));
            $faker = app(Arr::get($data, 'faker'));

            $attrs = [];

            $parent = $this->newNode('User', 'Tilk', "Crea una nuova entità di tipologia $name");
            $lastNode = $parent;

            foreach ($manager->getAttributes() as $attr) {

                if (!$attr->getFillable()) {
                    continue;
                }

                $attrs[] = $attr->getName();

                $faked = $faker::make()->parameters()->get($attr->getName());

                if (is_array ($faked)) {
                    $faked = json_encode($faked);
                }

                if ($faked === '') {
                    continue;
                }

                if ($attr->getRequired()) {

                } else {

                    $lastNode = $this->newNode('Tilk', 'User', "Vuoi inserire il campo {$attr->getName()}", [$lastNode]);
                    $lastNode = $this->newNode('User', 'Tilk', "Sì", [$lastNode]);
                }


                if ($attr instanceof Attributes\BelongsToAttribute || $attr instanceof Attributes\MorphToAttribute) {


                    $lastNode = $this->newNode('Tilk', 'User', "Vuoi utilizzare un record precedentemente creato per compilare il campo {$attr->getName()}?", [$lastNode]);

                    if ($attr instanceof Attributes\BelongsToAttribute) {

                        $class = $attr->getRelationManagerClass();


                        if (class_exists($class)) { 

                            $lastNodeNo = $this->newNode('Tilk', 'User', "No: Esegui [Creation:".(new $class)->getName()."]", [$lastNode]);
                            $lastNode = $this->newNode('Tilk', 'User', "Yes: Esegui [Query:".(new $class)->getName()."]", [$lastNode]);
                        }
                    }

                    if ($attr instanceof Attributes\MorphToAttribute && count($attr->getRelations()) > 0) {

                        $content .= "Note left of User: User può scegliere se Sì o No,\\nSì porta alla lettura di record già esistenti,\\nNo: porta alla sua creazione\n";

                        $class = array_values($attr->getRelations())[0];
                        if (class_exists($class)) { 
                            $lastNodeNo = $this->newNode('Tilk', 'User', "No: Esegui [Creation:".(new $class)->getName()."]", [$lastNode]);
                            $lastNode = $this->newNode('Tilk', 'User', "Yes: Esegui [Query:".(new $class)->getName()."]", [$lastNode]);
                        }
                    }

                    $lastNode = $this->newNode('Tilk', 'User', "Confermi la scelta del record X selezionato?", [$lastNodeNo, $lastNode]);
                    $lastNode = $this->newNode('User', 'Tilk', "Sì", [$lastNode]);
                } else {


                    if ($attr->getName() === 'enabled') {
                        $lastNode = $this->newNode('Tilk', 'User', "Vuoi attivare [campo: {$attr->getName()}] il record ?", [$lastNode]);
                        $lastNode = $this->newNode('User', 'Tilk', "Sì", [$lastNode]);
                    } else {
                        $lastNode = $this->newNode('Tilk', 'User', "Inserisci {$attr->getName()}", [$lastNode]);
                        $lastNode = $this->newNode('User', 'Tilk', "$faked", [$lastNode]);
                    }

                }

                if ($attr instanceof Attributes\EnumAttribute) {

                    $lastNode = $this->newNode('User', 'Tilk', "Quali sono le possibili opzioni?", [$lastNode]);
                    $lastNode = $this->newNode('Tilk', 'User', "[".implode(",",$attr->getOptions())."]", [$lastNode]);

                }

            }

            print_r(json_encode($this->toArray($parent)));
            file_put_contents("result.txt", json_encode($parent, JSON_PRETTY_PRINT));
            return;

            $content .=  "\n\nTitle: [Query:$name]\n";

            $content .= "Tilk-->User: Ecco gli ultimi 10 risultati (ult aggiornamento 10/10/2020)\n";

            $content .= "User->Tilk: Per quali campi posso cercare?\n";

            $content .= "Tilk-->User: Puoi cercare per ".implode(", ",$attrs)."\n";

            $content .= "User->Tilk: Prendi i risultati creati da gennaio a marzo\nNote right of Tilk: [Note: Stampare a debug la query per capire se funge]\n";

            $content .= "Tilk-->User: Ecco a te i risultati\n";

            $content .= "User->Tilk: Mostrameli per ordine decrescente del campo id\n";

            $content .= "Tilk-->User: Ecco a te i risultati\n";

            $content .= "User->Tilk: Quante pagine ci sono?\n";

            $content .= "Tilk-->User: Ci sono attualmente 100 risultati suddivisi in 10 pagine\n";

            $content .= "User->Tilk: Mostrami la pagina 2\n";

            $content .= "Tilk-->User: Ecco a te i risultati\n";

            $content .= "User->Tilk: Passami l'url per accedere alla pagina web\n";

            $content .= "Tilk-->User: Ecco a te l'url\n";

            $content .= "User->Tilk: Cerca per id a 1\n";

            $content .= "Tilk-->User: Ecco a te il risultato\n";
        }

        file_put_contents("result.txt", $content);
    }


    public function retrieveAttribute($attribute)
    {
        $params = [
            'name'       => $attribute->getName(),
            'type'       => $attribute->getType(),
            'fillable'   => (bool) $attribute->getFillable(),
            'required'   => (bool) $attribute->getRequired(),
            'unique'     => (bool) $attribute->getUnique(),
            'hidden'     => (bool) $attribute->getHidden(),
            'descriptor' => $attribute->getDescriptor(),
        ];

        if ($attribute instanceof Attributes\EnumAttribute) {
            $params = array_merge($params, [
                'options' => $attribute->getOptions(),
            ]);
        }

        if ($attribute instanceof Attributes\BelongsToAttribute || $attribute instanceof Attributes\MorphToAttribute) {
            $params = array_merge($params, [
                'relation' => $attribute->getRelationName(),
            ]);
        }

        return $params;
    }

    public function newNode($from, $to, $message, $parents = [])
    {
        $node = new \StdClass();
        $node->from = $from;
        $node->to = $to;
        $node->message = $message;
        $node->children = [];

        foreach ($parents as $parent) {
            $parent->children[] = $node;
            $node->parent = $parent;
        }

        return $node;
    }

    public function toArray($node)
    {
        return [
            'from' => $node->from,
            'to' => $node->to,
            'message' => $node->from."-->".$node->to.":".$node->message,
            'children' => array_map(function ($item) {
                return $this->toArray($item);
            }, $node->children)
        ];
    }
}
