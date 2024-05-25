<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\stage;


use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\utils\Limits;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\game\event\Event;
use sergittos\bedwars\game\event\presets\UpgradeGeneratorsTierEvent;
use sergittos\bedwars\game\generator\GeneratorType;
use sergittos\bedwars\game\generator\Tier;
use sergittos\bedwars\session\scoreboard\GameScoreboard;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\ColorUtils;
use function count;
use function round;
use function shuffle;

class PlayingStage extends Stage {

    private ?Event $nextEvent = null;

    public function getNextEvent(): Event {
        return $this->nextEvent;
    }

    public function hasStarted(): bool {
        return $this->nextEvent !== null;
    }

    private function startNextEvent(?Event $event = null): void {
        $this->nextEvent = $event ?? $this->nextEvent->getNextEvent();
        $this->nextEvent?->start($this->game);
    }

    protected function onStart(): void {
        $teams = $this->game->getTeams();
        shuffle($teams);

        foreach($teams as $team) {
            foreach($this->game->getPlayers() as $session) {
                if(!$team->isFull() and !$session->hasTeam()) {
                    $team->addMember($session);

                    if($team->isFull()) {
                        continue 2;
                    }
                }
            }
            if(!$team->isAlive()) {
                $team->destroyBed($this->game, true, true);
            }
        }

        $this->startNextEvent(new UpgradeGeneratorsTierEvent(GeneratorType::DIAMOND, Tier::II));
    }

    public function onJoin(Session $session): void {
        $session->setScoreboard(new GameScoreboard());
    }

    public function onQuit(Session $session): void {
        $session->title("{RED}GAME OVER!");
        $session->resetSettings();
        $session->setTrackingSession(null);

        if($session->hasTeam()) {
            $team = $session->getTeam();
            $team->removeMember($session);
            if(!$team->isAlive()) {
                $this->game->broadcastMessage("{BOLD}{WHITE}TEAM ELIMINATED > {RESET}" . $team->getColoredName() . " Team {RED}has been eliminated!");
            }
        }

        $this->game->despawnGeneratorsFrom($session);

        if(count($this->game->getAliveTeams()) === 1) {
            $this->game->setStage(new EndingStage());
        }
    }

    public function tick(): void {
        if($this->nextEvent->hasEnded()) {
            $this->startNextEvent();
        }
        $this->game->updateScoreboards();
        $this->tickPlayersAndSpectators();
    }

    private function tickPlayersAndSpectators(): void {
        foreach($this->game->getPlayers() as $session) {
            $this->checkTrackingSession($session);

            if($session->isRespawning()) {
                $session->attemptToRespawn();
            }

            if(!$session->hasTeam()) {
                continue;
            }

            $team = $session->getTeam();
            if($team->getUpgrades()->getHealPool()->canLevelUp()) {
                continue;
            }

            if($team->getZone()->isInside($session->getPlayer()->getPosition())) {
                $session->addEffect(new EffectInstance(VanillaEffects::REGENERATION(), Limits::INT32_MAX, 0, false));
            } else {
                $session->getPlayer()->getEffects()->remove(VanillaEffects::REGENERATION());
            }
        }

        foreach($this->game->getSpectators() as $session) {
            $this->checkTrackingSession($session);
        }
    }

    private function checkTrackingSession(Session $session): void {
        $trackingSession = $session->getTrackingSession();
        if($trackingSession === null) {
            return;
        }

        $player = $session->getPlayer();
        if($trackingSession->isRespawning()) {
            $player->sendPopup(TextFormat::RED . "Target lost");
            $session->setTrackingSession(null);
            return;
        }
        $session->updateCompassDirection();

        $position = $trackingSession->getPlayer()->getPosition();
        $distance = $player->getPosition()->distance($position);

        $player->sendPopup(ColorUtils::translate(
            "Target: {GREEN}{BOLD}" . $trackingSession->getUsername() . "  {RESET}{WHITE}Distance: {GREEN}{BOLD}" . round($distance, 1) . "m"
        ));

        if($session->isSpectator() and $session->getSpectatorSettings()->getAutoTeleport() and $distance >= 10) {
            $player->teleport($position);
        }
    }

}
