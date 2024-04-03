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

namespace Bedwars\game\teams;

class TeamUpgrade
{

    private bool $healPool = false;
    private int $forgeLevel = 1;
    private int $protectionArmor = 0;
    private int $armorLevel = 0;
    private int $hast = 0;
    private int $sharpness = 0;

    /**
     * @return int
     */
    public function getSharpness(): int
    {
        return $this->sharpness;
    }

    public function upgradeHast(): void{
        $this->hast++;
    }

    /**
     * @return void
     */
    public function upgradeforge(): void
    {
        $this->forgeLevel++;
    }

    /**
     * @return void
     */
    public function upgradeProtectionArmor(): void
    {
        $this->protectionArmor++;
    }

    /**
     * @return void
     */
    public function upgradeSharpness(): void
    {
        $this->sharpness++;
    }

    /**
     * @return int
     */
    public function getArmorLevel(): int
    {
        return $this->armorLevel;
    }

    /**
     * @param int $armorLevel
     */
    public function setArmorLevel(int $armorLevel): void
    {
        $this->armorLevel = $armorLevel;
    }

    /**
     * @param int $hast
     */
    public function setHast(int $hast): void
    {
        $this->hast = $hast;
    }

    /**
     * @return int
     */
    public function getHast(): int
    {
        return $this->hast;
    }

    /**
     * @return int
     */
    public function getForgeLevel(): int
    {
        return $this->forgeLevel;
    }

    /**
     * @return int
     */
    public function getProtectionArmor(): int
    {
        return $this->protectionArmor;
    }
    private bool $enemyAlarm = false;

    /**
     * @param bool $enemyAlarm
     */
    public function setEnemyAlarm(bool $enemyAlarm): void
    {
        $this->enemyAlarm = $enemyAlarm;
    }

    /**
     * @return bool
     */
    public function hasEnemyAlarm(): bool
    {
        return $this->enemyAlarm;
    }

    /**
     * @param bool $healPool
     */
    public function setHealPool(bool $healPool): void
    {
        $this->healPool = $healPool;
    }

    /**
     * @return bool
     */
    public function hasHealPool(): bool
    {
        return $this->healPool;
    }
}