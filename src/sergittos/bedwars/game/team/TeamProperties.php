<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\team;


use pocketmine\block\utils\DyeColor;
use pocketmine\math\Vector3;
use sergittos\bedwars\utils\ColorUtils;

trait TeamProperties {

    private string $name;
    private string $color;

    private Vector3 $spawnPoint;
    private Vector3 $bedPosition;

    private Area $zone;
    private Area $claim;

    public function getName(): string {
        return $this->name;
    }

    public function getColor(): string {
        return $this->color;
    }

    public function getDyeColor(): DyeColor {
        return ColorUtils::getDye($this->color);
    }

    public function getSpawnPoint(): Vector3 {
        return $this->spawnPoint;
    }

    public function getBedPosition(): Vector3 {
        return $this->bedPosition;
    }

    public function getZone(): Area {
        return $this->zone;
    }

    public function getClaim(): Area {
        return $this->claim;
    }

}