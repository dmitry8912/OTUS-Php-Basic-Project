<?php
declare(strict_types=1);
require_once 'database.php';
require_once 'users.php';

class Post {
    private $id, $user_id, $title, $text;
    private $db;
    public function __construct($user_id, $title, $text) {
        $this->user_id = $user_id;
        $this->title = $title;
        $this->text = $text;
        $this->db = getConnection();
    }

    public function save() {
        $query = $this->db->prepare("insert into posts(user_id, title, post_data) values(?,?,?)");
        $query->execute([$this->user_id, $this->title, $this->text]);
        $this->id = $this->db->lastInsertId();
    }

    public static function hide($postId) {
        if(!isAdmin())
            return;
        $db = getConnection();
        $query = $db->prepare("update posts set is_visible = 0 where id = ?");
        $query->execute([$postId]);
    }

    public static function delete($postId) {
        if(!isAdmin())
            return;
        $db = getConnection();
        $query = $db->prepare("delete from posts where id = ?");
        $query->execute([$postId]);
    }
}

class Posts implements Countable, Iterator
{
    private $page, $search, $posts, $position;
    public function __construct($page, $search) {
        $this->page = $page;
        $this->search = $search;
        $this->posts = $this->all();
        $this->position = 0;
    }

    private function all() {
        $db = getConnection();
        if(!empty($this->search))
        {
            $searchParams = [];
            foreach($this->search as $k => $s)
            {
                if(!empty($s))
                {
                    $searchParams[] = "{$k} like \"%{$s}%\"";
                }
            }
            $searchString = implode(' and ', $searchParams);
            $query = $db->query("select posts.id, users.username, posts.title, posts.post_data, posts.created_at from posts join users on posts.user_id = users.id where posts.is_visible = 1 and {$searchString} order by posts.id desc limit 25 offset ".($this->page * 25));
            $query->execute();
            return $query->fetchAll();
        }
        $query = $db->query("select posts.id, users.username, posts.title, posts.post_data, posts.created_at from posts join users on posts.user_id = users.id where posts.is_visible = 1 order by posts.id desc limit 25 offset ".($this->page * 25));
        $query->execute();
        return $query->fetchAll();
    }

    //Countable
    public function count(): int
    {
        $db = getConnection();
        $query = $db->query("select count(*) as count from posts where posts.is_visible = 1");
        $query->execute();
        return intval($query->fetch()[0]);
    }

    //Iterator
    public function current()
    {
        return $this->posts[$this->position];
    }

    public function next()
    {
        ++$this->position;
        return $this->posts[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->posts[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}


