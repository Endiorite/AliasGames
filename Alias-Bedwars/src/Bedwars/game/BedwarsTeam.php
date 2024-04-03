<?php

namespace Bedwars\game;

use Alias\game\Team;
use Alias\players\AliasPlayer;
use Bedwars\constants\BedwarsMessages;
use Bedwars\game\generators\Generator;
use Bedwars\game\teams\TeamUpgrade;
use pocketmine\block\Bed;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\math\Vector3;
use pocketmine\world\Position;

class BedwarsTeam extends \Alias\game\Team
{

    private Vector3 $bedPosition;
    private bool $bedDestroy = false;
    private int $teamDestroy = 0;
    private DyeColor $dyeColor;
    private TeamUpgrade $teamUpgrade;
    private Generator $ironGenerator;
    private Generator $goldGenerator;
    private Generator $emeraldGenerator;
    private int $lastGenerate = 0;

    public function __construct(string $name, string $identifier, Vector3 $bedPosition, DyeColor $dyeColor, int $max_players = 2)
    {
        $this->bedPosition = $bedPosition;
        $this->dyeColor = $dyeColor;
        $this->teamUpgrade = new TeamUpgrade();
        parent::__construct($name, $identifier, $max_players);
    }

    /**
     * @param Generator $emeraldGenerator
     */
    public function setEmeraldGenerator(Generator $emeraldGenerator): void
    {
        $this->emeraldGenerator = $emeraldGenerator;
    }

    /**
     * @param Generator $goldGenerator
     */
    public function setGoldGenerator(Generator $goldGenerator): void
    {
        $this->goldGenerator = $goldGenerator;
    }

    /**
     * @param Generator $ironGenerator
     */
    public function setIronGenerator(Generator $ironGenerator): void
    {
        $this->ironGenerator = $ironGenerator;
    }
    /**
     * @return Generator
     */
    public function getEmeraldGenerator(): Generator
    {
        return $this->emeraldGenerator;
    }

    /**
     * @return Generator
     */
    public function getGoldGenerator(): Generator
    {
        return $this->goldGenerator;
    }

    /**
     * @return Generator
     */
    public function getIronGenerator(): Generator
    {
        return $this->ironGenerator;
    }

    /**
     * @return int
     */
    public function getLastGenerate(): int
    {
        return $this->lastGenerate;
    }

    /**
     */
    public function setLastGenerate(): void
    {
        $this->lastGenerate = time();
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

    public function canRespawn(): bool{
        return !$this->bedDestroy;
    }

    /**
     * @return Vector3
     */
    public function getBedPosition(): Vector3
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