<?php

namespace Bedwars\game\generators;

use pocketmine\block\Wall;
use pocketmine\entity\Location;
use pocketmine\entity\object\ItemEntity;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat;
use pocketmine\world\particle\FloatingTextParticle;
use pocketmine\world\Position;
use pocketmine\world\World;

abstract class Generator
{

    private Vector3 $position;
    private ?World $world = null;
    public int $lastUpdate = 0;
    private int $speed = 15;
    private int $tier = 1;
    private ?FloatingTextParticle $entity = null;
    public function __construct(int $defaultSpeed, Vector3 $position)
    {
        $this->position = $position;

        if ($this->hasNamedTag()){
            if (!is_null($this->getWorld())){
                $this->spawnEntity();
            }
        }

        $this->speed = $defaultSpeed;
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
        if(!is_null($this->entity)){
            $this->entity->setText($this->getName() . " " . str_repeat("I", $this->tier) . "\n" .
                TextFormat::YELLOW . "Spawns in " . TextFormat::RED . ($time = ($this->speed - (time() - $this->lastUpdate))) . TextFormat::YELLOW . ($time === 1 ? " second" : " seconds"));
            foreach ($this->entity->encode($this->position->asVector3()->add(0, 1, 0)) as $packet) {
                foreach ($this->getWorld()->getPlayers() as $player) {
                    $player->getNetworkSession()->sendDataPacket($packet);
                }
            }
        }
        if (time() - $this->lastUpdate >= $this->speed){
            $this->generate();
        }
    }

    public function spawnEntity(): void{
        $this->entity = new FloatingTextParticle(
            $this->getName() . " " . str_repeat("I", $this->tier) . "\n" .
            TextFormat::YELLOW . "Spawns in " . TextFormat::RED . ($time = ($this->speed - (time() - $this->lastUpdate))) . TextFormat::YELLOW . ($time === 1 ? " second" : " seconds"));
        $this->getWorld()->addParticle($this->getPosition()->add(0, 1, 0), $this->entity);
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
        $world->dropItem($this->getPosition(), clone $this->getItem(), new Vector3(0, 0, 0));
        $this->lastUpdate = time();
    }

    abstract public function getBlockItem(): Item;
    abstract public function getItem(): Item;
    abstract public function getName(): string;
    abstract public function hasNamedTag(): bool;
}