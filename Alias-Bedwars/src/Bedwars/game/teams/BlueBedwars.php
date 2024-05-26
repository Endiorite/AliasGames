<?php

/*
 *  ______           _ _            _ _
 * |  ____|         | (_)          (_) |
 * | |__   _ __   __| |_  ___  _ __ _| |_ ___
 * |  __| | '_ \ / _` | |/ _ \| '__| | __/ _ \
 * | |____| | | | (_| | | (_) | |  | | ||  __/
 * |______|_| |_|\__,_|_|\___/|_|  |_|\__\___|
 *
 * @author Endiorite Team
 * @link http://www.endiorite.fr/
 */

namespace Bedwars\game\teams;

use pocketmine\block\utils\DyeColor;
use pocketmine\math\Vector3;
use pocketmine\world\Position;

class BlueBedwars extends \Bedwars\game\BedwarsTeam
{

    public function __construct(int $max_players = 2)
    {
        parent::__construct("§9Blue", "blue", DyeColor::BLUE(), $max_players);
    }

}