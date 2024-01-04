<?php

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

    private string $world_path;

    public function __construct(Map $map) {
        $this->storeLocal("map", $map);
        $this->world_path = BedWars::getInstance()->getDataFolder() . "worlds/" . $map->getName();
    }

    public function onRun(): void {
        Filesystem::recursiveUnlink($this->world_path);
    }

    public function onCompletion(): void { // TODO: Do not reuse code
        $map = $this->fetchLocal("map");

        $plugin = BedWars::getInstance();
        $path = $plugin->getDataFolder() . "maps.json";

        $data = json_decode(file_get_contents($path), true);
        $data = array_filter($data, function(array $map_data) use ($map): bool {
            return $map_data["name"] !== $map->getName();
        });

        file_put_contents($path, json_encode($data, JSON_THROW_ON_ERROR));

        MapFactory::removeMap($map->getId());
    }

}