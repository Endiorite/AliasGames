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
        return Position::fromObject(new Vector3($x, 1000, $z), $game->getWorld());
    }

    public static function teleport(Player $player, Position $position): void
    {
        $world = $position->getWorld();
        $chunkX = $position->getFloorX() >> 4;
        $chunkZ = $position->getFloorZ() >> 4;
        if ($world->isChunkPopulated($chunkX, $chunkZ) && $world->isChunkGenerated($chunkX, $chunkZ)){
            $player->teleport($world->getSafeSpawn($position));
        }else{
            $world->orderChunkPopulation($chunkX, $chunkZ, null)->onCompletion(function () use ($player, $position, $world){
                $player->teleport($world->getSafeSpawn($position));
            }, fn() => null);
        }
    }
}