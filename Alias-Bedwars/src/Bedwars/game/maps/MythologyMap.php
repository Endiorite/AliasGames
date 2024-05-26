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
            "bedwars_mythology",
            new TeamSpawner(),
            [
                "red" => new Vector3(27, 90, 229),
                "green" => new Vector3(33, 89, 348),
                "blue" => new Vector3(246, 88, 327),
                "yellow" => new Vector3(149, 87, 455)
            ],
            [
                "blue" => new Vector3(227, 88, 339),
                "green" => new Vector3(52, 89, 336),
                "red" => new Vector3(139, 90, 248),
                "yellow" => new Vector3(137, 87, 436)
            ],
            [
                "red" => [
                    new TeamGenerator(VanillaItems::IRON_INGOT(), 30, 5, new Vector3(145, 81, 226)),
                    new TeamGenerator(VanillaItems::GOLD_INGOT(), 30, 3, new Vector3(145, 81, 226)),
                    new TeamGenerator(VanillaItems::EMERALD(), PHP_INT_MAX, 1, new Vector3(145, 81, 226)),
                    new TeamGenerator(VanillaItems::IRON_INGOT(), PHP_INT_MAX, 5, new Vector3(140, 90, 237)),
                    new TeamGenerator(VanillaItems::GOLD_INGOT(), PHP_INT_MAX, 3, new Vector3(140, 90, 237)),
                    new TeamGenerator(VanillaItems::EMERALD(), PHP_INT_MAX, 1, new Vector3(140, 90, 237)),
                ],
                "blue" => [
                    new TeamGenerator(VanillaItems::IRON_INGOT(), 30, 5, new Vector3(249, 79, 345)),
                    new TeamGenerator(VanillaItems::GOLD_INGOT(), 30, 3, new Vector3(249, 79, 345)),
                    new TeamGenerator(VanillaItems::EMERALD(), PHP_INT_MAX, 1, new Vector3(249, 79, 345)),
                    new TeamGenerator(VanillaItems::IRON_INGOT(), PHP_INT_MAX, 5, new Vector3(238, 88, 340)),
                    new TeamGenerator(VanillaItems::GOLD_INGOT(), PHP_INT_MAX, 3, new Vector3(238, 88, 340)),
                    new TeamGenerator(VanillaItems::EMERALD(), PHP_INT_MAX, 1, new Vector3(238, 88, 340)),
                ],
                "yellow" => [
                    new TeamGenerator(VanillaItems::IRON_INGOT(), 30, 5, new Vector3(131, 78, 458)),
                    new TeamGenerator(VanillaItems::GOLD_INGOT(), 30, 3, new Vector3(131, 78, 458)),
                    new TeamGenerator(VanillaItems::EMERALD(), PHP_INT_MAX, 1, new Vector3(131, 78, 458)),
                    new TeamGenerator(VanillaItems::IRON_INGOT(), PHP_INT_MAX, 5, new Vector3(136, 87, 447)),
                    new TeamGenerator(VanillaItems::GOLD_INGOT(), PHP_INT_MAX, 3, new Vector3(136, 87, 447)),
                    new TeamGenerator(VanillaItems::EMERALD(), PHP_INT_MAX, 1, new Vector3(136, 87, 447)),
                ],
                "green" => [
                    new TeamGenerator(VanillaItems::IRON_INGOT(), 30, 5, new Vector3(30, 80, 330)),
                    new TeamGenerator(VanillaItems::GOLD_INGOT(), 30, 3, new Vector3(30, 80, 330)),
                    new TeamGenerator(VanillaItems::EMERALD(), PHP_INT_MAX, 1, new Vector3(30, 80, 330)),
                    new TeamGenerator(VanillaItems::IRON_INGOT(), PHP_INT_MAX, 5, new Vector3(41, 89, 335)),
                    new TeamGenerator(VanillaItems::GOLD_INGOT(), PHP_INT_MAX, 3, new Vector3(41, 89, 335)),
                    new TeamGenerator(VanillaItems::EMERALD(), PHP_INT_MAX, 1, new Vector3(41, 89, 335)),
                ],
            ],
            [
                new DiamondGenerator(60, new Vector3(194, 87,283)),
                new DiamondGenerator(60, new Vector3(193, 87, 402)),
                new DiamondGenerator(60, new Vector3(86, 87, 402)),
                new DiamondGenerator(60, new Vector3(85, 88, 280)),
                new EmeraldGenerator(90, new Vector3(124, 89, 324)),
                new EmeraldGenerator(90, new Vector3(124, 89, 352)),
                new EmeraldGenerator(90, new Vector3(152, 89, 352)),
                new EmeraldGenerator(90, new Vector3(152, 89, 324))
            ]);
    }

}