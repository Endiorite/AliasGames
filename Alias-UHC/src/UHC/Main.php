<?php

/*
 *  ______           _ _            _ _
 * |  ____|         | (_)          (_) |
 * | |__   _ __   __| |_  ___  _ __ _| |_ ___
 * |  __| | '_ \ / _` | |/ _ \| '__| | __/ _ \
 * | |____| | | | (_| | | (_) | |  | | ||  __/
 * |______|_| |_|\__,_|_|\___/|_|  |_|\__\___|
 *
 * @author Endiorite Team
 * @link http://www.endiorite.fr/
 */

namespace UHC;

use Alias\managers\GameManager;
use pocketmine\utils\TextFormat;
use ScoreboardViewer\scoreboard\ScoreboardBuilder;
use ScoreboardViewer\scoreboard\ScoreboardLineBuilder;
use ScoreboardViewer\ViewerManager;
use UHC\games\ClassicUHC;
use UHC\generators\BasicGenerator\BasicGameGenerator;
use pocketmine\world\generator\GeneratorManager;

class Main extends \pocketmine\plugin\PluginBase
{

    protected function onEnable(): void
    {
        GameManager::getInstance()->registerGame("classic_uhc", new ClassicUHC());
        GeneratorManager::getInstance()->addGenerator(BasicGameGenerator::class, "basicgame_generator", fn() => null);

        ViewerManager::getInstance()->registerScoreboard(
            ScoreboardBuilder::build("uhc_run")
                ->setDisplayName("§l§6UHC RUN")
                ->addLine(ScoreboardLineBuilder::build("0")
                    ->setIndex(0)
                    ->setContent(TextFormat::DARK_GRAY . "{game_uuid}"))
                ->addLine(ScoreboardLineBuilder::build("1")
                    ->setIndex(1)
                    ->setContent("     "))
                ->addLine(ScoreboardLineBuilder::build("2")
                    ->setIndex(2)
                    ->setContent(TextFormat::DARK_GRAY . "{game_uuid}"))
        );
    }
}