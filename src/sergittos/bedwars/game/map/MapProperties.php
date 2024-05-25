<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\map;


use pocketmine\math\Vector3;
use pocketmine\world\World;
use sergittos\bedwars\game\generator\Generator;

trait MapProperties {

    protected string $id;
    protected string $name;

    protected Vector3 $spectatorSpawnPosition;

    protected int $playersPerTeam; // TODO: Make an enum for this
    protected int $maxCapacity; // slots?

    protected World $waitingWorld;

    /** @var Vector3[] */
    protected array $shopPositions;

    /** @var Vector3[] */
    protected array $upgradesPositions;

    /** @var Generator[] */
    protected array $generators;

    public function getId(): string {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getSpectatorSpawnPosition(): Vector3 {
        return $this->spectatorSpawnPosition;
    }

    public function getPlayersPerTeam(): int {
        return $this->playersPerTeam;
    }

    public function getMaxCapacity(): int {
        return $this->maxCapacity;
    }

    public function getWaitingWorld(): World {
        return $this->waitingWorld;
    }

    /**
     * @return Generator[]
     */
    public function getGenerators(): array {
        return $this->generators ?? [];
    }

    /**
     * @return Vector3[]
     */
    public function getShopPositions(): array {
        return $this->shopPositions;
    }

    /**
     * @return Vector3[]
     */
    public function getUpgradesPositions(): array {
        return $this->upgradesPositions;
    }

}