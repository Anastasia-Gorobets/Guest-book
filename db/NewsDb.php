<?php
require_once 'Core.php';

class NewsDb
{
    private $core;

    public function __construct()
    {
        $this->core = Core::getInstance();
    }

    public function insertNews($username, $email, $homepage, $text, $ip, $browser, $date, $pathToTxtFile, $pathToImageFile)
    {
        $res = false;
        try {
            $stmt = $this->core->dbh->prepare('INSERT INTO news (username,email,homePage,text,ip,browser,date,pathToTxtFile,
        pathToImageFile) VALUES (?, ?,?,?,?,?,?,?,?)');
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $homepage);
            $stmt->bindParam(4, $text);
            $stmt->bindParam(5, $ip);
            $stmt->bindParam(6, $browser);
            $stmt->bindParam(7, $date);
            $stmt->bindParam(8, $pathToTxtFile);
            $stmt->bindParam(9, $pathToImageFile);
            $res = $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $res;

    }

    public function getAllNews()
    {
        $stmt = $this->core->dbh->query('SELECT * FROM news');
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $records = $stmt->fetchAll(PDO::FETCH_OBJ);
        if (!$records) {
            return null;
        } else return $records;
    }
}