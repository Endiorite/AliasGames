<?php

namespace Bedwars\game\maps;

use Alias\game\Game;
use Alias\game\spawners\GameSpawner;
use Alias\game\spawners\TeamSpawner;
use Alias\game\Team;
use Bedwars\game\BedwarsGame;
use Bedwars\game\BedwarsTeam;
use Bedwars\game\generators\DiamondGenerator;
use Bedwars\game\generators\EmeraldGenerator;
use Bedwars\game\generators\TeamGenerator;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;

class MythologyMap extends BedwarsMap
{

    public function __construct()
    {
        parent::__construct(
            "bedwars1",
            new TeamSpawner(),
            [
                "red" => new Vector3(128, 90, 230),
                "green" => new Vector3(34, 89, 347),
                "blue" => new Vector3(246, 88, 327),
                "yellow" => new Vector3(149, 87, 455)
            ],
            [
                "blue" => new Vector3(226, 88, 339),
                "green" => new Vector3(53, 89, 336),
                "red" => new Vector3(139, 90, 249),
                "yellow" => new Vector3(137, 87, 435)
            ],
            [
                "red" => [
                    new TeamGenerator(VanillaItems::IRON_INGOT(), 15, 5, new Vector3(140.5, 91, 237.5)),
                    new TeamGenerator(VanillaItems::GOLD_INGOT(), 15, 3, new Vector3(140.5, 91, 237.5)),
                    new TeamGenerator(VanillaItems::EMERALD(), PHP_INT_MAX, 1, new Vector3(140.5, 91, 237.5)),
                    new TeamGenerator(VanillaItems::IRON_INGOT(), PHP_INT_MAX, 5, new Vector3(145.5, 82, 226.5)),
                    new TeamGenerator(VanillaItems::GOLD_INGOT(), PHP_INT_MAX, 3, new Vector3(145.5, 82, 226.5)),
                    new TeamGenerator(VanillaItems::EMERALD(), PHP_INT_MAX, 1, new Vector3(145.5, 82, 226.5)),
                ],
                "blue" => [
                    new TeamGenerator(VanillaItems::IRON_INGOT(), 15, 5, new Vector3(238.5, 89, 340.5)),
                    new TeamGenerator(VanillaItems::GOLD_INGOT(), 15, 3, new Vector3(238.5, 89, 340.5)),
                    new TeamGenerator(VanillaItems::EMERALD(), PHP_INT_MAX, 1, new Vector3(238.5, 89, 340.5)),
                    new TeamGenerator(VanillaItems::IRON_INGOT(), PHP_INT_MAX, 5, new Vector3(249.5, 80, 345.5)),
                    new TeamGenerator(VanillaItems::GOLD_INGOT(), PHP_INT_MAX, 3, new Vector3(249.5, 80, 345.5)),
                    new TeamGenerator(VanillaItems::EMERALD(), PHP_INT_MAX, 1, new Vector3(249.5, 80, 345.5)),
                ],
                "yellow" => [
                    new TeamGenerator(VanillaItems::IRON_INGOT(), 15, 5, new Vector3(136.5, 88, 447.5)),
                    new TeamGenerator(VanillaItems::GOLD_INGOT(), 15, 3, new Vector3(136.5, 88, 447.5)),
                    new TeamGenerator(VanillaItems::EMERALD(), PHP_INT_MAX, 1, new Vector3(136.5, 88, 447.5)),
                    new TeamGenerator(VanillaItems::IRON_INGOT(), PHP_INT_MAX, 5, new Vector3(131.5, 79, 458.5)),
                    new TeamGenerator(VanillaItems::GOLD_INGOT(), PHP_INT_MAX, 3, new Vector3(131.5, 79, 458.5)),
                    new TeamGenerator(VanillaItems::EMERALD(), PHP_INT_MAX, 1, new Vector3(131.5, 79, 458.5)),
                ],
                "green" => [
                    new TeamGenerator(VanillaItems::IRON_INGOT(), 15, 5, new Vector3(41.5, 90, 335.5)),
                    new TeamGenerator(VanillaItems::GOLD_INGOT(), 15, 3, new Vector3(41.5, 90, 335.5)),
                    new TeamGenerator(VanillaItems::EMERALD(), PHP_INT_MAX, 1, new Vector3(41.5, 90, 335.5)),
                    new TeamGenerator(VanillaItems::IRON_INGOT(), PHP_INT_MAX, 5, new Vector3(30.5, 81, 330.5)),
                    new TeamGenerator(VanillaItems::GOLD_INGOT(), PHP_INT_MAX, 3, new Vector3(30.5, 81, 330.5)),
                    new TeamGenerator(VanillaItems::EMERALD(), PHP_INT_MAX, 1, new Vector3(30.5, 81, 330.5)),
                ],
            ],
            [
                new DiamondGenerator(45, new Vector3(194.5, 88,283.5)),
                new DiamondGenerator(45, new Vector3(193.5, 88, 402.5)),
                new DiamondGenerator(45, new Vector3(86.5, 88, 402.5)),
                new DiamondGenerator(45, new Vector3(85.5, 88, 280.5)),
                new EmeraldGenerator(70, new Vector3(124.5, 90, 324.5)),
                new EmeraldGenerator(70, new Vector3(124.5, 90, 352.5)),
                new EmeraldGenerator(70, new Vector3(152.5, 90, 352.5)),
                new EmeraldGenerator(70, new Vector3(152.5, 90, 324.5))
            ]);
    }

}