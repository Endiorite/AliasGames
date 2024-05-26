<?php

namespace Bedwars\game\events;

use Bedwars\game\BedwarsGame;

class Event
{

    private string $name;
    private int $time;
    public function __construct(string $name, int $time)
    {
        $this->time = time() + $time;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function isTime(): bool{
        return $this->time - time() <= 0;
    }

    public function execute(BedwarsGame $game): void{

    }
}