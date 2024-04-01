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

class ItemShopCategory
{
    private item $displayItem;
    private string $categoryName;
    private string $categoryDespcription;
    /**
     * @var ShopItem[]
     */
    private array $items;
    public function __construct(Item $displayItem, string $categoryName, string $categoryDescription, array $items = [])
    {
        $this->displayItem = $displayItem;
        $this->categoryName = $categoryName;
        $this->items = $items;
        $this->categoryDespcription = $categoryDescription;
    }

    /**
     * @return Item
     */
    public function getDisplayItem(): Item
    {
        $item = $this->displayItem;
        $item->setCount(1);
        $item->setCustomName("§c" . $this->categoryName);
        $item->setLore([$this->categoryDespcription]);

        return $item;
    }

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

}