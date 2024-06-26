<?php

namespace Bedwars\game;

use Alias\game\Team;
use Alias\players\AliasPlayer;
use Bedwars\constants\BedwarsMessages;
use Bedwars\game\generators\Generator;
use Bedwars\game\generators\TeamGenerator;
use Bedwars\game\teams\TeamUpgrade;
use pocketmine\block\Bed;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\math\Vector3;
use pocketmine\world\Position;

class BedwarsTeam extends \Alias\game\Team
{

    private Position $bedPosition;
    private bool $bedDestroy = false;
    private int $teamDestroy = 0;
    private DyeColor $dyeColor;
    private TeamUpgrade $teamUpgrade;
    /**
     * @var TeamGenerator[]
     */
    private array $generators = [];

    public function __construct(string $name, string $identifier, DyeColor $dyeColor, int $max_players = 2)
    {
        $this->dyeColor = $dyeColor;
        $this->teamUpgrade = new TeamUpgrade($this);
        parent::__construct($name, $identifier, $max_players);
    }

    /**
     * @return array
     */
    public function getGenerators(): array
    {
        return $this->generators;
    }

    public function addGenerator(TeamGenerator $generator): void{
        $this->generators[] = $generator;
    }

    /**
     * @return TeamUpgrade
     */
    public function getTeamUpgrade(): TeamUpgrade
    {
        return $this->teamUpgrade;
    }

    public function getDyeColor(): DyeColor{
        return $this->dyeColor;
    }

    public function getRestantPlayers(): array{
        $players = [];

        foreach ($this->getAvailablePlayers() as $player){
            if(!$player instanceof AliasPlayer) continue;

            $game = $player->getGame();
            if (is_null($game)) continue;

            $playerGame = $game->getPlayerGame($player->getName());
            if ($playerGame->getLife() > 0){
                $players[] = $player;
            }
        }

        return $players;
    }

    /**
     * @return int
     */
    public function getTeamDestroy(): int
    {
        return $this->teamDestroy;
    }

    public function addTeamDestroy(): void{
        $this->teamDestroy++;
    }

    public function bedBreak(): void{
        if ($this->bedDestroy) return;

        $world = $this->getBedPosition()->getWorld();
        if ($world->getBlock($this->getBedPosition()) instanceof Bed){
            $world->setBlock($this->getBedPosition(), VanillaBlocks::AIR());
        }

        foreach ($this->getAvailablePlayers() as $player){
            $player->sendMessage(BedwarsMessages::TEAMBED_BREAK);
            $player->sendTitle("§cBed Destroyed", "Vous ne pouvez plus respawn !");
        }

        $this->bedDestroy = true;
    }

    /**
     * @param Position $bedPosition
     */
    public function setBedPosition(Position $bedPosition): void
    {
        $this->bedPosition = $bedPosition;
    }

    public function canRespawn(): bool{
        return !$this->bedDestroy;
    }

    /**
     * @return Position
     */
    public function getBedPosition(): Position
    {
        return $this->bedPosition;
    }

    /**
     * @return bool
     */
    public function isBedDestroy(): bool
    {
        return $this->bedDestroy;
    }
}