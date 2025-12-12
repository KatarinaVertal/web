<?php
// app/core/database.php

require_once __DIR__ . '/../config/config.php';

class Database {
    private $pdo;

    public function __construct() {
        $config = require __DIR__ . '/../config/config.php';

        $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";

        $this->pdo = new PDO($dsn, $config['user'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }



}