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

use Alias\players\AliasPlayer;
use Bedwars\game\BedwarsGame;
use Bedwars\game\shops\ItemShopCategory;
use Bedwars\game\shops\ShopItem;
use Bedwars\Utils;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\InvMenuHandler;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Armor;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\StringToItemParser;
use pocketmine\item\Sword;
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

            if(!$player instanceof AliasPlayer or is_null(($game = $player->getGame())) or !$game instanceof BedwarsGame or $game->isSpectate($player)) return;
            $playerGame = $game->getPlayerGame($player->getName());
            $team = $game->getPlayerTeam($player);
            $upgrade = $team->getTeamUpgrade();

            if (($categoryName = $nbt->getString("categoryShop", "default")) !== "default"){
                $menu->setCategory($categoryName);
                $menu->display($player);
                return;
            }

            if (($price = $nbt->getInt("itemShop", -1)) !== -1 && ($index = $nbt->getString("indexArray", "null")) !== "null"){
                $type = $nbt->getInt("itemType", 0);
                $explode = explode(":", $index);

                $money = match ($type){
                    ShopItem::GOLD => VanillaItems::GOLD_INGOT(),
                    ShopItem::IRON => VanillaItems::IRON_INGOT(),
                    ShopItem::DIAMOND => VanillaItems::DIAMOND(),
                    ShopItem::EMERALD => VanillaItems::EMERALD(),
                };

                if (Utils::playerHasCountItem($player, $money, $price)){
                    foreach ($game->getItemShop()[$explode[0]][$explode[1]] as $item){
                        if ($item instanceof Armor){
                            if ($upgrade->getProtectionArmor() > 0){
                                $enchant = new EnchantmentInstance(VanillaEnchantments::PROTECTION(), $upgrade->getProtectionArmor());
                                $item->addEnchantment($enchant);
                            }
                            $player->getArmorInventory()->setItem($item->getArmorSlot(), $item);
                            return;
                        }

                        if ($item instanceof Sword){
                            $currentEnchant = $item->getEnchantment(VanillaEnchantments::SHARPNESS());
                            if (is_null($currentEnchant) or $currentEnchant->getLevel() < $upgrade->getSharpness()){
                                $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), $upgrade->getSharpness()));
                            }
                        }

                        if ($player->getInventory()->canAddItem($item)){
                            $player->getInventory()->addItem($item);
                        }else{
                            $world = $player->getWorld();
                            $world->dropItem($player->getPosition(), $item, null, 20);
                        }
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
        foreach ($category->getItems() as $index => $item){
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
            $item = $item->getDisplayItem($count);
            $nbt = $item->getNamedTag();
            $nbt->setString("indexArray", implode(":", [$this->currentCategory, $index]));
            $item->setNamedTag($nbt);

            $inv->setItem($startingSlot+$slot, $item);
        }
    }

}