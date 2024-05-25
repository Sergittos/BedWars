<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\provider\mysql;


use mysqli;
use pmmp\thread\ThreadSafe;

class MysqlCredentials extends ThreadSafe {

    private string $hostname, $username, $password, $database;
    private int $port;

    public function __construct(string $hostname, string $username, string $password, string $database, int $port) {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->port = $port;
    }

    static public function fromData(array $data): MysqlCredentials {
        return new MysqlCredentials($data["hostname"], $data["username"], $data["password"], $data["database"], $data["port"]);
    }

    public function getMysqli(): mysqli {
        return new mysqli($this->hostname, $this->username, $this->password, $this->database, $this->port);
    }

}