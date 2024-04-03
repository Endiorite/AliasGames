<?php

namespace Bedwars\game\shops\Upgrades;

use Alias\game\Team;
use Bedwars\game\BedwarsTeam;
use Bedwars\game\teams\TeamUpgrade;

class EnemyAlarm extends BaseUpgrade
{
    public function __construct()
    {
        parent::__construct("§eEnemy Alarm", 3, "");
    }

    public function apply(BedwarsTeam $team)
    {
        $team->getTeamUpgrade()->setEnemyAlarm(true);
    }

    public function max(TeamUpgrade $upgrade): bool
    {
        return $upgrade->hasEnemyAlarm();
    }
}