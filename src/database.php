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
    $query = $connection->prepare("SELECT * FROM `users` WHERE `email` = ? OR `login` = ? LIMIT 1");
    $query->execute([$login, $login]);
    return $query->fetch(PDO::FETCH_ASSOC) ?: [];
}

function findUserEmail(string $email = ''): bool
{
    $connection = connectToDB();
    $query = $connection->prepare("SELECT `id` FROM `users` WHERE `email` = ? LIMIT 1");
    $query->execute([$email]);
    return (bool) ($query->fetch(PDO::FETCH_ASSOC) ?? false);
}

function findUserLogin(string $login = ''): bool
{
    $connection = connectToDB();
    $query = $connection->prepare("SELECT `id` FROM `users` WHERE `login` = ? LIMIT 1");
    $query->execute([$login]);
    return (bool) ($query->fetch(PDO::FETCH_ASSOC) ?? false);
}

function registration(array $user): int
{
    $connection = connectToDB();
    $query = $connection->prepare("INSERT INTO `users` (`id_path`, `login`, `email`, `password`, `first_name`) VALUES (?, ?, ?, ?, ?)");
    $query->execute([$user['login'], $user['login'], $user['email'], $user['password'], $user['login']]);
    return (int) $connection->lastInsertId();
}
