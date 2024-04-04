<?php

namespace Bedwars\game\shops\Upgrades;

use Bedwars\game\BedwarsTeam;
use Bedwars\game\teams\TeamUpgrade;

class Forge extends BaseUpgrade
{

    public function __construct()
    {
        parent::__construct("§eForge", 4, "textures/blocks/furnace");
    }

    public function getName(BedwarsTeam $team): string
    {
        return match ($team->getTeamUpgrade()->getForgeLevel()+1){
            1 => "§cIron Forge",
            2 => "§cGolden Forge",
            3 => "§cEmerald Forge",
            4 => "§cMolten Forge",
            5 => "§cSecond Forge",
            default => "§cForge"
        };
    }

    public function apply(BedwarsTeam $team)
    {
        $team->getTeamUpgrade()->upgradeforge();
    }

    public function max(TeamUpgrade $upgrade): bool
    {
        return $upgrade->getForgeLevel() >= 5;
    }
}