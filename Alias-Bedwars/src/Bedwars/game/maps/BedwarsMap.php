<?php

namespace Bedwars\game\maps;

use Alias\game\Game;
use Alias\game\spawners\GameSpawner;
use Bedwars\game\BedwarsGame;
use Bedwars\game\generators\Generator;
use Bedwars\game\generators\TeamGenerator;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;

class BedwarsMap extends \Alias\game\TeamableMap
{

    /**
     * @var TeamGenerator[][]
     */
    private array $teamGenerator;
    /**
     * @var Generator[]
     */
    private array $generators;
    public function __construct(string $baseWorldName, GameSpawner $spawner, array $spawnPositions, array $teamGenerators, array $generator)
    {
        $this->teamGenerator = $teamGenerators;
        $this->generators = $generator;
        parent::__construct($baseWorldName, $spawner, $spawnPositions);
    }

    public function initMap(Game|BedwarsGame $game): void
    {
        foreach ($game->getTeams() as $team){
            $identifier = $team->getIdentifier();
            $generators = $this->teamGenerator[$identifier] ?? null;
            if (is_null($generators) or is_null(($ironGenerator = $generators["iron"] ?? null)) or
                is_null(($goldGenerator = $generators["gold"] ?? null) or is_null(($emeraldGenerator = $generators["emerald"] ?? null)))
            ){
                throw new \Exception("Generators for '$identifier' not found in map with base world" . $this->getBaseWorldName());
            }
            $emeraldGenerator->setSpeed(PHP_INT_MAX);

            $goldGenerator = clone $goldGenerator;
            $goldGenerator->setWorld($game->getWorld());
            $ironGenerator = clone $ironGenerator;
            $ironGenerator->setWorld($game->getWorld());
            $emeraldGenerator = clone $emeraldGenerator;
            $emeraldGenerator->setWorld($game->getWorld());
            $team->setGoldGenerator($goldGenerator);
            $team->setIronGenerator($ironGenerator);
            $team->setEmeraldGenerator($emeraldGenerator);
        }

        foreach ($this->generators as $generator){
            $generator->setWorld($game->getWorld());
            $generator->spawnEntity();

            $game->addGenerator($generator);
        }
    }

    /**
     * @return array
     */
    public function getGenerators(): array
    {
        return $this->generators;
    }
}