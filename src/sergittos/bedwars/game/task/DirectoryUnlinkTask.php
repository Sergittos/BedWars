<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\task;


use pocketmine\utils\Filesystem;

class DirectoryUnlinkTask extends DirectoryActionTask {

    protected function execute(array $directories): void {
        foreach($directories as $directory) {
            Filesystem::recursiveUnlink($directory);
        }
    }

}