<?php

use Illuminate\Database\Seeder;

use Symfony\Component\Yaml\Yaml;

class CatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('amethyst')->get('data-schema')->createOrFail([
            'name' => 'cat',
        ]);

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'cat',
            'name'   => 'name',
            'schema' => 'Text',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'cat',
            'name'   => 'description',
            'schema' => 'Text',
        ])->getResource();

        app('amethyst')->get('attribute-schema')->createOrFail([
            'model'  => 'cat',
            'name'   => 'owner_id',
            'schema' => 'BelongsTo',
            'options' => Yaml::dump([
                'relationData' => 'user',
                'relationName' => 'owner'
            ])
        ])->getResource();

        app('amethyst')->get('relation-schema')->createOrFail([
            'name'   => 'friends',
            'type'   => 'MorphToMany',
            'data' => 'cat',
            'payload' => Yaml::dump([
                'target' => 'cat'
            ])
        ]);

        for ($i = 0; $i < 10; $i++) {
            $cat = app('amethyst')->get('cat')->createOrFail([
                'name' => 'cat-'.str_random(4),
                'description' => 'cat '.str_random(4)
            ])->getResource();

            if (isset($prevCat)) {
                $cat->friends()->withTimestamps()->attach($prevCat);
            }

            $prevCat = $cat;
        }


        $workflow = app('amethyst')->get('workflow')->createOrFail([
            'name' => 'meow',
        ])->getResource();


        $node = $workflow->next('data', [
            'action'     => 'first',
            'name'       => 'cat',
            'query'      => 'id eq {{ id }}'
        ], [
            'cat' => 'resource',
            '__agent' => '__agent'
        ]);

        $node1 = $workflow->new('data', [
            'action'     => 'update',
            'name'       => 'cat',
            'query'      => 'id eq {{ cat.id }}',
            'parameters' => [
                'description' => "Ok, just feed me {{ random(2, 10) }} birds already. ",
            ]
        ]);

        $node2 = $workflow->new('data', [
            'action'     => 'update',
            'name'       => 'cat',
            'query'      => 'id eq {{ cat.id }}',
            'parameters' => [
                'description' => "As a cat, i won't obey any of your command! You're not my owner! You are {{ __agent.name }}!",
            ]
        ]);

        $switch = $node->next('switcher', [
            'channels' => [
                $node1->id => '"{{ __agent.id }}" === "{{ cat.owner_id }}"',
                $node2->id => '"{{ __agent.id }}" !== "{{ cat.owner_id }}"'
            ]
        ], [
            'cat' => 'cat',
            '__agent' => '__agent'
        ]);

        $switch->relations()->attach($node1);
        $switch->relations()->attach($node2);
        /*

        $node = $workflow->switch([
            [
                'condition' => '{{ agent.id }} === {{ owner.id }}'
                'data', 
                [
                    'action'     => 'update',
                    'name'       => 'cat',
                    'query'      => 'id eq {{ id }}',
                    'parameters' => [
                        'description' => "As a cat, i won't obey any of your command! You're not my owner!",
                    ],
                ]   
        );*/

        // Dispatch workflow for the first catm
        $user = app('amethyst')->get('user')->getRepository()->findOneById(1);

        $cat = app('amethyst')->get('cat')->getRepository()->findOneById(1);
        $cat->owner()->associate($user);
        $cat->save();

        app('amethyst.action')->dispatchByWorkflow($workflow, ['__agent' => $user, 'id' => 1]);
        app('amethyst.action')->dispatchByWorkflow($workflow, ['__agent' => $user, 'id' => 2]);

        $api = config('amethyst.api.http.data.router.prefix');

        app('amethyst')->get('data-view')->findOrCreateOrFail([
            'name'    => '~cat~.feed',
            'type'    => 'component',
            'tag'     => 'cat',
            'require' => 'cat',
            'config'  => Yaml::dump([
                'label'   => 'feed',
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
            'parent_id' => app('amethyst')->get('data-view')->getRepository()->findOneBy(['name' => '~cat~.data.iterator.table'])
        ])->getResource();
    }
}
