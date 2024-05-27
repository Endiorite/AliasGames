<?php

namespace TheBridge\games\behaviors;

use Alias\game\behaviors\Behavior;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemBlock;

class SecondHandBehavior extends Behavior
{

    public function getName(): string
    {
        return "SecondHandBehavior";
    }

    public function onUpdate(): void{}

    public function hasUpdate(): bool
    {
        return false;
    }

    public function onInteract(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
            $item = $player->getOffHandInventory()->getItem(0);
            if ($item instanceof ItemBlock){
                if ($player->getWorld()->getBlock($event->getTouchVector())->canBePlaced()){
                    $player->getWorld()->setBlock($event->getTouchVector(), $item->getBlock($event->getFace()));
                    $item->pop();
                    $player->getOffHandInventory()->setItem(0, $item);
                }
            }
        }
    }
}