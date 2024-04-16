<?php

namespace UHC\games\behaviors;

use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\item\GoldenApple;
use pocketmine\item\GoldenAppleEnchanted;
use pocketmine\item\RawBeef;

class HardcoreBehavior extends \Alias\game\behaviors\Behavior
{

    public function getName(): string
    {
        return "HardcoreBehavior";
    }

    public function onRegainHealth(EntityRegainHealthEvent $event): void
    {
        $reason = $event->getRegainReason();
        if ($reason === EntityRegainHealthEvent::CAUSE_REGEN or $reason === EntityRegainHealthEvent::CAUSE_SATURATION or $reason === EntityRegainHealthEvent::CAUSE_EATING){
            $event->cancel();
        }
    }

    public function onUpdate(): void{}

    public function hasUpdate(): bool
    {
        return false;
    }
}