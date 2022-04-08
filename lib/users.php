<?php
declare(strict_types=1);
require_once 'database.php';

function authenticateUser($username, $password) {
    $db = getConnection();
    $query = $db->prepare("select * from users where username = ? and password = ?");
    $query->execute([$username, sha1($password)]);
    return $query->rowCount() > 0 ? $query->fetch() : false;
}

function registerUser($username, $password) {
    $password=sha1($password);
    if(!preg_match("/^[A-z\d]{3,12}$/",$username))
        return false;

    $db = getConnection();
    $query = $db->prepare("select * from users where username = ?");
    $query->execute([$username]);
    if($query->rowCount() > 0)
        return false;
    $registerQuery = $db->prepare("insert into users(username, password) values(?,?)");
    $registerQuery->execute([$username,$password]);
    return [
        'id' => $db->lastInsertId(),
        'username' => $username
    ];
}

function isAuthenticated() {
    return !empty($_SESSION['user_id']);
}

function isAdmin() {
    return !empty($_SESSION['is_admin']) && $_SESSION['is_admin'];
}
