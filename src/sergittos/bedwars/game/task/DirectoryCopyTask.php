<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\game\task;


use Closure;
use pocketmine\utils\Filesystem;

class DirectoryCopyTask extends DirectoryActionTask {

    public function __construct(string $origin, string $destination, Closure $onCompletion) {
        parent::__construct([$origin => $destination], $onCompletion);
    }

    protected function execute(array $directories): void {
        foreach($directories as $origin => $destination) {
            Filesystem::recursiveCopy($origin, $destination);
        }
    }

}