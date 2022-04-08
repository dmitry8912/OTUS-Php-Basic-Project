<?php
declare(strict_types=1);
require_once 'database.php';

function authenticateUser($username, $password) {
    $db = getConnection();
    $query = $db->prepare("select * from users where username = ? and password = ?");
    $query->execute([$username, $password]);
    return $query->rowCount() > 0 ? $query->fetch() : false;
}

function isAuthenticated() {
    return !empty($_SESSION['user_id']);
}
