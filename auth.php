<?php
declare(strict_types=1);
require_once 'requirements.php';
session_start();
if (!empty($_GET['action']) && $_GET['action'] == 'login') {
    $result = authenticateUser($_POST['username'], $_POST['password']);
    if ($result !== false) {
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['username'] = $result['username'];
        $_SESSION['is_admin'] = intval($result['is_admin']) === 1;
        header('Location: /');
    } else {
        ?>
            <h4>Попытка аутентификации неуспешна. Введен неверный логин или пароль.</h4>
        <?php
    }
}

if (!empty($_GET['action']) && $_GET['action'] == 'register') {
    $result = registerUser($_POST['username'], $_POST['password']);
    if ($result !== false) {
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['username'] = $result['username'];
        header('Location: /');
    } else {
        ?>
        <h4>Попытка регистрации неуспешна.</h4>
        <?php
    }
}

if (!empty($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header('Location: /');
}

if(!isAuthenticated()) { ?>
    <div>
        <a href="/auth.php?action=auth_form">Вход в систему</a> | <a href="/auth.php?action=register_form">Регистрация</a>
    </div>
    <?php if(!empty($_GET['action']) && $_GET['action'] === 'auth_form') { ?>
        <form action="/auth.php?action=login" method="POST">
            <div>
                <div>
                    <label for="username">Имя пользователя:</label>
                </div>
                <div>
                    <input type="text" name="username" />
                </div>
                <div>
                    <label for="password">Пароль:</label>
                </div>
                <div>
                    <input type="password" name="password" />
                </div>
                <div>
                    <input type="submit" value="Войти в систему" />
                </div>
            </div>
        </form>
    <?php } ?>
    <?php if(!empty($_GET['action']) && $_GET['action'] === 'register_form') { ?>
        <form action="/auth.php?action=register" method="POST">
            <div>
                <div>
                    <label for="username">Имя пользователя:</label>
                </div>
                <div>
                    <input type="text" name="username" />
                </div>
                <div>
                    <label for="password">Пароль:</label>
                </div>
                <div>
                    <input type="password" name="password" />
                </div>
                <div>
                    <input type="submit" value="Зарегистрироваться" />
                </div>
            </div>
        </form>
    <?php } ?>
<?php } else { ?>
    <div><a href="/auth.php?action=logout">Выйти из системы</a></div>
<?php } ?>
<?php require_once 'posts.php'; ?>

