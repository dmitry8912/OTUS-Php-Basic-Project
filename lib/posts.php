<?php
declare(strict_types=1);
require_once 'database.php';
require_once 'users.php';

function getPostsCount() {
    $db = getConnection();
    $query = $db->query("select count(*) as count from posts where posts.is_visible = 1");
    $query->execute();
    return $query->fetch()[0];
}

function getPosts($page = 0, $search = []) {
    $db = getConnection();
    if(!empty($search))
    {
        $searchParams = [];
        foreach($search as $k => $s)
        {
            if(!empty($s))
            {
                $searchParams[] = "{$k} like \"%{$s}%\"";
            }
        }
        $searchString = implode(' and ', $searchParams);
        $query = $db->query("select posts.id, users.username, posts.title, posts.post_data, posts.created_at from posts join users on posts.user_id = users.id where posts.is_visible = 1 and {$searchString} order by posts.id desc limit 25 offset ".($page * 25));
        $query->execute();
        return $query->fetchAll();
    }
    $query = $db->query("select posts.id, users.username, posts.title, posts.post_data, posts.created_at from posts join users on posts.user_id = users.id where posts.is_visible = 1 order by posts.id desc limit 25 offset ".($page * 25));
    $query->execute();
    return $query->fetchAll();
}

function addPost($userId, $postTitle, $postText) {
    $db = getConnection();
    $query = $db->prepare("insert into posts(user_id, title, post_data) values(?,?,?)");
    $query->execute([$userId, $postTitle, $postText]);
    return $query->fetchAll();
}

function hidePost($postId) {
    if(!isAdmin())
        return;
    $db = getConnection();
    $query = $db->prepare("update posts set is_visible = 0 where id = ?");
    $query->execute([$postId]);
}

function deletePost($postId) {
    if(!isAdmin())
        return;
    $db = getConnection();
    $query = $db->prepare("delete from posts where id = ?");
    $query->execute([$postId]);
}
