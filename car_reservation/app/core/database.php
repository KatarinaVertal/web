<?php
// app/core/database.php

require_once __DIR__ . '/../config/config.php';

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = 'pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;

            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                // ak sa nevie pripojiť k DB, zobrazí chybovú hlášku
                die('Chyba pripojenia k databáze: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
