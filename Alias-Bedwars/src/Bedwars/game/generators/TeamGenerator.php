<?php

namespace Bedwars\game\generators;

use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\world\World;

class TeamGenerator extends Generator
{

    private Item $item;
    private int $maxSpawn;
    public function __construct(Item $item, int $defaultSpeed, int $maxSpawn, Vector3 $position)
    {
        $this->item = $item;
        $this->maxSpawn = $maxSpawn;
        parent::__construct($defaultSpeed, $position);
    }

    public function getBlockItem(): Item
    {
        return VanillaItems::RABBIT_FOOT();
    }

    public function generate(): void
    {
        $world = $this->getWorld();
        $world->dropItem($this->getPosition(), clone $this->getItem()->setCount(mt_rand(1, $this->maxSpawn)));
        $this->lastUpdate = time();
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public function getName(): string
    {
        return "team_generator";
    }

    public function hasNamedTag(): bool
    {
        return false;
    }
}