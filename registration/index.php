<?php
include_once dirname(__DIR__) . '/src/core.php';

if (isAuthorized()) {
    header('Location: /');
}

function formatChars(int $count): string // формат вывода слова "символ"
{
    $count = $count % 100;
    if ($count >= 5 && $count <= 20) {
        return 'символов';
    } elseif ($count % 10 == 1) {
        return 'символ';
    } elseif ($count % 10 >= 2 && $count % 10 <= 4) {
        return 'символа';
    } else {
        return 'символов';
    }
}

// if (/[А-Яа-я!@#$%^&*()+\-=\[\]{};':"\\|,.<>\/?]/g.test(loginValue)) { 
//     error.textContent = 'Логин может содержать только латинские буквы, цифры и символ нижнего подчеркивания';
//     return;
// }

// if (/[!@#$%^&*()+\-=\[\]{};':"\\|,.<>\/?]/g.test(usernameValue)) { 
//     error.textContent = 'Имя может содержать только буквы, цифры, символ нижнего подчеркивания и пробел';
//     return;
// }
// if (/[&()+=\[\]{};':"\\|<>\/?]/g.test(passwordValue)) { 
//     error.textContent = 'Пароль не может содержать символы "( ) { } ? + < > [ ] = ; " \' : & | / \\"';
//     return;
// }
// if (!/[!@#$%^*_,.\-]/g.test(passwordValue)) {
//     error.textContent = 'Пароль должен содержать хотя-бы один спец-символ "! @ # $ % ^ * - _ , ."';
//     return;
// }

function validation(string $key, int $minCount = 0, int $maxCount = 0, string $lockChars = '', string $requiredChars = ''): string // проверка валидации формы
{
    global $showError;
    $showError = false;
    if (!isset($_POST[$key]) || empty($_POST[$key])) { // проверка заполнения поляЯ
        $message = 'Поле не заполнено';
        $showError = true;
    } elseif ($minCount !== 0 && strlen($_POST[$key]) < $minCount) { // проверка минимального количества символов
        $message = 'Поле должно содержать минимум ' . $minCount . ' ' . formatChars($minCount);
        $showError = true;
    } elseif ($maxCount !== 0 && strlen($_POST[$key]) > $maxCount) { // проверка максимального количества символов
        $message = 'Поле может содержать максимум ' . $maxCount . ' ' . formatChars($maxCount);
        $showError = true;
    } elseif ($key === 'email' && findUserEmail(strtolower($_POST['email']))) {
        $message = 'Пользователь с такой почтой уже существует';
        $showError = true;
    } elseif ($key === 'login' && findUserLogin(strtolower($_POST['login']))) {
        $message = 'Этот логин уже занят';
        $showError = true;
    } else {
        if ($requiredChars !== '') {
            echo 'requiredchars';
            preg_match('/' . $requiredChars . '/', $_POST[$key], $matchesRequired, PREG_OFFSET_CAPTURE);
            if (!$matchesRequired[0][0]) {
                $message = 'Поле должно содержать хотя бы 1 спец-символ "' . implode(' ', str_split($requiredChars)) . '"';
                $showError = true;
            }
        }
        if ($lockChars !== '') {
            echo 'lockchars';
            preg_match('/' . $lockChars . '/', $_POST[$key], $matchesLock, PREG_OFFSET_CAPTURE);
            if ($matchesLock[0][0]) {
                $message = 'Символ ' . $matchesLock[0][0] . ' запрещен';
                $showError = true;
            }
        }   
    }
    // регулярки
    return $message ?? '';
}

$fields = [ // данные для проверки и вывода ошибки для каждого поля
    'email' => [
        'message' => '',
    ],
    'login' => [
        'message' => '',
        'minCount' => 4,
        'maxCount' => 30,
        'lockChars' => '[!@#$%^&*()+\-=\[\]{};\':"\\|,.<>\/?]', // запретить русские символы
    ],
    'password' => [
        'message' => '',
        'minCount' => 6,
        'maxCount' => 40,
        'lockChars' => '[&()+=\[\]{};\':"\\|<>\/?]',
        'requiredChars' => '[!@#$%^*_,.\-]',
    ],
    'confirm-password' => [
        'message' => '',
    ],
];
$showError = false;
$showMessage = false;
$messageGlobal = '';

if (isset($_POST['sendForm'])) { // проверка отправки формы
    $showMessage = false;
    $messageGlobal = '';
    foreach ($fields as $key => $field) { // проверка валидации каждого поля
        $fields[$key]['message'] = validation($key, $field['minCount'] ?? 0, $field['maxCount'] ?? 0, $field['lockChars'] ?? '', $field['requiredChars'] ?? '');
    }
    if (!$showError) {
        if ($_POST['password'] === $_POST['confirm-password']) {
            if (registration(['login' => strtolower($_POST['login']), 'email' => strtolower($_POST['email']), 'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)]) !== 0) {
                setCookieEmail($_POST['email'], 60 * 10); // после регистрации email в куках хранится 10 минут, после авторизации - 30 дней
                header('Location: /authorization/');
            }
        } else {
            $showMessage = true;
            $messageGlobal = 'Пароли не совпадают';
        }
    }
}

?>
<?php
includeTemplate('header.php', ['href' => '/authorization/', 'hrefName' => 'Уже есть аккаунт? Авторизуйтесь', 'pageTitle' => 'Регистрация']);
?>
<div class="form-wrapper">
    <form class="form" method="POST">
        <h2 class="title">Регистрация</h2>
        <div class="input-wrapper">
            <input class="form-input <?=$fields['email']['message'] ? 'error-input' : ''?>" name="email" placeholder="Почта" value="<?=htmlspecialchars($_POST['email'] ?? '')?>"></input>
            <span class="error"><?=htmlspecialchars($fields['email']['message']) ?? '&nbsp;'?></span>
        </div>
        <div class="input-wrapper">
            <input class="form-input <?=$fields['login']['message'] ? 'error-input' : ''?>" name="login" placeholder="Логин" value="<?=htmlspecialchars($_POST['login'] ?? '')?>"></input>
            <span class="error"><?=htmlspecialchars($fields['login']['message']) ?? '&nbsp;'?></span>
        </div>
        <div class="input-wrapper">
            <div class="password-field">
                <input id="password-field" class="form-input <?=$fields['password']['message'] ? 'error-input' : ''?>" name="password" type="password" placeholder="Пароль" value="<?=htmlspecialchars($_POST['password'] ?? '')?>"></input>
                <div class="img-visibility">
                    <img id="img-visibility-password" src="/assets/pictures/visibility_on.svg" alt="Видимость пароля">
                </div>
            </div>
            <span class="error"><?=htmlspecialchars($fields['password']['message']) ?? '&nbsp;'?></span>
        </div>
        <div class="input-wrapper">
            <div class="password-field">
                <input id="confirm-password-field" class="form-input <?=$fields['confirm-password']['message'] ? 'error-input' : ''?>" name="confirm-password" type="password" placeholder="Подтвердите пароль" value="<?=htmlspecialchars($_POST['confirm-password'] ?? '')?>"></input>
                <div class="img-visibility">
                    <img id="img-visibility-confirm-password" src="/assets/pictures/visibility_on.svg" alt="Видимость пароля">
                </div>
            </div>
            <span class="error"><?=htmlspecialchars($fields['confirm-password']['message']) ?? '&nbsp;'?></span>
        </div>
        <span class="error global-error"><?=$showMessage ? htmlspecialchars($messageGlobal) : '&nbsp;'?></span>
        <div class="nav-form nav-form-registration">
            <button name="sendForm" class="button">Создать аккаунт</button>
        </div>
    </form>
</div>
<script src="/js/set-visibility-password.js"></script>
<?php
includeTemplate('footer.php');
