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

namespace Bedwars\entities;

use Alias\players\AliasPlayer;
use Bedwars\game\BedwarsGame;
use Bedwars\interfaces\UpgradeForm;
use Bedwars\Utils;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;

class UpgraderEntity extends \pocketmine\entity\Entity
{

    protected function getInitialSizeInfo(): EntitySizeInfo{ return new EntitySizeInfo(1.8, 0.6, 1.62); }

    protected function getInitialDragMultiplier() : float{ return 0.02; }

    protected function getInitialGravity() : float{ return 0.08; }

    public static function getNetworkTypeId(): string{ return EntityIds::VILLAGER; }

    protected function initEntity(CompoundTag $nbt): void
    {
        parent::initEntity($nbt);
        $this->setNameTagAlwaysVisible();
        $this->setNameTagAlwaysVisible();

        $this->setNameTag("§eTEAM UPGRADE");
        $this->setScoreTag("§7Tap me !");
    }

    public function onInteract(Player $player, Vector3 $clickPos): bool
    {
        $this->openInterface($player);
        return parent::onInteract($player, $clickPos);
    }

    public function attack(EntityDamageEvent $source): void
    {
        if ($source instanceof EntityDamageByEntityEvent){
            if ($source->getDamager() instanceof Player){
                $this->openInterface($source->getDamager());
            }
        }
        $source->cancel();
    }

    public function openInterface(Player|AliasPlayer $player){
        if(($game = $player->getGame()) === null or !$game instanceof BedwarsGame) return;

        $team = $game->getPlayerTeam($player);

        $player->sendForm(new UpgradeForm($game, $team, $player));
    }
}