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

        app('amethyst')->get('attribute')->createOrFail([
            'model'  => 'cat',
            'name'   => 'name',
            'schema' => 'Text',
            'required' => true
        ])->getResource();

        app('amethyst')->get('attribute')->createOrFail([
            'model'  => 'cat',
            'name'   => 'description',
            'schema' => 'Text',
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
    }
}
