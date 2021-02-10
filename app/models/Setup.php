
<?php

class Setup
{
    private $db;

    public function __construct()
    {              
        $this->db = new Database;
    }

    public function execute()
    {     
        $this->createUsers();
        $this->createPosts();
        $this->createResetPasswd();
        $this->createComments();
        $this->createLikes();
    }

    public function createUsers()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `camagru`.`users` ( ';
        $sql .= '`user_id` int(8) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`username` VARCHAR(255) NOT NULL, ';
        $sql .= '`name` VARCHAR(255) NOT NULL, ';
        $sql .= '`email` VARCHAR(255) NOT NULL, ';
        $sql .= '`password` VARCHAR(255) NOT NULL, ';
        $sql .= '`token` VARCHAR(255), ';
        $sql .= '`notif` tinyint(1) NOT NULL DEFAULT 1, ';
        $sql .= '`verified` tinyint(1) NOT NULL DEFAULT 0, ';
        $sql .= '`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,';
        $sql .= 'PRIMARY KEY (`user_id`))';
        $this->db->query($sql);
        $this->db->execute();
    }

    public function createPosts()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `camagru`.`posts` ( ';
        $sql .= '`post_id` int(8) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`user_id` INT NOT NULL, ';
        $sql .= '`image` VARCHAR(255) NOT NULL, ';
        $sql .= '`likes` int(8) NOT NULL DEFAULT 0 , ';
        $sql .= '`comments` int(8) NOT NULL DEFAULT 0 , ';
        $sql .= '`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, ';
        $sql .= 'PRIMARY KEY (`post_id`))';
        $this->db->query($sql);
        $this->db->execute();
    }

    public function createResetPasswd()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `camagru`.`resetPasswd` ( ';
        $sql .= '`id` INT NOT NULL AUTO_INCREMENT , ';
        $sql .= '`email` VARCHAR(255) NOT NULL , ';
        $sql .= '`token` VARCHAR(255) NOT NULL , ';
        $sql .= '`expires`  int(11)  NOT NULL , ';
        $sql .= 'PRIMARY KEY (`id`))';
        $this->db->query($sql);
        $this->db->execute();
    }

    public function createLikes()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `camagru`.`likes` ( ';
        $sql .= '`like_id` int(8) NOT NULL AUTO_INCREMENT , ';
        $sql .= '`user_id` int(8) NOT NULL , ';
        $sql .= '`post_id` int(8) NOT NULL , ';
        $sql .= 'PRIMARY KEY (`like_id`))';
        $this->db->query($sql);
        $this->db->execute();
        $this->db->query('DROP TRIGGER IF EXISTS `likeDecrement`');
        $this->db->execute();
        $this->db->query('DROP TRIGGER IF EXISTS `likeIncrement`');
        $this->db->execute();
        $sql = 'CREATE TRIGGER `likeDecrement` AFTER DELETE ON `camagru`.`likes` FOR EACH ROW ';
        $sql .= 'BEGIN UPDATE camagru.`posts` SET likes = likes - 1 WHERE posts.post_id = old.post_id AND posts.likes > 0;END';
        $this->db->query($sql);
        $this->db->execute();
        $sql = 'CREATE TRIGGER `likeIncrement` AFTER INSERT ON `camagru`.`likes` FOR EACH ROW ';
        $sql .= 'BEGIN SET @nbr := (SELECT COUNT(*) FROM likes WHERE likes.`post_id` = NEW.`post_id`);';
        $sql .= 'UPDATE camagru.`posts` SET camagru.`posts`.`likes` = @nbr WHERE posts.`post_id` = NEW.post_id;END';
        $this->db->query($sql);
        $this->db->execute();
    }

    public function createComments()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `camagru`.`comments` ( ';
        $sql .= '`comment_id` INT NOT NULL AUTO_INCREMENT , ';
        $sql .= '`user_id` INT NOT NULL , ';
        $sql .= '`post_id` INT NOT NULL , ';
        $sql .= '`comment` TEXT NOT NULL , ';
        $sql .= '`date_pub` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, ';
        $sql .= 'PRIMARY KEY (`comment_id`))';
        $this->db->query($sql);
        $this->db->execute();
        $this->db->query('DROP TRIGGER IF EXISTS `commentDecrement` ');
        $this->db->execute();
        $this->db->query('DROP TRIGGER IF EXISTS `commentIncrement` ');
        $this->db->execute();
        $sql = 'CREATE TRIGGER `commentDecrement` AFTER DELETE ON `camagru`.`comments` FOR EACH ROW ';
        $sql .= 'BEGIN UPDATE camagru.`posts` SET comments = comments - 1 WHERE posts.post_id = old.post_id AND posts.comments > 0;END';
        $this->db->query($sql);
        $this->db->execute();
        $sql = 'CREATE TRIGGER `commentIncrement` AFTER INSERT ON `camagru`.`comments` FOR EACH ROW ';
        $sql .= 'BEGIN SET @nbr := (SELECT COUNT(*) FROM comments WHERE comments.`post_id` = NEW.`post_id`); ';
        $sql .= 'UPDATE camagru.`posts` SET camagru.`posts`.`comments` = @nbr WHERE posts.`post_id` = NEW.post_id;END';
        $this->db->query($sql);
        $this->db->execute();
    }
}

?>
