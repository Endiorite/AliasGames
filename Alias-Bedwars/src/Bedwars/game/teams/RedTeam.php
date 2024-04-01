<?php

namespace Bedwars\game\teams;

use Bedwars\game\BedwarsTeam;
use pocketmine\block\utils\DyeColor;
use pocketmine\math\Vector3;
use pocketmine\world\Position;

class RedTeam extends BedwarsTeam
{

    public function __construct(int $max_players = 2)
    {
        parent::__construct(
            "§cRed",
            "red",
            new Vector3(139, 90, 248),
            DyeColor::RED(),
            $max_players);
    }

}