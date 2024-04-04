<?php

namespace Bedwars\game\events;

use Bedwars\constants\BedwarsMessages;
use Bedwars\game\BedwarsGame;

class BedDestroyEvent extends Event
{

    public function __construct(int $time)
    {
        parent::__construct("§cBed Destroy", $time);
    }


    public function execute(BedwarsGame $game): void
    {
        $game->broadcastMessage(BedwarsMessages::BREAK_ALL_BED);

        foreach ($game->getTeams() as $team){
            $team->bedBreak();
        }
    }

}