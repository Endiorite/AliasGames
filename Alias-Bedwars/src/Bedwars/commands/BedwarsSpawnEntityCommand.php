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

namespace Bedwars\commands;

use Bedwars\entities\ItemShopEntity;
use Bedwars\entities\UpgraderEntity;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;

class BedwarsSpawnEntityCommand extends \pocketmine\command\Command
{

    public function __construct()
    {
        $this->setPermission("bedwars.admin");
        parent::__construct("bedwarssetentity", "set bedwars entity to make new map !", "", []);
    }

    /**
     * @inheritDoc
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player) return;
        if (!$sender->hasPermission("bedwars.admin")) return;

        $entity = $args[0] ?? "";

        switch ($entity){
            case "shop":
                $entity = new ItemShopEntity($sender->getLocation(), null);
                $entity->spawnToAll();
                break;
            case "upgrade":
                $entity = new UpgraderEntity($sender->getLocation(), null);
                $entity->spawnToAll();
                break;
        }
    }
}