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

use pocketmine\item\Item;

class ShopItem
{

    const GOLD = 0;
    const IRON = 1;
    const EMERALD = 2;
    const DIAMOND = 3;

    private Item $item;
    private int $price;
    private int $type;
    private string $description = "";

    public function __construct(Item $item, int $price, int $type, string $description = "")
    {
        $this->item = $item;
        $this->price = $price;
        $this->type = $type;
        $this->description = $description;
    }

    /**
     * @return Item
     */
    public function getItem(int $count): Item
    {
        $item = $this->item;
        $item->setCustomName("§2" . $item->getName());
        $price = match ($this->type){
            self::GOLD => "§6" . $this->price . " gold",
            self::IRON => "§f" . $this->price . " iron",
            self::EMERALD => "§2" . $this->price . " emerald",
            self::DIAMOND => "§b" . $this->price . " diamond"
        };

        $canBuy = match (true){
            $count >= $this->price => "§2You can buy this item !",
            $count < $this->price => "§cYou can't buy this item !"
        };
        $item->setLore([
            "§7Price:" . $this->price,
            "     ",
            $this->description,
            "      ",
            $canBuy
        ]);

        $nbt = $item->getNamedTag();
        $nbt->setInt("itemType", $this->type);
        $nbt->setInt("itemShop", $this->price);
        $item->setNamedTag($nbt);

        return $item;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

}