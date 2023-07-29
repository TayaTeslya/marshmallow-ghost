<?php
include_once dirname(__DIR__) . '/src/core.php';

function validation(string $key): string {
    if (!isset($_POST[$key]) || empty($_POST[$key])) {
        $message = 'Поле не заполнено';
        global $showError;
        $showError = true;
    }
    return $message ?? '';
}
$showMessage = false;
$messageGlobal = '';
$messages = [
    'login' => '',
    'password' => '',
];
$showError = false;
if (isset($_POST['sendForm'])) {
    $showError = false;
    foreach ($messages as $key => $message) {
        $messages[$key] = validation($key);
    }
    if (!$showError) {
        $user = findUser($_POST['login']);
        if (!empty($user) && password_verify($_POST['password'], $user['password'])) {
            login($user);
            header('Location: /');
        } else {
            $messageGlobal = 'Неправильный логин и/или пароль';
            $showMessage = true;
        }
    }
}
?>
<?php
includeTemplate('header.php', ['href' => '/registration/', 'hrefName' => 'Нет аккаунта? Зарегистрируйтесь', 'pageTitle' => 'Авторизация']);
?>
<div class="form-wrapper">
    <form class="form" method="POST">
        <h2 class="title">Авторизация</h2>
        <input class="form-input <?=$messages['login'] ? 'error-input' : ''?>" name="login" placeholder="Логин или почта" value="<?=htmlspecialchars($_POST['login'] ?? '')?>">
        <span class="error"><?=htmlspecialchars($messages['login']) ?? '&nbsp;'?></span>
        <input class="form-input <?=$messages['password'] ? 'error-input' : ''?>" name="password" type="password" placeholder="Пароль" value="<?=htmlspecialchars($_POST['password'] ?? '')?>">
        <span class="error"><?=htmlspecialchars($messages['password']) ?? '&nbsp;'?></span>
        <span class="error global-error"><?=$showMessage ? htmlspecialchars($messageGlobal) : '&nbsp;'?></span>
        <div class="nav-form">
            <a href="">Забыли пароль?</a>
            <button name="sendForm" class="button">Войти</button>
        </div>
    </form>
</div>
<?php
includeTemplate('footer.php');
