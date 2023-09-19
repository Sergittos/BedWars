<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\map;


use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\utils\ServerException;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\game\generator\Generator;
use sergittos\bedwars\game\team\Area;
use sergittos\bedwars\game\team\Team;
use function array_filter;
use function file_get_contents;
use function json_decode;
use function strtoupper;
use function ucfirst;

class MapFactory {

    /** @var Map[] */
    static private array $maps = [];

    static public function init(): void {
        foreach(json_decode(file_get_contents(BedWars::getInstance()->getDataFolder() . "maps.json"), true) as $map_data) {
            $name = $map_data["name"];
            $world_name = $map_data["waiting_world"];
            $position_data = $map_data["spectator_spawn_position"];
            $players_per_team = $map_data["players_per_team"];
            $capacity = $map_data["max_capacity"];

            $world_manager = Server::getInstance()->getWorldManager();
            if(!$world_manager->loadWorld($world_name)) {
                throw new ServerException("Couldn't load " . $name . " map because the world doesn't exist. (" . $world_name . ")");
            }

            $waiting_world = $world_manager->getWorldByName($world_name);
            $spectator_spawn_position = new Vector3($position_data["x"], $position_data["y"], $position_data["z"]);

            $generators = [];
            foreach($map_data["generators"] as $id => $generator_data) {
                foreach($generator_data["positions"] as $positions_data) {
                    $generators[] = Generator::fromData($id, $id, $generator_data["speed"], StringToItemParser::getInstance()->parse($id), true, $positions_data);
                }
            }

            $shop_locations = [];
            $upgrades_locations = [];

            $teams = [];
            foreach($map_data["teams"] as $team_name => $data) {
                $generator_data = $data["generator"];
                $areas_data = $data["areas"];
                $bed_data = $data["bed"];

                $team_generators = [];
                $team_generators[] = Generator::fromData(Generator::IRON, $team_name, 1, VanillaItems::IRON_INGOT(), false, $generator_data);
                $team_generators[] = Generator::fromData(Generator::GOLD, $team_name, 5, VanillaItems::GOLD_INGOT(), false, $generator_data);

                $shop_locations[] = self::createVector($data["shop"]);
                $upgrades_locations[] = self::createVector($data["upgrades"]);

                $teams[] = new Team(
                    ucfirst($team_name), "{" . strtoupper($team_name) . "}", $players_per_team,
                    self::createVector($data["spawn_point"]), new Vector3($bed_data["x"], $bed_data["y"], $bed_data["z"]),
                    Area::fromData($areas_data["zone"]), Area::fromData($areas_data["zone"]), $team_generators // todo: change to claim
                );
            }

            self::addMap(new Map(
                $name, $spectator_spawn_position, $players_per_team,
                $capacity, $waiting_world, $generators, $teams,
                $shop_locations, $upgrades_locations
            ));
        }
    }

    /**
     * @return Map[]
     */
    static public function getMaps(): array {
        return self::$maps;
    }

    /**
     * @return Map[]
     */
    static public function getMapsByPlayers(int $players_per_team): array {
        return array_filter(self::$maps, function(Map $map) use ($players_per_team) {
            return $map->getPlayersPerTeam() === $players_per_team;
        });
    }

    static public function getMap(string $id): ?Map {
        return self::$maps[$id] ?? null;
    }

    static private function addMap(Map $map): void {
        self::$maps[$map->getId()] = $map;
    }

    static private function createVector(array $data): Vector3 {
        return new Vector3($data["x"] + 0.5, $data["y"] + 0.5, $data["z"] + 0.5);
    }

}