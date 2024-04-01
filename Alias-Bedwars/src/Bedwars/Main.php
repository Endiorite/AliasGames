<?php

namespace Bedwars;

use Alias\loaders\PermissionLoader;
use Alias\managers\GameManager;
use Bedwars\commands\BedwarsSpawnEntityCommand;
use Bedwars\game\BedwarsGame;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Main extends PluginBase
{

    protected function onLoad(): void
    {
        GameManager::getInstance()->registerGame("bedwars_2v2", new BedwarsGame());

        if(!InvMenuHandler::isRegistered()){
            InvMenuHandler::register($this);
        }

        $op = PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_OPERATOR);
        DefaultPermissions::registerPermission(new Permission("bedwars.admin"), [$op]);
        Server::getInstance()->getCommandMap()->register("bedwars", new BedwarsSpawnEntityCommand());
    }


}