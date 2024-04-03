<?php

namespace Bedwars\game\shops\Upgrades;

use Bedwars\game\BedwarsTeam;
use Bedwars\game\teams\TeamUpgrade;

class HealPool extends BaseUpgrade
{

    public function __construct()
    {
        parent::__construct("§eHeal Pool", 4, "textures/blocks/beacon");
    }

    public function apply(BedwarsTeam $team)
    {
        $team->getTeamUpgrade()->setHealPool(true);
    }

    public function max(TeamUpgrade $upgrade): bool
    {
        return $upgrade->hasHealPool();
    }
}