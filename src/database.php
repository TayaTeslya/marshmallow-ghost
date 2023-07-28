<?php


function connectToDB(): PDO
{
    static $connection = null;
    if ($connection !== null) {
        return $connection;
    }
    $config = loadConfig('database');
    $connection = new PDO(
        "mysql:host={$config['hostname']};dbname={$config['database']};charset=UTF8",
        $config['username'],
        $config['password'],
    );
    return $connection;
}

function findUser(string $login = ''): array
{
    $connection = connectToDB();
    $query = $connection->prepare("SELECT * FROM `users` WHERE `email` = ? OR `login` = ? limit 1");
    $query->execute([$login, $login]);
    return $query->fetch(PDO::FETCH_ASSOC) ?: [];
}
