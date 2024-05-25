<?php

declare(strict_types=1);


namespace sergittos\bedwars\game;


use pocketmine\Server;
use pocketmine\world\World;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\game\map\Map;
use sergittos\bedwars\game\shop\ShopFactory;
use sergittos\bedwars\game\stage\StartingStage;
use sergittos\bedwars\game\stage\WaitingStage;
use sergittos\bedwars\game\task\DirectoryCopyTask;
use sergittos\bedwars\session\Session;
use function count;
use function usort;

class GameManager {

    private int $nextGameId = 0;

    /** @var Game[] */
    private array $games = [];

    public function __construct() {
        ShopFactory::init();
    }

    public function getNextGameId(): int {
        return $this->nextGameId++;
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

    /**
     * @return Game[]
     */
    public function getAvailableGames(Map $map): array {
        $games = [];
        foreach($this->getGamesByMap($map) as $game) {
            $stage = $game->getStage();
            if($stage instanceof WaitingStage or ($stage instanceof StartingStage and !$game->isFull())) {
                $games[] = $game;
            }
        }

        if(empty($games)) {
            return [];
        }

        usort($games, function($a, $b) {
            return $b->getPlayersCount() <=> $a->getPlayersCount();
        });

        return $games;
    }

    public function findGame(Map $map, Session $session): void {
        $games = $this->getAvailableGames($map);
        if(count($games) === 0) {
            $this->generateGame($map, $session);
        } else {
            $games[0]->addPlayer($session);
        }
    }

    private function generateGame(Map $map, Session $session): void {
        $id = $this->getNextGameId();

        Server::getInstance()->getAsyncPool()->submitTask(new DirectoryCopyTask($map->getWorldPath(), $map->createWorldPath($id), function() use ($map, $session, $id): void {
            $this->addGame($game = new Game($map, $id));

            if($session->isSpectator()) {
                $session->getGame()->removeSpectator($session);
            }
            if(!$session->isPlaying()) {
                $game->addPlayer($session);
            }
        }));
    }

    public function addGame(Game $game): void {
        $this->games[$game->getId()] = $game;
    }

    public function removeGame(int $id): void {
        unset($this->games[$id]);
    }

}