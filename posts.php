<?php
declare(strict_types=1);
require_once 'requirements.php';
session_start();
if(!empty($_GET['action']) && $_GET['action'] === 'add_post')
{
    addPost($_SESSION['user_id'], $_POST['post_title'], $_POST['post_data']);
    header('Location: /');
}

if(!empty($_GET['action']) && $_GET['action'] === 'hide_post')
{
    hidePost($_GET['post_id']);
    header('Location: /');
}

if(!empty($_GET['action']) && $_GET['action'] === 'delete_post')
{
    deletePost($_GET['post_id']);
    header('Location: /');
}

if(isAuthenticated()) { ?>
    <div>
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
                    <input type="submit" value="Создать объявление" />
                </div>
            </div>
        </form>
    </div>
    <hr>
    <div>
        <form action="/posts.php?action=search" method="POST">
            <div>
                <div>
                    <label for="username">Автор:</label>
                </div>
                <div>
                    <input type="text" name="username" />
                </div>
                <div>
                    <label for="post_data">Текст объявления:</label>
                </div>
                <div>
                    <input type="text" name="post_data" />
                </div>
                <div>
                <label for="title">Заголовок объявления:</label>
                </div>
                <div>
                    <input type="text" name="title" />
                </div>
                <div>
                    <input type="submit" value="Поиск" />
                </div>
            </div>
        </form>
    </div>
<?php } ?>
<h3>Доска объявлений:</h3>
<?php
$page = !empty($_GET['page']) ? intval($_GET['page']) : 0;
if(!empty($_GET['action']) && $_GET['action'] === 'search')
{
    $posts = getPosts($page, $_POST);
} else {
    $posts = getPosts($page);
}


foreach($posts as $post)
{
    echo "<div><div>Объявление №{$post['id']} от {$post['created_at']}. Оставил {$post['username']}</div>";
    echo "<h4>{$post['title']}</h4>";
    echo "<div>" . $post['post_data'] . "</div></div>";
    if(!empty($_SESSION['is_admin']) && $_SESSION['is_admin'])
    {
        echo "<div><a href=\"/posts.php?action=hide_post&post_id={$post['id']}\">Скрыть</a> | <a href=\"/posts.php?action=delete_post&post_id={$post['id']}\">Удалить</a></div>";
    }
    echo "<hr>";
}


$count = getPostsCount();
echo "<div>";
if($page > 0)
{
    $prevPage = $page - 1;
    echo "<a href=\"/posts.php?page={$prevPage}\"><< Назад</a>";
}
echo " | ";
if($count > $page * 25)
{
    $nextPage = $page + 1;
    echo "<a href=\"/posts.php?page={$nextPage}\">Дальше >></a>";
}
echo "</div>";

?>
