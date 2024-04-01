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

use Bedwars\game\BedwarsTeam;
use pocketmine\block\utils\DyeColor;
use pocketmine\math\Vector3;
use pocketmine\world\Position;

class GreenTeam extends BedwarsTeam
{

    public function __construct(int $max_players = 2)
    {
        parent::__construct("§2Green", "green", new Vector3(52, 89, 336), DyeColor::GREEN(), $max_players);
    }

}