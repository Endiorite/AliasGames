<?php

namespace Bedwars\interfaces;

use Alias\game\Team;
use Alias\libs\FormAPI\SimpleForm;
use Bedwars\constants\BedwarsMessages;
use Bedwars\entities\Upgrader;
use Bedwars\game\BedwarsGame;
use Bedwars\game\BedwarsTeam;
use Bedwars\game\teams\TeamUpgrade;
use Bedwars\Utils;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class UpgradeForm extends SimpleForm
{

    public function __construct(BedwarsGame $game, BedwarsTeam $team, Player $player)
    {
        $teamUpgrade = $team->getTeamUpgrade();
        $diamond = Utils::getItemCountInInventory($player, VanillaItems::DIAMOND());
        parent::__construct(function (Player $player, mixed $data) use ($team, $teamUpgrade, $diamond, $game){
            if (is_null($data)) return;

            $upgrade = $game->getUpgradeShop()[$data] ?? null;
            if (is_null($upgrade)){
                $player->sendMessage(BedwarsMessages::ERROR);
                return;
            }
            if ($diamond >= $upgrade->getPrice($team)){
                if(!$upgrade->max($teamUpgrade)){
                    $upgrade->apply($team);
                    $team->broadcastMessage(str_replace("{name}", $upgrade->getName($team), BedwarsMessages::TEAMUPGRADE));
                    $player->sendForm(new UpgradeForm($game, $team, $player));
                }else $player->sendMessage(BedwarsMessages::PREFIX . "§cYou have reached the maximum level");
            }else $player->sendMessage(BedwarsMessages::PREFIX . "§cYou don't have enough diamond");
        });
        $this->setTitle("§eTeam Upgrade");

        foreach ($game->getUpgradeShop() as $index => $upgrade){
            $message = "";
            if ($upgrade->max($teamUpgrade)){
                $message = "§cYou have reached the maximum level";
            }else if ($diamond < $upgrade->getPrice($team)){
                $message = "§cYou don't have enough diamond";
            }
            $this->addButton(sprintf("%s\n§b%d diamonds\n%s", $upgrade->getName($team), $upgrade->getPrice($team), $message), SimpleForm::IMAGE_TYPE_PATH, $upgrade->getDisplayTexture(), $index);
        }
    }

}