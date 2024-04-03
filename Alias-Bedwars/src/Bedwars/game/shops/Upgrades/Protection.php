<?php

namespace Bedwars\game\shops\Upgrades;

use Alias\game\Team;
use Bedwars\game\BedwarsTeam;
use Bedwars\game\teams\TeamUpgrade;
use pocketmine\block\Bed;

class Protection extends BaseUpgrade
{

    public function __construct()
    {
        parent::__construct("§eProtection", 2, "textures/items/iron_chestplate");
    }

    public function getName(BedwarsTeam $team): string
    {
        $name = "";
        if (!$this->max($team->getTeamUpgrade())){
            $name = str_repeat("I", $team->getTeamUpgrade()->getProtectionArmor() + 1);
        }
        return parent::getName($team) . " " . $name;
    }

    public function getPrice(BedwarsTeam $team): int
    {
        return $this->price^$team->getTeamUpgrade()->getProtectionArmor();
    }

    public function apply(BedwarsTeam $team)
    {
        $team->getTeamUpgrade()->upgradeProtectionArmor();
    }

    public function max(TeamUpgrade $upgrade): bool
    {
        return $upgrade->getProtectionArmor() >= 4;
    }
}