<?php

namespace Bedwars;

use Alias\loaders\PermissionLoader;
use Alias\managers\GameManager;
use Bedwars\commands\BedwarsSpawnEntityCommand;
use Bedwars\entities\ItemShopEntity;
use Bedwars\entities\UpgraderEntity;
use Bedwars\game\BedwarsGame;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\world\World;

class Main extends PluginBase
{

    protected function onLoad(): void
    {
        GameManager::getInstance()->registerGame("bedwars_2v2", new BedwarsGame());

        $op = PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_OPERATOR);
        DefaultPermissions::registerPermission(new Permission("bedwars.admin"), [$op]);
        Server::getInstance()->getCommandMap()->register("bedwars", new BedwarsSpawnEntityCommand());

        EntityFactory::getInstance()->register(UpgraderEntity::class, function (World $world, CompoundTag $nbt): Entity{
            return new UpgraderEntity(EntityDataHelper::parseLocation($nbt, $world), null, $nbt);
        }, ["alias:bedwars_shop"]);
        EntityFactory::getInstance()->register(ItemShopEntity::class, function (World $world, CompoundTag $nbt): Entity{
            return new ItemShopEntity(EntityDataHelper::parseLocation($nbt, $world), null, $nbt);
        }, ["alias:bedwars_upgrade"]);
    }

    protected function onEnable(): void
    {
        if(!InvMenuHandler::isRegistered()){
            InvMenuHandler::register($this);
        }
    }

}