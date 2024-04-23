<?php

namespace UHC\games;

use Alias\exceptions\BehaviorAlreadyExistsException;
use Alias\game\behaviors\StartingKitBehavior;
use Alias\game\GameInformation;
use Alias\game\GameType;
use Alias\game\GameVariant;
use Alias\game\maps\GeneratedMap;
use Alias\game\RankedInformation;
use Alias\players\Scoreboard;
use Alias\utils\Utils;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\VanillaItems;
use pocketmine\utils\TextFormat;
use UHC\games\behaviors\AutoEnchantToolsBehavior;
use UHC\games\behaviors\CutCleanBehavior;
use UHC\games\behaviors\HardcoreBehavior;
use UHC\games\behaviors\InstantDeleteTreeBehavior;
use UHC\games\behaviors\PvpBehavior;
use UHC\games\spawners\UHCSpawner;

class ClassicUHC extends \Alias\game\Game
{

    /**
     * @throws BehaviorAlreadyExistsException
     */
    public function init(string $uuid, bool $isRanked): void
    {
        $ironPickaxe = VanillaItems::IRON_PICKAXE();
        $axe = VanillaItems::IRON_AXE();
        $shovel = VanillaItems::IRON_SHOVEL();
        $efficiency = new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 5);
        $unbreaking = new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 4);
        $ironPickaxe->addEnchantment($efficiency);
        $ironPickaxe->addEnchantment($unbreaking);
        $axe->addEnchantment($efficiency);
        $axe->addEnchantment($unbreaking);
        $this->addBehavior(new StartingKitBehavior([
            $shovel,
            $ironPickaxe,
            $axe,
            VanillaItems::BOOK()->setCount(10),
            VanillaItems::FEATHER()->setCount(10),
            VanillaItems::COOKED_CHICKEN()->setCount(64)
        ]));
        $this->addBehavior(new InstantDeleteTreeBehavior());
        $this->addBehavior(new AutoEnchantToolsBehavior());
        $this->addBehavior(new HardcoreBehavior());
        $this->addBehavior(new PvpBehavior(15));
        $this->addBehavior(new CutCleanBehavior());
        parent::init($uuid, $isRanked);
    }

    public function onUpdate(): void
    {
        parent::onUpdate();
        foreach ($this->getAvailablePlayers() as $playerGame){
            $player = $playerGame->getPlayer();
            $scoreboard = new Scoreboard($player->getName(), "uhcrun.scoreboard", "§l§6UHC RUN");
            $scoreboard->setLine(0, TextFormat::DARK_GRAY . $this->getUuid());
            $scoreboard->setLine(1, "      ");
            $scoreboard->setLine(2, "§7Joueurs:    ");
            $scoreboard->setLine(3, "§7Kill(s): §e" . $playerGame->getKills());
            $scoreboard->setLine(4, "      ");
            $scoreboard->setLine(5, "§7Pvp: " . "");
            $scoreboard->setLine(6, "      ");

            $scoreboard->setLine(7, "§7Bordure:  ");
            $scoreboard->setLine(8, "§7Centre: ");
            $scoreboard->setLine(9, "   ");
            $scoreboard->setLine(7, "§6alias.net");

            $scoreboard->show();
        }
    }

    /**
     * @inheritDoc
     */
    public function getVariants(): array
    {
        return [
            "classic" => new GameVariant(
                "classic",
                new GameInformation("classic_uhc", 1, 5, 10, false, false, "UCH Run"),
                [
                    new GeneratedMap("basicgame_generator", new UHCSpawner(1000, 1000))
                ]
            )
        ];
    }

    public function getRankedInformation(): ?RankedInformation
    {
        return null;
    }

    public function getType(): GameType
    {
        return GameType::LAST_SURVIVOR;
    }

    public function getGameInformation(): GameInformation
    {
        return new GameInformation("classic_uhc", "UHC Run", "UHC But Fast", false, true);
    }
}