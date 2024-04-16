<?php

namespace UHC\games\spawners;

use Alias\game\Game;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\Position;

class UHCSpawner extends \Alias\game\spawners\GameSpawner
{

    protected int $maxPosition;
    private int $minPosition;
    public function __construct(int $maxPosition, int $minPosition)
    {
        $this->minPosition = $minPosition;
        $this->maxPosition = $maxPosition;
    }

    public function spawn(Game $game, Player $player): Position
    {
        $x = mt_rand(-$this->minPosition, $this->maxPosition);
        $z = mt_rand(-$this->minPosition, $this->maxPosition);
        $safeSpawn = $game->getWorld()->getSafeSpawn(new Vector3($x, 1000, $z));
        $player->teleport($safeSpawn);
        return $safeSpawn;
    }
}