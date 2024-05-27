<?php

namespace Bedwars\game;

use Alias\game\Game;
use Alias\game\TeamableGame;
use Bedwars\constants\BedwarsMessages;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

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

    public function onSpawn(Player $player, Game|TeamableGame $game, Vector3 $position): void
    {
        $team = $game->getPlayerTeam($player);
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->clearAll();
        $player->getInventory()->addItem(VanillaItems::WOODEN_SWORD());
        $player->getArmorInventory()->setHelmet(VanillaItems::LEATHER_CAP()->setCustomColor($team->getDyeColor()->getRgbValue()));
        $player->getArmorInventory()->setChestplate(VanillaItems::LEATHER_TUNIC()->setCustomColor($team->getDyeColor()->getRgbValue()));
        $player->getArmorInventory()->setLeggings(VanillaItems::LEATHER_PANTS()->setCustomColor($team->getDyeColor()->getRgbValue()));
        $player->getArmorInventory()->setBoots(VanillaItems::LEATHER_BOOTS()->setCustomColor($team->getDyeColor()->getRgbValue()));
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