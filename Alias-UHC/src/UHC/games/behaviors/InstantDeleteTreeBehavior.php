<?php

namespace UHC\games\behaviors;

use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\block\Leaves;
use pocketmine\block\VanillaBlocks;
use pocketmine\block\Wood;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;

class InstantDeleteTreeBehavior extends \Alias\game\behaviors\Behavior
{

    public function getName(): string
    {
        return "InstantDeleteTree";
    }

    public function onBlockBreak(BlockBreakEvent $event): void
    {
        $this->deleteTree($event->getBlock(), $event->getPlayer()->getInventory()->getItemInHand());
    }

    public function deleteTree(Block $block, Item $item, int &$iteration = 0): void{
        $world = $block->getPosition()->getWorld();
        if ($iteration >= 20) return;

        for ($x = -1;$x <= 1; $x++){
            for ($y = -1;$y <= 1; $y++){
                for ($z = -1;$z <= 1; $z++){
                    $blockAt = $world->getBlockAt($x, $y, $z);
                    if ($blockAt instanceof Air) continue;
                    if ($blockAt instanceof Leaves or $blockAt instanceof Wood){
                        $world->setBlockAt($x, $y, $z, VanillaBlocks::AIR());
                        $drops = $blockAt->getDrops($item);
                        foreach ($drops as $item){
                            $world->dropItem(new Vector3($x, $y, $z), $item);
                        }
                        $this->deleteTree($blockAt, $item);
                    }
                }
            }
        }

        $iteration++;
    }

    public function onUpdate(): void{}

    public function hasUpdate(): bool
    {
        return false;
    }
}