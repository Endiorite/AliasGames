<?php

namespace Bedwars\game\maps;

use Alias\game\Game;
use Alias\game\Map;
use Alias\game\spawners\GameSpawner;
use Alias\game\spawners\TeamSpawner;
use Alias\game\TeamableMap;
use Bedwars\game\BedwarsGame;
use Bedwars\game\BedwarsTeam;
use Bedwars\game\generators\TeamGenerator;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;

class MythologyMap extends BedwarsMap
{

    public function __construct()
    {
        parent::__construct(
            "bedwars_mythology",
            new TeamSpawner(),
            [
                "red" => new Vector3(27, 90, 229),
                "green" => new Vector3(33, 89, 348),
                "blue" => new Vector3(246, 88, 327),
                "yellow" => new Vector3(149, 87, 455)
            ],
            [
                "red" => [
                    "iron" => new TeamGenerator(VanillaItems::IRON_INGOT(), 5, new Vector3(0, 0, 0), )
                ]
            ],
            []);
    }

}