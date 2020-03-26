<?php

use Illuminate\Database\Seeder;

use Symfony\Component\Yaml\Yaml;

class LocalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedTransaction();
        $this->seedAssociate();
        $this->seedActivity();
        $this->seedMessage();
        $this->seedRegistrationPage();
    }

    public function seedTransaction()
    {
        app('amethyst')->get('data-schema')->createOrFail([
            'name' => 'transaction'
        ]);
        
        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'transaction',
            'name'   => 'gateway_name',
            'schema' => 'Enum',
            'options' => Yaml::dump([
                'options' => [
                    'paypal',
                    'manual', 
                ]
            ])
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'transaction',
            'name'   => 'amount',
            'schema' => 'Number',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'transaction',
            'name'   => 'gateway_code',
            'schema' => 'Text'
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'transaction',
            'name'   => 'status',
            'schema' => 'Enum',
            'options' => Yaml::dump([
                'default' => 'unpaid',
                'options' => [
                    'unpaid', 
                    'paid', 
                ]
            ])
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'transaction',
            'name'   => 'paid_at',
            'schema' => 'DateTime'
        ])->getResource();

        $workflow = app('amethyst')->get('workflow')->createOrFail([
            'name' => 'transaction-paid-manual',
        ])->getResource();

        $node = $workflow->next('data', [
            'action'     => 'update',
            'name'       => 'transaction',
            'query'      => 'id eq {{ id }}',
            'parameters' => [
                'gateway_name' => "manual",
                'gateway_code' => "manual",
                'status' => "paid",
                'paid_at' => '{{ "now"|date("Y-m-d H:i:s") }}',
            ]
        ]);

        $api = config('amethyst.api.http.data.router.prefix');

        app('amethyst')->get('data-view')->findOrCreateOrFail([
            'name'    => '~transaction~.feed',
            'type'    => 'component',
            'tag'     => 'transaction',
            'require' => 'transaction',
            'config'  => Yaml::dump([
                'label'   => 'Set as Paid',
                'extends' => "resource-execute",
                'type'    => 'action',
                'scope'   => 'resource',
                'options' => [
                    'http' => [
                        'method' => 'POST',
                        'url' => $api."/workflow/execute",
                        'query' => "id eq {$workflow->id}",
                        'body' => [
                            'id' => "{{ resource.id }}"
                        ]
                    ]
                ]
            ]),
            'parent_id' => app('amethyst')->get('data-view')->getRepository()->findOneBy(['name' => '~transaction~.data.iterator.table'])
        ])->getResource();

        $workflow = app('amethyst')->get('workflow')->createOrFail([
            'name' => 'transaction-exporter',
        ])->getResource();

        $node = $workflow->next('exporter', [
            'type'       => 'xlsx',
            'data'       => 'transaction',
            'filter'     => '',
            'filename'   => 'transaction.xlsx',
        ], [
            'file' => 'file',
            '__agent' => '__agent'
        ], [
            'body' => [
                'id' => '{{ resource.id }}',
                'gateway_name' => '{{ resource.gateway_name }}',
                'gateway_code' => '{{ resource.gateway_code }}',
                'amount' => '{{ resource.amount }}',
                'status' => '{{ resource.status }}',
                'paid_at' => '{{ resource.paid_at }}',
                'created_at' => '{{ resource.created_at }}',
                'updated_at' => '{{ resource.updated_at }}',
            ]
        ]);

        $node = $node->next('notification', [
            'agent' => [
                'data' => 'user',
                'filter' => 'id = {{ __agent.id }}'
            ],
            'message'    => 'The file is ready! Click here to download it',
            'vars'      => [
                'url' => "{{ file.media[0].getFullUrl() }}"
            ]
        ]);

        $api = config('amethyst.api.http.data.router.prefix');
        
        app('amethyst')->get('data-view')->findOrCreateOrFail([
            'name'    => '~transaction~.export',
            'type'    => 'component',
            'tag'     => 'transaction',
            'require' => 'transaction',
            'config'  => Yaml::dump([
                'label'   => 'export',
                'extends' => "resource-execute",
                'type'    => 'action',
                'scope'   => 'global',
                'options' => [
                    'http' => [
                        'method' => 'POST',
                        'url' => $api."/workflow/execute",
                        'query' => "id eq {$workflow->id}",
                        'body' => [
                            'queue' => 1
                        ]
                    ]
                ]
            ]),
            'parent_id' => app('amethyst')->get('data-view')->getRepository()->findOneBy(['name' => '~transaction~.data.iterator.table'])
        ])->getResource();
    }

    public function seedAssociate()
    {
        app('amethyst')->get('data-schema')->createOrFail([
            'name' => 'associate'
        ]);

        app('amethyst')->get('relation-schema')->createOrFail([
            'name'   => 'transactions',
            'type'   => 'MorphToMany',
            'data' => 'associate',
            'payload' => Yaml::dump([
                'target' => 'transaction',
                'key' => 'transactions',
                'inversedBy' => 'associates'
            ])
        ]);

        app('amethyst')->get('relation-schema')->createOrFail([
            'name'   => 'associates',
            'type'   => 'MorphToMany',
            'data' => 'transaction',
            'payload' => Yaml::dump([
                'target' => 'associate',
                'key' => 'transactions',
                'inversedBy' => 'transactions'
            ])
        ]);

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'tessera',
            'schema' => 'Text',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'tipo',
            'schema' => 'Enum',
            'options' => Yaml::dump([
                'options' => [
                    'ordinario',
                    'onorario'
                ]
            ])
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'cognome',
            'schema' => 'Text',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'nome',
            'schema' => 'Text',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'luogo_di_nascita',
            'schema' => 'Text',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'data_di_nascita',
            'schema' => 'Date',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'stato_di_nasciate',
            'schema' => 'Text',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'codice_fiscale',
            'schema' => 'Text',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'residenza_indirizzo',
            'schema' => 'Text',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'residenza_cap',
            'schema' => 'Text',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'residenza_provincia',
            'schema' => 'Text',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'residenza_stato',
            'schema' => 'Text',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'cellulare',
            'schema' => 'Text',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'telefono',
            'schema' => 'Text',
            'required' => false
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'email',
            'schema' => 'Email',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'giornalino_cartaceo',
            'schema' => 'Boolean'
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'newsletter_cartacea',
            'schema' => 'Boolean'
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'newsletter_email',
            'schema' => 'Boolean'
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'associate',
            'name'   => 'iscritto_il',
            'schema' => 'DateTime'
        ])->getResource();


        $workflow = app('amethyst')->get('workflow')->createOrFail([
            'name' => 'associate-exporter',
        ])->getResource();

        $node = $workflow->next('exporter', [
            'type'       => 'xlsx',
            'data'       => 'associate',
            'filter'     => '',
            'filename'   => 'associate.xlsx',
        ], [
            'file' => 'file',
            '__agent' => '__agent'
        ], [
            'body' => [
                'id' => '{{ resource.id }}',
                'created_at' => '{{ resource.created_at }}',
                'updated_at' => '{{ resource.updated_at }}',
                'tessera' => '{{ resource.tessera }}',
                'tipo' => '{{ resource.tipo }}',
                'cognome' => '{{ resource.cognome }}',
                'nome' => '{{ resource.nome }}',
                'luogo_di_nascita' => '{{ resource.luogo_di_nascita }}',
                'data_di_nascita' => '{{ resource.data_di_nascita }}',
                'stato_di_nasciate' => '{{ resource.stato_di_nasciate }}',
                'codice_fiscale' => '{{ resource.codice_fiscale }}',
                'residenza_indirizzo' => '{{ resource.residenza_indirizzo }}',
                'residenza_cap' => '{{ resource.residenza_cap }}',
                'residenza_provincia' => '{{ resource.residenza_provincia }}',
                'residenza_stato' => '{{ resource.residenza_stato }}',
                'cellulare' => '{{ resource.cellulare }}',
                'telefono' => '{{ resource.telefono }}',
                'email_secondaria' => '{{ resource.email }}',
                'giornalino_cartaceo' => '{{ resource.giornalino_cartaceo }}',
                'newsletter_cartacea' => '{{ resource.newsletter_cartacea }}',
                'newsletter_email' => '{{ resource.newsletter_email }}',
                'iscritto_il' => '{{ resource.iscritto_il }}',
                'email' => '{{ resource.user.email }}',
            ]
        ]);

        $node = $node->next('notification', [
            'agent' => [
                'data' => 'user',
                'filter' => 'id = {{ __agent.id }}'
            ],
            'message'    => 'The file is ready! Click here to download it',
            'vars'      => [
                'url' => "{{ file.media[0].getFullUrl() }}"
            ]
        ]);

        $api = config('amethyst.api.http.data.router.prefix');
        
        app('amethyst')->get('data-view')->findOrCreateOrFail([
            'name'    => '~associate~.export',
            'type'    => 'component',
            'tag'     => 'associate',
            'require' => 'associate',
            'config'  => Yaml::dump([
                'label'   => 'export',
                'extends' => "resource-execute",
                'type'    => 'action',
                'scope'   => 'global',
                'options' => [
                    'http' => [
                        'method' => 'POST',
                        'url' => $api."/workflow/execute",
                        'query' => "id eq {$workflow->id}",
                        'body' => [
                            'queue' => 1
                        ]
                    ]
                ]
            ]),
            'parent_id' => app('amethyst')->get('data-view')->getRepository()->findOneBy(['name' => '~associate~.data.iterator.table'])
        ])->getResource();
    }

    public function seedActivity()
    {
        app('amethyst')->get('data-schema')->createOrFail([
            'name' => 'activity'
        ]);
        
        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'activity',
            'name'   => 'name',
            'schema' => 'Text',
            'required' => true
        ])->getResource();
        
        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'activity',
            'name'   => 'description',
            'schema' => 'LongText',
            'required' => false
        ])->getResource();
        
        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'activity',
            'name'   => 'start_at',
            'schema' => 'DateTime',
            'required' => true
        ])->getResource();
        
        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'activity',
            'name'   => 'end_at',
            'schema' => 'DateTime',
            'required' => true
        ])->getResource();
        
        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'activity',
            'name'   => 'price',
            'schema' => 'Number',
            'required' => true
        ])->getResource();
    }

    public function seedMessage()
    {
        app('amethyst')->get('data-schema')->createOrFail([
            'name' => 'message'
        ]);
        
        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'message',
            'name'   => 'body',
            'schema' => 'Text',
            'required' => true
        ])->getResource();

        app('amethyst')->get('relation-schema')->createOrFail([
            'name'   => 'messages',
            'type'   => 'MorphToMany',
            'data' => 'activity',
            'payload' => Yaml::dump([
                'target' => 'message',
                'key' => 'messages',
                'inversedBy' => 'activities'
            ])
        ]);

        app('amethyst')->get('relation-schema')->createOrFail([
            'name'   => 'activities',
            'type'   => 'MorphToMany',
            'data' => 'message',
            'payload' => Yaml::dump([
                'target' => 'activity',
                'key' => 'messages',
                'inversedBy' => 'messages'
            ])
        ]);
    }

    public function seedRegistrationPage()
    {
        $configuration = [
            [
                'name'      => 'registration',
                'component' => 'page.form',
                'path'      => '/registration'
            ],
        ];

        $view = app('amethyst')->get('data-view')->findOrCreateOrFail([
            'name'    => 'registration.routes',
            'type'    => 'routes',
            'tag'     => 'associate',
            'require' => 'associate',
        ])->getResource();

        app('amethyst')->get('data-view')->updateOrFail($view, ['config' => Yaml::dump($configuration)]);  
    }
}
