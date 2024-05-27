<?php

namespace Bedwars\game;

use Bedwars\constants\BedwarsMessages;
use pocketmine\event\block\BlockBreakEvent;

class BedwarsBehavior extends \Alias\game\behaviors\Behavior
{

    public function getName(): string
    {
        return "BedwarsBehavior";
    }

    public function onUpdate(): void{}

    public function hasUpdate(): bool
    {
        return false;
    }

    public function onBlockBreak(BlockBreakEvent $event): void
    {
        /** @var BedwarsGame $game */
        $game = $this->getGame();
        $block = $event->getBlock();
        $position = $block->getPosition();
        $player = $event->getPlayer();

        /** @var BedwarsTeam $playerTeam */
        $playerTeam = $game->getPlayerTeam($player);
        if (is_null($playerTeam)) return;

        foreach ($game->getTeams() as $team){
            if ($team->getBedPosition()->equals($position->asVector3())){
                if ($team->inTeam($player->getName())){
                    $event->cancel();
                    $player->sendMessage(BedwarsMessages::CANT_BREAK_YOUR_BED);
                }else{
                    $game->broadcastMessage(BedwarsMessages::BED_BREAK, ["{player}", "{team}"], [$player->getName(), $team->getName()]);
                    $team->bedBreak();
                    $playerTeam->addTeamDestroy();
                }
            }
        }
    }
}