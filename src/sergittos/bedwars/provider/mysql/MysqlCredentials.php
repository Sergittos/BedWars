<?php

declare(strict_types=1);


namespace sergittos\bedwars\provider\mysql;


use mysqli;

class MysqlCredentials {

    private string $hostname, $username, $password, $database;

    public function __construct(string $hostname, string $username, string $password, string $database) {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    static public function fromData(array $data): MysqlCredentials {
        return new MysqlCredentials($data["hostname"], $data["username"], $data["password"], $data["database"]);
    }

    public function getHostname(): string {
        return $this->hostname;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getDatabase(): string {
        return $this->database;
    }

    public function getMysqli(): mysqli {
        return new mysqli($this->hostname, $this->username, $this->password, $this->database);
    }

}