<?php
declare(strict_types=1);
require_once 'requirements.php';
session_start();
if (!empty($_GET['action']) && $_GET['action'] == 'login') {
    $result = authenticateUser($_POST['username'], $_POST['password']);
    if ($result !== false) {
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['username'] = $result['username'];
        header('Location: /');
    } else {

        ?>
            <h4>Попытка аутентификации неуспешна. Введен неверный логин или пароль.</h4>
        <?php
    }
}

if(!isAuthenticated()) { ?>
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
                <input type="submit" name="Войти в систему" />
            </div>
        </div>
    </form>
<?php } ?>
<?php require_once 'posts.php'; ?>

