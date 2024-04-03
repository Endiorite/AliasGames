<?php

namespace Bedwars\game\shops\Upgrades;

use Alias\game\Team;
use Bedwars\game\BedwarsTeam;
use Bedwars\game\teams\TeamUpgrade;

abstract class BaseUpgrade
{

    private string $name;
    protected int $price;
    private string $display_texture;
    public function __construct(string $name, int $price, string $display_texture)
    {
        $this->name = $name;
        $this->price = $price;
        $this->display_texture = $display_texture;
    }

    /**
     * @return string
     */
    public function getDisplayTexture(): string
    {
        return $this->display_texture;
    }

    /**
     * @param BedwarsTeam $team
     * @return int
     */
    public function getPrice(BedwarsTeam $team): int
    {
        return $this->price;
    }

    /**
     * @param Team $team
     * @return string
     */
    public function getName(BedwarsTeam $team): string
    {
        return $this->name;
    }

    abstract public function apply(BedwarsTeam $team);

    abstract public function max(TeamUpgrade $upgrade): bool;
}