<?php
include_once dirname(__DIR__) . '/src/core.php';

function validation(): string {
    if (!isset($_POST['login']) || empty($_POST['login'])) {
        $message = 'Введите логин';
    } elseif (!isset($_POST['password']) || empty($_POST['password'])) {
        $message = 'Введите пароль';
    }
    return $message ?? '';
}

$showMessage = false;
$message = '';
if (isset($_POST['sendForm'])) {
    $message = validation();
    if (empty($message)) {
        $user = findUser($_POST['login']);
        if (!empty($user) && password_verify($_POST['password'], $user['password'])) {
            login($user);
            header('Location: /');
        } else {
            $message = 'Неправильный логин и/или пароль';
            $showMessage = true;
        }
    } else {
        $showMessage = true;
    }
}
?>
<?php
includeTemplate('header.php', ['href' => '/registration/', 'hrefName' => 'Нет аккаунта? Зарегистрируйтесь', 'pageTitle' => 'Авторизация']);
?>
<div class="form-wrapper">
    <form class="form" method="POST">
        <h2 class="title">Авторизация</h2>
        <input name="login" placeholder="Логин или почта" value="<?=htmlspecialchars($_POST['login'] ?? '')?>"></input>
        <input name="password" type="password" placeholder="Пароль" value="<?=htmlspecialchars($_POST['password'] ?? '')?>"></input>
        <span class="error"><?=$showMessage ? htmlspecialchars($message) : '&nbsp;'?></span>
        <div class="nav-form">
            <a href="">Забыли пароль?</a>
            <button name="sendForm" class="button">Войти</button>
        </div>
    </form>
</div>
<?php
includeTemplate('footer.php');
