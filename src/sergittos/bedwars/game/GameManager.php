<?php

declare(strict_types=1);


namespace sergittos\bedwars\game;


use pocketmine\Server;
use pocketmine\world\World;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\game\map\Map;
use sergittos\bedwars\game\map\MapFactory;
use sergittos\bedwars\game\shop\ShopFactory;
use sergittos\bedwars\game\stage\StartingStage;
use sergittos\bedwars\game\stage\WaitingStage;
use sergittos\bedwars\game\task\GenerateGameTask;
use function array_rand;
use function count;

class GameManager {

    private int $next_game_id = 0;

    /** @var Game[] */
    private array $games = [];

    public function __construct() {
        ShopFactory::init();
        foreach(MapFactory::getMaps() as $map) { // Generate 3 games per map by default
            $this->generateGames($map);
        }
    }

    public function getNextGameId(): int {
        return $this->next_game_id++;
    }

    /**
     * @return Game[]
     */
    public function getGames(): array {
        return $this->games;
    }

    public function getGamesCount(Map $map): int {
        return count($this->getGamesByMap($map));
    }

    /**
     * @return Game[]
     */
    public function getGamesByMap(Map $map): array {
        $games = [];
        foreach($this->games as $game) {
            if($game->getMap()->getId() === $map->getId()) {
                $games[] = $game;
            }
        }
        return $games;
    }

    public function getGameByWorld(World $world): ?Game {
        foreach(BedWars::getInstance()->getGameManager()->getGames() as $game) {
            if($game->getWorld() === $world) {
                return $game;
            }
        }
        return null;
    }

    public function findRandomGame(int $players_per_team): ?Game {
        $maps = MapFactory::getMapsByPlayers($players_per_team);
        if($maps !== []) {
            $map = $maps[array_rand($maps)];

            return $this->findGame($map);
        }
        return null;
    }

    public function findGame(Map $map): ?Game {
        $games = [];
        foreach($this->getGamesByMap($map) as $game) {
            $stage = $game->getStage();
            if($stage instanceof WaitingStage or ($stage instanceof StartingStage and !$game->isFull())) {
                $games[] = $game;
            }
        }

        if(empty($games)) {
            $this->generateGames($map);
            return null;
        }

        $found = null;
        $index = PHP_INT_MIN;
        foreach($games as $game) {
            $count = $game->getPlayersCount();
            if($count > $index) {
                $index = $count;
                $found = $game;
            }
        }
        return $found;
    }

    public function generateGames(Map $map): void {
        for($i = 0; $i < 3; ++$i) {
            Server::getInstance()->getAsyncPool()->submitTask(new GenerateGameTask(
                $this->getNextGameId(), $map
            ));
        }
    }

    public function addGame(Game $game): void {
        $this->games[$game->getId()] = $game;
    }

    public function removeGame(int $id): void {
        unset($this->games[$id]);
    }

}