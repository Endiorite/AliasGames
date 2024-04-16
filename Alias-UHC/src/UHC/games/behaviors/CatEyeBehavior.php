<?php

namespace UHC\games\behaviors;

use Alias\game\Game;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class CatEyeBehavior extends \Alias\game\behaviors\Behavior
{

    public function getName(): string
    {
        return "CatEye";
    }

    public function onSpawn(Player $player, Game $game, Vector3 $position): void
    {
        $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), PHP_INT_MAX, 2, false));
    }

    public function onUpdate(): void{}

    public function hasUpdate(): bool
    {
        return false;
    }
}