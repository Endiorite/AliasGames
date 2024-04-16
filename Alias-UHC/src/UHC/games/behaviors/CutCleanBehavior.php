<?php

namespace UHC\games\behaviors;

use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\VanillaItems;

class CutCleanBehavior extends \Alias\game\behaviors\Behavior
{

    public function getName(): string
    {
        return "CutClean";
    }

    public function onBlockBreak(BlockBreakEvent $event): void
    {
        $block = $event->getBlock();

        $drops = [];
        foreach ($event->getDrops() as $item){
            switch ($item->getTypeId()){
                case VanillaBlocks::DIAMOND_ORE()->asItem()->getTypeId():
                case VanillaBlocks::DEEPSLATE_DIAMOND_ORE()->asItem()->getTypeId():
                    $drops[] = VanillaItems::DIAMOND()->setCount($item->getCount());
                    break;
                case VanillaBlocks::IRON_ORE()->asItem()->getTypeId():
                case VanillaBlocks::DEEPSLATE_IRON_ORE()->asItem()->getTypeId():
                    $drops[] = VanillaItems::IRON_INGOT()->setCount($item->getCount());
                break;
                case VanillaBlocks::GOLD_ORE()->asItem()->getTypeId():
                case VanillaBlocks::DEEPSLATE_GOLD_ORE()->asItem()->getTypeId():
                    $drops[] = VanillaItems::GOLD_INGOT()->setCount($item->getCount());
                    break;
            }
        }
        $event->setDrops($drops);
    }

    public function onUpdate(): void{}

    public function hasUpdate(): bool
    {
        return false;
    }
}