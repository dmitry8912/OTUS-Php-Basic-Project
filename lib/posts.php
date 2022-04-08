<?php
declare(strict_types=1);
require_once 'database.php';

function getPosts() {
    $db = getConnection();
    $query = $db->query("select posts.id, users.username, posts.title, posts.post_data, posts.created_at from posts join users on posts.user_id = users.id");
    $query->execute();
    return $query->fetchAll();
}

function addPost($userId, $postTitle, $postText) {
    $db = getConnection();
    $query = $db->prepare("insert into posts(user_id, title, post_data) values(?,?,?)");
    $query->execute([$userId, $postTitle, $postText]);
    return $query->fetchAll();
}
