<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\map;


use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\utils\ServerException;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\game\generator\GeneratorType;
use sergittos\bedwars\game\generator\presets\GoldGenerator;
use sergittos\bedwars\game\generator\presets\IronGenerator;
use sergittos\bedwars\game\team\Area;
use sergittos\bedwars\game\team\Team;
use function array_filter;
use function array_map;
use function file_get_contents;
use function json_decode;
use function strtolower;
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
            foreach($map_data["generators"] as $type => $generator_data) {
                foreach($generator_data as $position) {
                    $generators[] = GeneratorType::toGenerator(self::createVector($position), GeneratorType::fromString($type));
                }
            }

            $shop_locations = self::createPositions($map_data["shop_positions"]);
            $upgrades_locations = self::createPositions($map_data["upgrades_positions"]);

            $teams = [];
            foreach($map_data["teams"] as $data) {
                $generator_data = $data["generator"];
                $areas_data = $data["areas"];
                $bed_data = $data["bed"];

                $team_generators = [];
                $team_generators[] = new IronGenerator(self::createVector($generator_data));
                $team_generators[] = new GoldGenerator(self::createVector($generator_data));

                $teams[] = new Team(
                    ucfirst($data["name"]), $players_per_team,
                    self::createVector($data["spawn_point"]), new Vector3($bed_data["x"], $bed_data["y"] + 1, $bed_data["z"]),
                    Area::fromData($areas_data["zone"]), Area::fromData($areas_data["claim"]), $team_generators
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

    /**
     * @return Map[]
     */
    static public function getMapsByName(string $name): array {
        $maps = [];
        foreach(self::$maps as $map) {
            if(strtolower($map->getName()) === strtolower($name)) {
                $maps[] = $map;
            }
        }
        return $maps;
    }

    static public function getMapByName(string $name): ?Map {
        foreach(self::$maps as $map) {
            if(strtolower($map->getName()) === strtolower($name)) {
                return $map;
            }
        }
        return null;
    }

    static public function getMapById(string $id): ?Map {
        return self::$maps[$id] ?? null;
    }

    static public function addMap(Map $map): void {
        self::$maps[$map->getId()] = $map;
    }

    static public function removeMap(string $id): void {
        unset(self::$maps[$id]);
    }

    static private function createVector(array $data): Vector3 {
        return new Vector3($data["x"] + 0.5, $data["y"] + 1.5, $data["z"] + 0.5);
    }

    static private function createPositions(array $positions): array {
        return array_map(function(array $data) {
            return self::createVector($data);
        }, $positions);
    }

}