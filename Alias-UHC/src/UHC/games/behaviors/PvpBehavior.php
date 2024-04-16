<?php

namespace UHC\games\behaviors;

use pocketmine\event\entity\EntityDamageByEntityEvent;

class PvpBehavior extends \Alias\game\behaviors\Behavior
{

    private bool $pvp = false;
    private int $startAt = 0;
    private ?int $startTime = null;

    public function __construct(int $startAt = 10)
    {
        $this->startAt = $startAt;
    }

    public function getName(): string
    {
        return "PvpBehavior";
    }

    public function onEntityDamageByEntity(EntityDamageByEntityEvent $event): void
    {
        if (!$this->pvp){
            $event->cancel();
        }
    }

    public function onUpdate(): void{
        if (is_null($this->startTime)){
            if ($this->getGame()->isStarted()){
                $this->startTime = time();
            }
        }else if(time() - $this->startTime >= $this->startAt){
            $this->pvp = true;
        }
    }

    public function hasUpdate(): bool
    {
        return true;
    }
}