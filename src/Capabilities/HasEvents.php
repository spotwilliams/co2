<?php

namespace Co2Control\Capabilities;

trait HasEvents
{
    protected $events = [];

    public function release(): array
    {
        return $this->events;
    }

    public function dispatch($dispatcher)
    {
        while (count($this->events) > 0) {
            $event = array_shift($this->events);

            $dispatcher->dispatch($event);
        }
    }

    public function raise($event): void
    {
        $this->events[] = $event;
    }

    public function raiseMultiple(array $events): void
    {
        $this->events = array_merge($this->events, $events);
    }
}
