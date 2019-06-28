<?php

namespace App\Listeners;

use Railken\Amethyst\Events\DataViewDataUpdated;
use Illuminate\Support\Facades\Config;
use Railken\Amethyst\Models\DataView;
use Symfony\Component\Yaml\Yaml;
use Railken\Amethyst\Models\ProductType;
use Railken\Amethyst\Models\Taxonomy;

class CustomDataView
{
    /**
     * Handle the event.
     *
     * @param \Railken\Amethysts\Events\DataViewDataUpdated $event
     */
    public function handle(DataViewDataUpdated $event)
    {
        $dataView = $event->dataView;

        $this->handleAttendance($dataView);
    }

    public function handleAttendance(DataView $dataView)
    {
        if ($dataView->name === 'attendance-resource') {

            $config = Yaml::parse($dataView->config);

            // perhaps this should be added as constraint in the relationship BelongsTo
            $taxonomy = \Railken\Amethyst\Models\Taxonomy::where('name', 'Attendance Type')->first();
            $config['options']['components']['type_id']['options']['query'] = 'parent.parent_id eq '.$taxonomy->id;
            $config['options']['components']['type_id']['options']['include'] = 'parent';

            $dataView->config = Yaml::dump($config);
            $dataView->save();
        }

        if ($dataView->name === 'employee-page-show') {

            $config = Yaml::parse($dataView->config);

            $config['options']['sections']['stats'] = [
                'extends' => 'attendance-counter'
            ];

            $dataView->config = Yaml::dump($config);
            $dataView->save();
        }
    }
}
