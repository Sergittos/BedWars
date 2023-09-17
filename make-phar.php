<?php

// https://github.com/dresnite/skyblock/blob/stable/build.php

$output = "BedWars.phar";

if(is_file($output)) {
    unlink($output);
}

$phar = new Phar($output);
$phar->startBuffering();
$phar->buildFromDirectory(__DIR__);
$phar->stopBuffering();

echo "BedWars phar file has been built";