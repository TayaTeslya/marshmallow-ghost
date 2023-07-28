<?php

function isAuthorized(): bool
{
    return (bool) ($_SESSION['auth'] ?? false);
}

function login(array $user): void
{
    $_SESSION['auth'] = true;
    $userSession = [
        'email' => $user['email'],
        'id' => $user['id'],
        'username' => $user['first_name'] . ' ' . $user['last_name'],
    ];
    $_SESSION['user'] = $userSession;
    setcookie('email', $user['email'], [
        'expires' => time() + 3600 * 24 * 30,
    ]);
}

function currentUser(): array
{
    return $_SESSION['user'] ?? [];
}

function rewriteCookie(string $userEmail = ''): void
{
    setcookie('email', $userEmail, [
        'expires' => time() + 3600 * 24 * 30,
    ]);
}

function logout()
{
    $_SESSION['auth'] = false;
    $_SESSION['user'] = [];
}