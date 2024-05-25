<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\map\task;


use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Filesystem;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\game\map\Map;
use sergittos\bedwars\game\map\MapFactory;
use function file_get_contents;
use function file_put_contents;
use function json_decode;
use function json_encode;

class DeleteMapTask extends AsyncTask {

    private string $worldPath;

    public function __construct(Map $map) {
        $this->storeLocal("map", $map);
        $this->worldPath = BedWars::getInstance()->getDataFolder() . "worlds/" . $map->getName();
    }

    public function onRun(): void {
        Filesystem::recursiveUnlink($this->worldPath);
    }

    public function onCompletion(): void {
        $map = $this->fetchLocal("map");

        MapFactory::destroyMap($map);
        MapFactory::removeMap($map);
    }

}