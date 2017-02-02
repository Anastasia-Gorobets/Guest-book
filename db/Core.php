<?php
require_once 'Config.php';

class Core
{
    public $dbh; // handle of the db connexion
    private static $instance;

    private function __construct()
    {
        // building data source name from config
        $dsn = 'mysql:host=' . Config::read('db.host') .
            ';dbname=' . Config::read('db.basename') .
            ';connect_timeout=15';
        // getting DB user from config
        $user = Config::read('db.user');
        // getting DB password from config
        $password = Config::read('db.password');
        $this->dbh = new PDO($dsn, $user, $password);
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Core();
        }
        return self::$instance;
    }
}