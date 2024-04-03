<?php

namespace Bedwars\game;

use Alias\game\Game;
use Alias\game\GameInformation;
use Alias\game\GameVariant;
use Alias\game\RankedInformation;
use Alias\game\Team;
use Alias\game\TeamableGame;
use Alias\players\Scoreboard;
use Alias\utils\Utils;
use Bedwars\constants\BedwarsMessages;
use Bedwars\game\maps\MythologyMap;
use Bedwars\game\shops\ItemShopCategory;
use Bedwars\game\shops\ShopItem;
use Bedwars\game\shops\Upgrades\BaseUpgrade;
use Bedwars\game\shops\Upgrades\EnemyAlarm;
use Bedwars\game\teams\BlueBedwars;
use Bedwars\game\teams\GreenTeam;
use Bedwars\game\teams\RedTeam;
use Bedwars\game\teams\YellowTeam;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\PotionType;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\World;

class BedwarsGame extends TeamableGame
{

    private int $time;
    private bool $instantDeath = false;
    /**
     * @var ItemShopCategory[]
     */
    private array $itemShop = [];
    /**
     * @var BaseUpgrade[]
     */
    private array $upgradeShop = [];
    public function init(string $uuid, bool $isRanked): void
    {
        parent::init($uuid, $isRanked);

        $this->initItemShop();
        $this->initTeam();
        $this->time = time() + 15*60;
    }

    public function initUpgrade(): void{
        $this->upgradeShop = [
            "enemy" => new EnemyAlarm(),
            "sharpness" => new Sharpness(),
        ];
    }

    public function initTeam(): void{
        if ($this->getVariants())
        $this->addTeam(new RedTeam(2));
        $this->addTeam(new BlueBedwars(2));
        $this->addTeam(new GreenTeam(2));
        $this->addTeam(new YellowTeam(2));
    }

    public function initItemShop(): void{
        $wool = VanillaBlocks::WOOL()->asItem();
        $this->itemShop["blocks"] = new ItemShopCategory($wool, "Blocks", "All block to protect your bed", [
            new ShopItem($wool->setCount(16), 4, ShopItem::IRON),
            new ShopItem(VanillaBlocks::SANDSTONE()->asItem()->setCount(16), 12, ShopItem::IRON),
            new ShopItem(VanillaBlocks::END_STONE()->asItem()->setCount(12), 24, ShopItem::IRON),
            new ShopItem(VanillaBlocks::OAK_WOOD()->asItem()->setCount(16), 4, ShopItem::GOLD),
            new ShopItem(VanillaBlocks::OBSIDIAN()->asItem()->setCount(4), 4, ShopItem::EMERALD),
        ]);

        $stick = VanillaItems::STICK()->setCount(1);
        $stick->addEnchantment(new EnchantmentInstance(VanillaEnchantments::KNOCKBACK()));
        $this->itemShop["armor"] = new ItemShopCategory(VanillaItems::STONE_SWORD(), "Armor", "Get the best protection !", [
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
        $this->itemShop["ranged"] = new ItemShopCategory(VanillaItems::ARROW(), "Ranged", "Make flick en clip your opponent !", [
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

        $this->itemShop["tools"] = new ItemShopCategory(VanillaItems::WOODEN_PICKAXE(), "Tools", "Best tools to destroy opponent bed !", [
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

        $this->itemShop["potions"] = new ItemShopCategory($invisibility, "potions", "Best Potions !", [
            new ShopItem($speed, 1, ShopItem::EMERALD),
            new ShopItem($jump, 1, ShopItem::EMERALD),
            new ShopItem($invisibility, 1, ShopItem::EMERALD),
        ]);

        $this->itemShop["utils"] = new ItemShopCategory(VanillaItems::ENDER_PEARL(), "utils", "Best utils item to help you !", [
            new ShopItem(VanillaItems::ENDER_PEARL()->setCount(1), 4, ShopItem::EMERALD),
            new ShopItem(VanillaItems::FIRE_CHARGE()->setCount(1), 50, ShopItem::IRON),
            new ShopItem(VanillaBlocks::TNT()->asItem()->setCount(1), 8, ShopItem::GOLD),
            new ShopItem(VanillaItems::WATER_BUCKET(), 1, ShopItem::EMERALD)
        ]);
    }

    public function getWorld(): ?World{
        return Server::getInstance()->getWorldManager()->getWorldByName($this->getWorldName());
    }

    public function onUpdate(): void
    {
        $restantTime = $this->time - time();

        foreach ($this->getTeams() as $team){
            if (count($team->getPlayers()) <= 0){
                $team->bedBreak();
            }
        }

        $time = Utils::getInstance()->convertTime($restantTime);

        foreach ($this->getAvailablePlayers() as $player){
            $player = $player->getPlayer();
            $team = $this->getPlayerTeam($player);
            $scoreboard = new Scoreboard($player->getName(), "bedwars.scoreboard", "§l§eBEDWARS");
            $scoreboard->setLine(0, TextFormat::GRAY . $this->getUuid());
            $scoreboard->setLine(1, "      ");
            $scoreboard->setLine(2, "Mort Subite dans §2" . $time["minutes"] . ":" . $time["seconds"]);
            $scoreboard->setLine(3, "      ");
            $line = 4;
            foreach ($this->getTeams() as $team){
                $restantPlayerCount = count($team->getRestantPlayers());
                $icon = match (true){
                    !$team->isBedDestroy() => '',
                    $team->isBedDestroy() && $restantPlayerCount > 0 => "2" . count($team->getRestantPlayers()),
                    default => ''
                };

                $you = match ($team->inTeam($player)){
                    default => " §7YOU"
                };
                $scoreboard->setLine($line, $team->getName() . "§r§f: " . $icon . $you);
                $line++;
            }

            $scoreboard->setLine($line+1, "   ");
            $scoreboard->setLine($line+2, "Bed Destroyed: §c" . $team->getTeamDestroy());
            $scoreboard->setLine($line+3, "   ");
            $scoreboard->setLine($line+4, "§6alias.net");

            $scoreboard->show();
        }

        if ($restantTime <= 0 && !$this->instantDeath){
            $this->broadcastMessage(BedwarsMessages::BREAK_ALL_BED);
            $this->instantDeath = true;

            foreach ($this->getTeams() as $team){
                $team->bedBreak();
            }
        }
    }

    public function onBlockBreak(BlockBreakEvent $event): void
    {
        $block = $event->getBlock();
        $position = $block->getPosition();
        $player = $event->getPlayer();

        $playerTeam = $this->getPlayerTeam($player);
        if (is_null($playerTeam)) return;

        foreach ($this->getTeams() as $team){
            if ($team->getBedPosition()->equals($position->asVector3())){
                if ($team->inTeam($player->getName())){
                    $event->cancel();
                    $player->sendMessage(BedwarsMessages::CANT_BREAK_YOUR_BED);
                }else{
                    $this->broadcastMessage(BedwarsMessages::BED_BREAK, ["{player}", "{team}"], [$player->getName(), $team->getName()]);
                    $team->bedBreak();

                    $playerTeam->addTeamDestroy();
                }
            }
        }
    }

    public function onRespawn(PlayerRespawnEvent $event): void
    {
        parent::onRespawn($event);
        $player = $event->getPlayer();
        $team = $this->getPlayerTeam($player);
        $upgrade = $team->getTeamUpgrade();

        $sword = VanillaItems::WOODEN_SWORD();
        if ($upgrade->getSharpness() > 0){
            $sword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), $upgrade->getSharpness()));
        }

        $player->getInventory()->addItem($sword);
        $helmet = VanillaItems::LEATHER_CAP()->setCustomColor($team->getDyeColor());
        $chestplate = VanillaItems::LEATHER_TUNIC()->setCustomColor($team->getDyeColor());
        $leggings = VanillaItems::LEATHER_PANTS()->setCustomColor($team->getDyeColor());
        $boots = VanillaItems::LEATHER_BOOTS()->setCustomColor($team->getDyeColor());

        if ($upgrade->getProtectionArmor() > 0){
            $enchant = new EnchantmentInstance(VanillaEnchantments::PROTECTION(), $upgrade->getProtectionArmor());
            $helmet->addEnchantment($enchant);
            $chestplate->addEnchantment($enchant);
            $leggings->addEnchantment($enchant);
            $boots->addEnchantment($enchant);
        }
        $player->getArmorInventory()->setHelmet($helmet);
        $player->getArmorInventory()->setChestplate($chestplate);
        $player->getArmorInventory()->setLeggings($leggings);
        $player->getArmorInventory()->setBoots($boots);

    }

    public function onDeath(PlayerDeathEvent $event): void
    {
        parent::onDeath($event);

        $player = $event->getPlayer();
        $playerGame = $this->getPlayerGame($player->getName());
        $team = $this->getPlayerTeam($player);

        if (!$team->canRespawn()){
            $playerGame->setLife(0);
        }

        if (count($team->getRestantPlayers()) <= 0){
            $this->broadcastMessage(str_replace("{team}", $team->getName(), BedwarsMessages::TEAM_ELIMINATED));
        }
    }

    public function getPlayerTeam(Player $player): BedwarsTeam|Team|null
    {
        return parent::getPlayerTeam($player);
    }

    /**
     * @return BedwarsTeam[]
     */
    public function getTeams(): array
    {
        return parent::getTeams(); // TODO: Change the autogenerated stub
    }

    public function getRankedInformation(): ?RankedInformation
    {
        return new RankedInformation();
    }

    /**
     * @return array
     */
    public function getItemShop(): array
    {
        return $this->itemShop;
    }

    /**
     * @inheritDoc
     */
    public function getMaps(): array
    {
        return [
            new MythologyMap()
        ];
    }

    /**
     * @return array
     */
    public function getUpgradeShop(): array
    {
        return $this->upgradeShop;
    }

    public function getType(): int
    {
        return Game::TYPE_CUSTOM;
    }

    public function getVariants(): array
    {
        return [
            "solo" => new GameVariant("solo", new GameInformation("bedwars", PHP_INT_MAX, 2, 4)),
            "duo" => new GameVariant("duo", new GameInformation("bedwars", PHP_INT_MAX, 4, 8)),
            "squad" => new GameVariant("squad", new GameInformation("bedwars", PHP_INT_MAX, 8, 16))
        ];
    }
}