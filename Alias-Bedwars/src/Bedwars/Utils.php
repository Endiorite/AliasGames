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

namespace Bedwars;

use pocketmine\item\Item;
use pocketmine\player\Player;

class Utils
{
    public static function getItemCountInInventory(Player $player, Item $item): int{
        $count = 0;
        foreach ($player->getInventory()->getContents() as $item){
            if ($item->getTypeId() === $item->getTypeId()){
                $count += $item->getCount();
            }
        }

        return $count;
    }

    public static function playerHasCountItem(Player $player, Item $item, int $count): bool{
        $counts = self::getItemCountInInventory($player, $item);
        return $counts >= $count;
    }
}