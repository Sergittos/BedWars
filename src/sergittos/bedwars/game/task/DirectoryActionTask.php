<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\game\task;


use Closure;
use pocketmine\scheduler\AsyncTask;

abstract class DirectoryActionTask extends AsyncTask {

    private string $directories;

    public function __construct(array $directories, Closure $onCompletion) {
        $this->directories = json_encode($directories);
        $this->storeLocal("onCompletion", $onCompletion);
    }

    public function onRun(): void {
        $this->execute(json_decode($this->directories, true));
    }

    public function onCompletion(): void {
        $callback = $this->fetchLocal("onCompletion");
        $callback();
    }

    abstract protected function execute(array $directories): void;

}