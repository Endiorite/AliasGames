<?php

namespace Bedwars\constants;

use pocketmine\utils\TextFormat;


class BedwarsMessages
{
    const PREFIX = "§l§cBed§fWars§r§f ";

    const CANT_BREAK_YOUR_BED = self::PREFIX . "§cVous ne pouvez pas casser votre propre lit !";
    const BED_BREAK = self::PREFIX . "§e{player}§f à casser le lit de la team {team}";
    const TEAMBED_BREAK = self::PREFIX . "Votre lit à été casser, vous ne pouvez plus réapparaitre !";

    const BREAK_ALL_BED = self::PREFIX . "§c§lMort Subite§r§f Tout les §clit§f restant on été cassé !";
    const TEAM_ELIMINATED = self::PREFIX . "{team}§r§f à été éliminée";
    const ERROR = self::PREFIX . "§cAn error occured";
    const TEAMUPGRADE = self::PREFIX . "§7Vous avez débloqué l'amélioration: §c{name}";
    const DIAMONDGENERATORUPGRADE = self::PREFIX . "Tout les générateurs de §bdiaman§f sont passer Tier {tier}";

}