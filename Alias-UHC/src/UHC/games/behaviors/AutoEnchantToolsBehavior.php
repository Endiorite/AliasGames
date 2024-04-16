<?php

namespace UHC\games\behaviors;

use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Sword;
use pocketmine\item\Tool;

class AutoEnchantToolsBehavior extends \Alias\game\behaviors\Behavior
{

    public function getName(): string
    {
        return "AutoEnchantBehavior";
    }

    public function onUpdate(): void
    {
        // TODO: Implement onUpdate() method.
    }

    public function hasUpdate(): bool
    {
        return false;
    }

    public function onCraft(CraftItemEvent $event): void
    {
        $outputs = [];
        $event->cancel();
        foreach ($event->getOutputs() as $output){
            if ($output instanceof Tool && !$output instanceof Sword){
                $efficiency = new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 5);
                $unbreaking = new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 4);
                $output->addEnchantment($efficiency);
                $output->addEnchantment($unbreaking);
            }
            $event->getPlayer()->getInventory()->addItem($output);
        }
    }
}