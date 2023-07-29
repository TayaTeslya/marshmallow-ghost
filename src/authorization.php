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
    setCookieEmail($user['email']);
}

function setCookieEmail(string $email, int $time = 3600 * 24 * 30): void 
{
    setcookie('email', $email, [
        'expires' => time() + $time, // после регистрации email в куках хранится 10 минут, после авторизации - 30 дней
        'path' => '/',
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
        'path' => '/',
    ]);
}

function logout()
{
    $_SESSION['auth'] = false;
    $_SESSION['user'] = [];
}