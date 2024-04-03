<?php

namespace Bedwars\game\shops\Upgrades;

use Bedwars\game\BedwarsTeam;
use Bedwars\game\teams\TeamUpgrade;

class Hast extends BaseUpgrade
{
    public function __construct()
    {
        parent::__construct("§eSharpness", 4, "textures/items/gold_pickaxe");
    }

    public function getName(BedwarsTeam $team): string
    {
        $name = "";
        if (!$this->max($team->getTeamUpgrade())){
            $name = str_repeat("I", $team->getTeamUpgrade()->getHast() + 1);
        }
        return parent::getName($team) . " " . $name;
    }

    public function apply(BedwarsTeam $team)
    {
        $team->getTeamUpgrade()->upgradeHast();
    }

    public function max(TeamUpgrade $upgrade): bool
    {
        return $upgrade->getSharpness() >= 2;
    }
}