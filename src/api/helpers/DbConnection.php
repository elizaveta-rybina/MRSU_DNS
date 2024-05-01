<?php

class DbConnection
{
    private static $host = 'mysql';
    private static $dbname = 'dns';
    private static $charset = 'utf8';
    private static $username = 'root';
    private static $password = 'root';

    private static $connection = null;

    private function __construct()
    {
    }

    public static function getConnection()
    {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=" . self::$charset, self::$username, self::$password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
