<?php

namespace Bedwars\game;

use Alias\game\Game;
use Alias\game\GameInformation;
use Alias\game\GameProperties;
use Alias\game\GameType;
use Alias\game\GameVariant;
use Alias\game\maps\Map;
use Alias\game\RankedInformation;
use Alias\game\Team;
use Alias\game\TeamableGame;
use Alias\players\Scoreboard;
use Alias\utils\Utils;
use Bedwars\constants\BedwarsMessages;
use Bedwars\game\events\BedDestroyEvent;
use Bedwars\game\events\DiamondUpgradeEvent;
use Bedwars\game\events\EmeraldUpgradeEvent;
use Bedwars\game\events\Event;
use Bedwars\game\generators\EmeraldGenerator;
use Bedwars\game\generators\Generator;
use Bedwars\game\maps\BedwarsMap;
use Bedwars\game\maps\MythologyMap;
use Bedwars\game\shops\ItemShopCategory;
use Bedwars\game\shops\ShopItem;
use Bedwars\game\shops\Upgrades\BaseUpgrade;
use Bedwars\game\shops\Upgrades\EnemyAlarm;
use Bedwars\game\shops\Upgrades\Forge;
use Bedwars\game\shops\Upgrades\Hast;
use Bedwars\game\shops\Upgrades\HealPool;
use Bedwars\game\shops\Upgrades\Protection;
use Bedwars\game\shops\Upgrades\Sharpness;
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
use pocketmine\world\Position;
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
    /**
     * @var Event[]
     */
    private array $events = [];

    /**
     * @var Generator[]
     */
    private array $generators = [];

    public function init(string $uuid, bool $isRanked): void
    {
        parent::init($uuid, $isRanked);

        $this->initItemShop();
        $this->initTeam();
        $this->initUpgrade();
        $this->initEvent();
        $this->time = time() + 30*60;
    }

    public function initEvent(): void{
        $this->events = [
            new DiamondUpgradeEvent("§bDiamond II", 5),
            new EmeraldUpgradeEvent("§2Emerald III", 10),
            new DiamondUpgradeEvent("§bDiamond III", 15),
            new EmeraldUpgradeEvent("§2Emerald III", 20),
            new BedDestroyEvent(25)
        ];

    }

    public function initUpgrade(): void{
        $this->upgradeShop = [
            "sharpness" => new Sharpness(),
            "protection" => new Protection(),
            "enemy" => new EnemyAlarm(),
            "forge" => new Forge(),
            "healpool" => new HealPool(),
            "hast" => new Hast()
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

    public function onUpdate(): void
    {
        parent::onUpdate();

        $restantTime = $this->time - time();
        foreach ($this->getTeams() as $team){
            if (count($team->getPlayers()) <= 0){
                $team->bedBreak();
            }

            foreach ($team->getGenerators() as $generator){
                $generator->onUpdate();
            }
        }

        foreach ($this->getMap()->getGenerators() as $generator){
            $generator->onUpdate();
        }

        $restantTimeConvert = Utils::getInstance()->convertTime($restantTime);

        $nextEvent = $this->getNextEvent();
        foreach ($this->getAvailablePlayers() as $player){
            $player = $player->getPlayer();
            $team = $this->getPlayerTeam($player);
            $scoreboard = new Scoreboard($player->getName(), "bedwars.scoreboard", "§l§eBEDWARS");
            $scoreboard->setLine(0, TextFormat::GRAY . $this->getUuid());
            $scoreboard->setLine(1, "      ");
            if (!is_null($nextEvent)){
                $eventTimeConvert = Utils::getInstance()->convertTime($nextEvent->getTime() - time());
                $scoreboard->setLine(2, $nextEvent->getName() . "§r§f dans §2" . $eventTimeConvert["minutes"] . ":" . $eventTimeConvert["seconds"]);
            }else{
                $scoreboard->setLine(2, "Fin de la partie dans §2" . $restantTimeConvert["minutes"] . ":" . $restantTimeConvert["seconds"]);
            }
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

        foreach ($this->events as $index => $event){
            if ($event->isTime()){
                $event->execute($this);
                unset($this->events[$index]);
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

    public function getNextEvent(): ?Event{
        $next = null;
        foreach ($this->events as $event){
            if (is_null($next) or ($next->getTime() - time()) > ($event->getTime() - time())){
                $next = $event;
            }
        }
        return $next;
    }

    /**
     * @return array
     */
    public function getGenerators(): array
    {
        return $this->generators;
    }

    public function addGenerator(Generator $generator): void{
        $this->generators[] = $generator;
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
     * @return array
     */
    public function getUpgradeShop(): array
    {
        return $this->upgradeShop;
    }

    public function getType(): GameType
    {
        return GameType::CUSTOM;
    }

    public function getMap(): null|Map|BedwarsMap
    {
        return parent::getMap();
    }

    public function getGameInformation(): GameInformation
    {
        return new GameInformation("bedwars", "Bedwars Classic", "Destroy Team Bed to twin", false, true);
    }

    public function getVariants(): array
    {
        return [
            "solo" => new GameVariant("solo", new GameProperties(PHP_INT_MAX, false, 2, 4), [new MythologyMap()]),
            "duo" => new GameVariant("duo", new GameProperties(PHP_INT_MAX, false, 4, 8), [new MythologyMap()]),
            "squad" => new GameVariant("squad", new GameProperties(PHP_INT_MAX, false, 8, 16), [new MythologyMap()])
        ];
    }
}