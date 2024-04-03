<?php

namespace Bedwars\game\generators;

use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;

class DiamondGenerator extends Generator
{

    public function getBlockItem(): Item
    {
        return VanillaBlocks::DIAMOND()->asItem();
    }

    public function getItem(): Item
    {
        return VanillaItems::DIAMOND();
    }

    public function getName(): string
    {
        return "§bDiamond";
    }

    public function hasNamedTag(): bool
    {
        return true;
    }
}