<?php

namespace Bedwars\game\generators;

use pocketmine\block\Wall;
use pocketmine\entity\Location;
use pocketmine\entity\object\ItemEntity;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\world\Position;
use pocketmine\world\World;

abstract class Generator
{

    private Vector3 $position;
    private ?World $world = null;
    public int $lastUpdate = 0;
    private int $speed = 15;
    private int $tier = 1;
    private ?ItemEntity $entity = null;
    public function __construct(Vector3 $position)
    {
        $this->position = $position;

        if ($this->hasNamedTag()){
            if (!is_null($this->getWorld())){
                $this->spawnEntity();
            }
        }
    }

    /**
     * @param World $world
     */
    public function setWorld(World $world): void
    {
        $this->world = $world;
    }

    public function getTier(): int{
        return $this->tier;
    }

    public function upgrade(): void{
        $this->tier++;
        if ($this->hasNamedTag()){
            $this->entity->setNameTag($this->getName() . " " . str_repeat("I", $this->tier));
        }
    }

    /**
     * @return int
     */
    public function getLastUpdate(): int
    {
        return $this->lastUpdate;
    }

    /**
     * @return int
     */
    public function getSpeed(): int
    {
        return $this->speed;
    }

    /**
     * @return Vector3
     */
    public function getPosition(): Vector3
    {
        return $this->position;
    }

    /**
     * @return World|null
     */
    public function getWorld(): ?World
    {
        return $this->world;
    }

    public function onUpdate(): void{
        if (is_null($this->getWorld())) return;
        if(is_null($this->entity)){
            $this->spawnEntity();
        }
        if (time() - $this->lastUpdate >= $this->speed){
            $this->generate();
        }
    }

    public function spawnEntity(): void{
        $this->entity = new ItemEntity(Location::fromObject($this->getPosition(), $this->world), $this->getItem());
        $this->entity->setPickupDelay(ItemEntity::NEVER_DESPAWN);
        $this->entity->setDespawnDelay(ItemEntity::NEVER_DESPAWN);
        $this->entity->setNameTagAlwaysVisible();
        $this->entity->setNameTagVisible();
        $this->entity->setNameTag($this->getName() . " " . str_repeat("I", $this->tier));
    }

    /**
     * @param int $speed
     */
    public function setSpeed(int $speed): void
    {
        $this->speed = $speed;
    }


    public function generate(): void{
        $world = $this->getWorld();
        $world->dropItem($this->getPosition(), clone $this->getItem());
        $this->lastUpdate = time();
    }
    abstract public function getBlockItem(): Item;
    abstract public function getItem(): Item;
    abstract public function getName(): string;
    abstract public function hasNamedTag(): bool;
}