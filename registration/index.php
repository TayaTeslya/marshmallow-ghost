<?php
include_once dirname(__DIR__) . '/src/core.php';

function validation(): string {
    if (!isset($_POST['email']) || empty($_POST['email'])) {
        $message = 'Введите email';
    } elseif (!isset($_POST['login']) || empty($_POST['login'])) {
        $message = 'Введите логин';
    } elseif (strlen($_POST['login']) < 4) {
        $message = 'Минимальная длина логина 4 символа';
    } elseif (strlen($_POST['login']) > 30) {
        $message = 'Максимальная длина логина 30 символов';
    } elseif (!isset($_POST['password']) || empty($_POST['password'])) {
        $message = 'Введите пароль';
    } elseif (strlen($_POST['password']) < 6) {
        $message = 'Минимальная длина пароля 6 символов';
    } elseif (strlen($_POST['password']) > 40) {
        $message = 'Максимальная длина пароля 40 символов';
    } elseif (!isset($_POST['confirm_password']) || empty($_POST['confirm_password'])) {
        $message = 'Подтвердите пароль';
    } elseif ($_POST['password'] !== $_POST['confirm_password']) {
        $message = 'Пароли не совпадают';
    }
    return $message ?? '';
}

$showMessage = false;
$message = '';
if (isset($_POST['sendForm'])) {
    $message = validation();
    if (empty($message)) {
        $user = findUser($_POST['login']);
        if (empty($user)) {
            // header('Location: /');
        } else {
            $message = 'Данный логин уже занят';
            $showMessage = true;
        }
    } else {
        $showMessage = true;
    }
}
?>
<?php
includeTemplate('header.php', ['href' => '/authorization/', 'hrefName' => 'Уже есть аккаунт? Авторизуйтесь', 'pageTitle' => 'Регистрация']);
?>
<div class="form-wrapper">
    <form class="form" method="POST">
        <h2 class="title">Регистрация</h2>
        <input name="email" placeholder="Почта" value="<?=htmlspecialchars($_POST['email'] ?? '')?>"></input>
        <input name="login" placeholder="Логин" value="<?=htmlspecialchars($_POST['login'] ?? '')?>"></input>
        <input name="password" type="password" placeholder="Пароль" value="<?=htmlspecialchars($_POST['password'] ?? '')?>"></input>
        <input name="password" type="confirm_password" placeholder="Подтвердите пароль" value="<?=htmlspecialchars($_POST['confirm_password'] ?? '')?>"></input>
        <span class="error"><?=$showMessage ? htmlspecialchars($message) : '&nbsp;'?></span>
        <div class="nav-form nav-form-registration">
            <button name="sendForm" class="button">Создать аккаунт</button>
        </div>
    </form>
</div>
<?php
includeTemplate('footer.php');