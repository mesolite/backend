<?php

namespace App\Jobs;

class TriggerEvent extends Job
{
	protected $event;

    public function __construct($event)
    {
    	$this->event = $event;
    }

    public function handle()
    {
    	event($this->event);
    }
    
}