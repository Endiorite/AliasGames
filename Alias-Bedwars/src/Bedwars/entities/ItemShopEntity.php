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

namespace Bedwars\entities;

use Alias\players\AliasPlayer;
use Bedwars\game\BedwarsGame;
use Bedwars\interfaces\ItemShopInv;
use Bedwars\Utils;
use muqsit\invmenu\InvMenu;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\PotionType;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;

class ItemShopEntity extends \pocketmine\entity\Entity
{

    protected function getInitialSizeInfo(): EntitySizeInfo{ return new EntitySizeInfo(1.8, 0.6, 1.62); }

    protected function getInitialDragMultiplier() : float{ return 0.02; }

    protected function getInitialGravity() : float{ return 0.08; }

    public static function getNetworkTypeId(): string{ return EntityIds::VILLAGER; }

    protected function initEntity(CompoundTag $nbt): void
    {
        parent::initEntity($nbt);
        $this->setNameTagAlwaysVisible();
        $this->setNameTagAlwaysVisible();

        $this->setNameTag("§eITEM SHOP");
        $this->setScoreTag("§7Tap me !");
    }

    public function onInteract(Player $player, Vector3 $clickPos): bool
    {
        $this->openInterface($player);
        return parent::onInteract($player, $clickPos);
    }

    public function attack(EntityDamageEvent $source): void
    {
        if ($source instanceof EntityDamageByEntityEvent){
            if ($source->getDamager() instanceof Player){
                $this->openInterface($source->getDamager());
            }
        }
    }

    public function openInterface(Player|AliasPlayer $player){
        if(($game = $player->getGame()) === null or !$game instanceof BedwarsGame) return;

        $team = $game->getPlayerTeam($player);
        $playerGame = $game->getPlayerGame($player->getName());

        $wool = VanillaBlocks::WOOL()->setColor($team->getDyeColor())->asItem();
        $blockCategory = new ItemShopCategory($wool, "Blocks", "All block to protect your bed", [
            new ShopItem($wool->setCount(16), 4, ShopItem::IRON),
            new ShopItem(VanillaBlocks::SANDSTONE()->asItem()->setCount(16), 12, ShopItem::IRON),
            new ShopItem(VanillaBlocks::END_STONE()->asItem()->setCount(12), 24, ShopItem::IRON),
            new ShopItem(VanillaBlocks::OAK_WOOD()->asItem()->setCount(16), 4, ShopItem::GOLD),
            new ShopItem(VanillaBlocks::OBSIDIAN()->asItem()->setCount(4), 4, ShopItem::EMERALD),
        ]);

        $stick = VanillaItems::STICK()->setCount(1);
        $stick->addEnchantment(new EnchantmentInstance(VanillaEnchantments::KNOCKBACK()));

        $weaponCategory = new ItemShopCategory(VanillaItems::STONE_SWORD(), "Armor", "Get the best protection !", [
            new ShopItem(VanillaItems::STONE_SWORD(), 10, ShopItem::IRON),
            new ShopItem(VanillaItems::IRON_SWORD(), 7, ShopItem::GOLD),
            new ShopItem($stick, 10, ShopItem::GOLD),
            new ShopItem(VanillaItems::DIAMOND_SWORD(), 4, ShopItem::EMERALD),
        ]);

        $bow2 = VanillaItems::BOW();
        $power = new EnchantmentInstance(VanillaEnchantments::POWER());
        $bow2->addEnchantment($power);

        $bow3 = VanillaItems::BOW();
        $bow3->addEnchantment($power);
        $bow3->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PUNCH()));
        $rangedCategory = new ItemShopCategory(VanillaItems::ARROW(), "Ranged", "Make flick en clip your opponent !", [
            new ShopItem(VanillaItems::ARROW()->setCount(8), 2, ShopItem::GOLD),
            new ShopItem(VanillaItems::BOW(), 12, ShopItem::GOLD),
            new ShopItem($bow2, 24, ShopItem::GOLD),
            new ShopItem($bow3, 6, ShopItem::EMERALD),
        ]);

        $efficiency = VanillaEnchantments::EFFICIENCY();
        $stone = VanillaItems::STONE_PICKAXE();
        $stone->addEnchantment(new EnchantmentInstance($efficiency));
        $iron = VanillaItems::IRON_PICKAXE();
        $iron->addEnchantment(new EnchantmentInstance($efficiency, 2));
        $diamond = VanillaItems::DIAMOND_PICKAXE();
        $diamond->addEnchantment(new EnchantmentInstance($efficiency, 3));
        $axe = VanillaItems::DIAMOND_AXE();
        $axe->addEnchantment(new EnchantmentInstance($efficiency, 2));

        $toolsCategory = new ItemShopCategory(VanillaItems::WOODEN_PICKAXE(), "Tools", "Best tools to destroy opponent bed !", [
            new ShopItem(VanillaItems::WOODEN_PICKAXE(), 10, ShopItem::IRON),
            new ShopItem($stone, 20, ShopItem::IRON),
            new ShopItem($iron, 8, ShopItem::GOLD),
            new ShopItem($diamond, 12, ShopItem::GOLD),
            new ShopItem($axe, 12, ShopItem::GOLD),
            new ShopItem(VanillaItems::SHEARS(), 30, ShopItem::IRON)
        ]);

        $speed = VanillaItems::POTION()->setType(PotionType::SWIFTNESS);
        $jump = VanillaItems::POTION()->setType(PotionType::LEAPING);
        $invisibility = VanillaItems::POTION()->setType(PotionType::INVISIBILITY);

        $potionCategory = new ItemShopCategory($invisibility, "Tools", "Best tools to destroy opponent bed !", [
            new ShopItem($speed, 1, ShopItem::EMERALD),
            new ShopItem($jump, 1, ShopItem::EMERALD),
            new ShopItem($invisibility, 1, ShopItem::EMERALD),
        ]);

        $utilityCategory = new ItemShopCategory($invisibility, "Tools", "Best tools to destroy opponent bed !", [
            new ShopItem(VanillaItems::ENDER_PEARL()->setCount(1), 4, ShopItem::EMERALD),
            new ShopItem(VanillaItems::FIRE_CHARGE()->setCount(1), 50, ShopItem::IRON),
            new ShopItem(VanillaBlocks::TNT()->asItem()->setCount(1), 8, ShopItem::GOLD),
            new ShopItem(VanillaItems::WATER_BUCKET(), 1, ShopItem::EMERALD)
        ]);

        $categories = [
            $blockCategory,
            $weaponCategory,
            $rangedCategory,
            $toolsCategory,
            $potionCategory,
            $utilityCategory
        ];
        $inv = new ItemShopInv();
        $inv->setCategories($categories);
        $inv->display($player);
        $inv->send($player);
    }
}