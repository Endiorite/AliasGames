<?php

namespace Bedwars\game\events;

use Bedwars\constants\BedwarsMessages;
use Bedwars\game\BedwarsGame;
use Bedwars\game\generators\DiamondGenerator;
use Bedwars\game\generators\EmeraldGenerator;

class EmeraldUpgradeEvent extends Event
{
    public function __construct(string $name, int $time)
    {
        parent::__construct($name, $time);
    }

    public function execute(BedwarsGame $game): void
    {
        $tier = 1;
        foreach ($game->getGenerators() as $generator){
            if ($generator instanceof EmeraldGenerator){
                $generator->upgrade();
                $speed = $generator->getSpeed() - 15;
                $generator->setSpeed($speed <= 0 ? 15 : $speed);
                $tier = $generator->getTier();
            }
        }

        $game->broadcastMessage(str_replace("{tier}", str_repeat("I", $tier), BedwarsMessages::DIAMONDGENERATORUPGRADE));
    }
}