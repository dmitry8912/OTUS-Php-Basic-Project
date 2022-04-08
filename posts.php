<?php
declare(strict_types=1);
require_once 'requirements.php';
session_start();
if(!empty($_GET['action']) && $_GET['action'] === 'add_post')
{
    addPost($_SESSION['user_id'], $_POST['post_title'], $_POST['post_data']);
    header('Location: /');
}

if(isAuthenticated()) { ?>
    <form action="/posts.php?action=add_post" method="POST">
        <div>
            <div>
                <label for="post_title">Заголовок объявления:</label>
            </div>
            <div>
                <input type="text" name="post_title" />
            </div>
            <div>
                <label for="post_data">Текст объявления:</label>
            </div>
            <div>
                <textarea name="post_data"></textarea>
            </div>
            <div>
                <input type="submit" name="Создать объявление" />
            </div>
        </div>
    </form>
<?php } ?>
<h3>Доска объявлений:</h3>
<?php

$posts = getPosts();

foreach($posts as $post)
{
    echo "<div><div>Объявление №{$post['id']} от {$post['created_at']}. Оставил {$post['username']}</div>";
    echo "<div>" . $post['post_data'] . "</div></div><hr>";
}

?>
