<?php

namespace Bedwars\game\maps;

use Alias\game\Game;
use Alias\game\maps\TeamableMap;
use Alias\game\spawners\GameSpawner;
use Bedwars\game\BedwarsGame;
use Bedwars\game\generators\Generator;
use Bedwars\game\generators\TeamGenerator;
use pocketmine\entity\Location;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\world\Position;
use pocketmine\world\World;

class BedwarsMap extends TeamableMap
{

    /**
     * @var TeamGenerator[][]
     */
    private array $teamGenerator;
    /**
     * @var Generator[]
     */
    private array $generators;
    private array $bedPositions;
    public function __construct(string $baseWorldName, GameSpawner $spawner, array $spawnPositions, array $bedPositions, array $teamGenerators, array $generator)
    {
        $this->teamGenerator = $teamGenerators;
        $this->generators = $generator;
        $this->bedPositions = $bedPositions;
        parent::__construct($baseWorldName, $spawner, $spawnPositions);
    }

    /**
     * @throws \Exception
     */
    public function initMap(Game|BedwarsGame $game): void
    {
        parent::initMap($game);
        foreach ($game->getTeams() as $team){
            $identifier = $team->getIdentifier();
            $generators = $this->teamGenerator[$identifier] ?? null;
            if (is_null($generators)){
                throw new \Exception("Generators for '$identifier' not found in map with base world" . $this->getBaseWorldName());
            }else{
                foreach($generators as $generator){
                    $generator->setWorld($game->getWorld());
                    $team->addGenerator($generator);
                }
            }
            if (is_null($bed = $this->bedPositions[$identifier] ?? null)){
                throw new \Exception("Bed for '$identifier' not found in map");
            }else $team->setBedPosition(Position::fromObject($bed, $game->getWorld()));
        }

        foreach ($this->generators as $generator){
            $generator->setWorld($game->getWorld());
            $generator->spawnEntity();

            $game->addGenerator($generator);
        }
        $game->getWorld()->setTime(World::TIME_DAY);
        $game->getWorld()->stopTime();
    }

    /**
     * @return array
     */
    public function getGenerators(): array
    {
        return $this->generators;
    }
}