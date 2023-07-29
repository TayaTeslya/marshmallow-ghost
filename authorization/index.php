<?php
include_once dirname(__DIR__) . '/src/core.php'; 

function validation(string $key): string { // проверка валидации формы
    global $showError;
    $showError = false;
    if (!isset($_POST[$key]) || empty($_POST[$key])) {
        $message = 'Поле не заполнено';
        
        $showError = true;
    }
    return $message ?? '';
}

$showMessage = false;
$showError = false;
$messageGlobal = '';
$messages = [
    'login' => '',
    'password' => '',
];

if (isset($_POST['sendForm'])) { // проверка отправки формы
    foreach ($messages as $key => $message) { // проверка валидации каждого поля
        $messages[$key] = validation($key);
    }
    if (!$showError) {
        $user = findUser($_POST['login']); // поиск пользователя в базе данных
        if (!empty($user) && password_verify($_POST['password'], $user['password'])) { // проверка соответствия пароля
            login($user); // авторизация
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
        <div class="input-wrapper">
            <input class="form-input <?=$messages['login'] ? 'error-input' : ''?>" name="login" placeholder="Логин или почта" value="<?=htmlspecialchars($_COOKIE['email']) ?? htmlspecialchars($_POST['login'] ?? '')?>">
            <span class="error"><?=htmlspecialchars($messages['login']) ?? '&nbsp;'?></span>
        </div>
        <div class="input-wrapper">
            <div class="password-field">
                <input id="password-field" class="form-input <?=$messages['password'] ? 'error-input' : ''?>" name="password" type="password" placeholder="Пароль" value="<?=htmlspecialchars($_POST['password'] ?? '')?>">
                <div class="img-visibility">
                    <img id="img-visibility-password" src="/assets/pictures/visibility_on.svg" alt="Видимость пароля">
                </div>
            </div>
            <span class="error"><?=htmlspecialchars($messages['password']) ?? '&nbsp;'?></span>
        </div>
        
        <span class="error global-error"><?=$showMessage ? htmlspecialchars($messageGlobal) : '&nbsp;'?></span>
        <div class="nav-form">
            
            <a href="">Забыли пароль?</a>
            <button name="sendForm" class="button">Войти</button>
        </div>
    </form>
</div>
<script src="/js/set-visibility-password.js"></script>
<?php
includeTemplate('footer.php');
