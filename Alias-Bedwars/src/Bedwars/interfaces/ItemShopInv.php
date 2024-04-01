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

namespace Bedwars\interfaces;

use Bedwars\entities\ItemShopCategory;
use Bedwars\entities\ShopItem;
use Bedwars\Utils;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\InvMenuHandler;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use muqsit\invmenu\type\InvMenuType;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\inventory\Inventory;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class ItemShopInv extends \muqsit\invmenu\InvMenu
{

    /**
     * @var ItemShopCategory[]
     */
    private array $categories = [];
    private string $currentCategory = "block";
    public function __construct()
    {
        parent::__construct(InvMenuHandler::getTypeRegistry()->get(InvMenu::TYPE_CHEST));

        $menu = $this;
        $this->setListener(InvMenu::readonly(function(DeterministicInvMenuTransaction $transaction) use ($menu): void{
            $player = $transaction->getPlayer();
            $item = $transaction->getItemClicked();
            $nbt = $item->getNamedTag();
            $inv = $transaction->getAction()->getInventory();

            if (($categoryName = $nbt->getString("categoryShop", "default")) !== "default"){
                $menu->setCategory($categoryName);
                $menu->display($player);
                return;
            }

            if (($price = $nbt->getInt("itemShop", -1)) !== -1){
                $type = $nbt->getInt("itemType", 0);

                $money = match ($type){
                    ShopItem::GOLD => VanillaItems::GOLD_INGOT(),
                    ShopItem::IRON => VanillaItems::IRON_INGOT(),
                    ShopItem::DIAMOND => VanillaItems::DIAMOND(),
                    ShopItem::EMERALD => VanillaItems::EMERALD(),
                };

                if (Utils::playerHasCountItem($player, $money, $price)){
                    $newItem = StringToItemParser::getInstance()->parse($item->getVanillaName());
                    $newItem->setCount($item->getCount());
                    foreach ($item->getEnchantments() as $enchantment){
                        $newItem->addEnchantment($enchantment);
                    }

                    if ($player->getInventory()->canAddItem($newItem)){
                        $player->getInventory()->addItem($newItem);
                    }else{
                        $world = $player->getWorld();
                        $world->dropItem($player->getPosition(), $newItem, null, 20);
                    }
                }
            }

        }));
    }

    /**
     * @param array $categories
     */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    public function setCategory(string $identifier){
        $this->currentCategory = $identifier;
    }

    public function display(Player $player): void{
        $category = $this->categories[$this->currentCategory];
        $this->setName($category->getCategoryName());
        $inv = $this->getInventory();
        $inv->clearAll();

        foreach ($this->categories as $identifier => $category){
            $item = $category->getDisplayItem();
            $nbt = $item->getNamedTag();
            $nbt->setString("categoryShop", $identifier);
            $item->setNamedTag($nbt);
            $inv->addItem($item);
        }

        for ($slot = 0; $slot <= 8; $slot++){
            $glass = VanillaBlocks::STAINED_GLASS_PANE()->setColor(DyeColor::BLACK)->asItem()->setCustomName("        ");
            $inv->setItem(9+$slot, $glass);
        }

        $startingSlot = 19;
        $slot = 0;
        foreach ($category->getItems() as $item){
            if ($slot > 6){
                $startingSlot += 2;
                $slot = 0;
            }

            $count = match($item->getType()){
                ShopItem::GOLD => Utils::getItemCountInInventory($player, VanillaItems::GOLD_INGOT()),
                ShopItem::IRON => Utils::getItemCountInInventory($player, VanillaItems::IRON_INGOT()),
                ShopItem::EMERALD => Utils::getItemCountInInventory($player, VanillaItems::EMERALD()),
                ShopItem::DIAMOND => Utils::getItemCountInInventory($player, VanillaItems::DIAMOND())
            };
            $inv->setItem($startingSlot+$slot, $item->getItem($count));
        }
    }

}