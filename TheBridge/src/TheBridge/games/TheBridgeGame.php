<?php

namespace TheBridge\games;

use Alias\exceptions\BehaviorAlreadyExistsException;
use Alias\game\GameInformation;
use Alias\game\GameProperties;
use Alias\game\GameVariant;
use Alias\game\RankedInformation;
use TheBridge\games\behaviors\SecondHandBehavior;

class TheBridgeGame extends \Alias\game\TeamableGame
{

    /**
     * @throws BehaviorAlreadyExistsException
     */
    public function init(string $uuid, bool $isRanked): void
    {
        parent::init($uuid, $isRanked);
        $this->addBehavior(new SecondHandBehavior());
    }

    public function onUpdate(): void{}

    public function getVariants(): array
    {
        return [
            "solo" => new GameVariant("solo", new GameProperties(PHP_INT_MAX, false, 2, 2, 1), [

            ])
        ];
    }

    public function getGameInformation(): GameInformation
    {
        return new GameInformation("thebridge", "TheBridge", "TheBridge", false, true);
    }

    /**
     * @inheritDoc
     */
    public function getRankedInformation(): ?RankedInformation
    {
        return new RankedInformation();
    }
}