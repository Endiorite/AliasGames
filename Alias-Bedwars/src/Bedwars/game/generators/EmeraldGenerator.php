<?php

namespace Bedwars\game\generators;

use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;

class EmeraldGenerator extends Generator
{

    public function getBlockItem(): Item
    {
       return VanillaBlocks::EMERALD()->asItem();
    }

    public function getItem(): Item
    {
        return VanillaItems::EMERALD();
    }

    public function getName(): string
    {
        return "§2Emerald";
    }

    public function hasNamedTag(): bool
    {
        return true;
    }
}