<?php

class Post
{
  private $db;
  private $allowed = ['jpg', 'jpeg', 'png'];

  public function __construct()
  {
    $this->db = new Database;
  }

  public function getPosts()
  {
    $this->db->query('SELECT *,
                        posts.post_id as postId,
                        users.user_id as userId,
                        posts.created_at as postCreated,
                        posts.image as images,
                        users.username as userName,
                        users.created_at as userCreated
                        FROM posts
                        INNER JOIN users
                        ON posts.user_id = users.user_id
                        ORDER BY posts.created_at DESC
                        ');

    $results = $this->db->resultSet();

    return $results;
  }

  public function getPostsBypage($page, $num)
  {
    $this->db->query('SELECT *,
                        posts.post_id as postId,
                        users.user_id as userId,
                        posts.created_at as postCreated,
                        posts.image as images,
                        users.username as userName,
                        users.created_at as userCreated
						FROM posts
						INNER JOIN users
						ON posts.user_id = users.user_id
						ORDER BY posts.created_at DESC
						LIMIT :num OFFSET :start');
    $this->db->bind(':num', $num, PDO::PARAM_INT);
    $this->db->bind(':start', $page * $num, PDO::PARAM_INT);
    return $this->db->resultSet();
  }


  public function getPostById($id)
  {
    $this->db->query('SELECT * FROM posts WHERE post_id = :id ORDER BY posts.created_at DESC');
    $this->db->bind(':id', $id);

    return $this->db->single();
  }

  public function getPostsByUserId($id)
  {
    $this->db->query('SELECT * FROM posts WHERE user_id = :id ORDER BY posts.created_at DESC');
    $this->db->bind(':id', $id);

    return $this->db->resultSet();
  }


  public function getPostComments($id)
  {
    $this->db->query('SELECT 
						comments.comment_id as cmntId,
						comments.comment as cmnt,
						comments.date_pub as cmntCreated,
						comments.user_id as userId,
						users.username as useruName,
            users.name as userName
						FROM comments, users
						WHERE comments.post_id = :id
						AND users.user_id = comments.user_id
						ORDER BY comments.date_pub DESC');
    $this->db->bind(':id', $id);

    $results = $this->db->resultSet();
    return $results;
  }

  public function posts_shared($id)
  {
    $this->db->query('SELECT * FROM posts WHERE user_id = :id');
    $this->db->bind(':id', $id);
    // $row = $this->db->rowCount();
    $this->db->execute();
    $row = $this->db->rowCount();
    return $row;
  }


  public function getPostLikes($post)
  {
    $this->db->query('SELECT likes FROM posts WHERE post_id = :post_id');
    $this->db->bind(':post_id', $post);
    return $this->db->single();
    // return $this->db->rowCount();
  }

  public function addPost($data)
  {
    $this->db->query('INSERT INTO posts (user_id, image) VALUES (:user_id, :image)');
    $this->db->bind(':image', $data['image']);
    $this->db->bind(':user_id', $data['user_id']);

    return $this->db->execute();
  }

  public function likedBy($post_id)
  {
    $this->db->query('SELECT
              likes.post_id as postId, 
              users.user_id as userId,
              users.username as username, 
              users.name as uname
              FROM likes 
              INNER JOIN users 
              ON likes.user_id = users.user_id
              WHERE post_id = :post_id
    ');
    $this->db->bind(':post_id', $post_id);
    return $this->db->resultSet();
  }

  public function addLike($like)
  {
    $this->db->query('INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)');
    $this->db->bind(':user_id', $like['user_id']);
    $this->db->bind(':post_id', $like['post_id']);
    return $this->db->execute();
  }

  public function unlikePost($post, $user)
  {
    $this->db->query('DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id');
    $this->db->bind(':user_id', $user);
    $this->db->bind(':post_id', $post);
    return $this->db->execute();
  }

  public function userLikes($post, $user)
  {
    $this->db->query('SELECT * FROM likes WHERE user_id = :user_id AND post_id = :post_id');
    $this->db->bind(':user_id', $user);
    $this->db->bind(':post_id', $post);
    $this->db->execute();
    return $this->db->rowCount();
  }

  public function addComment($comment)
  {
    $this->db->query('INSERT INTO comments (comment,  post_id, user_id) VALUES (:comment,  :post_id, :user_id)');
    $this->db->bind(':user_id', $comment['user_id']);
    $this->db->bind(':comment', $comment['comment']);
    $this->db->bind(':post_id', $comment['post_id']);

    return $this->db->execute();
  }

  public function deleteComment($comment, $user)
  {
    $this->db->query('DELETE FROM comments WHERE id = :id AND user_id = :user_id');
    $this->db->bind(':id', $comment);
    $this->db->bind(':user_id', $user);
    return $this->db->execute();
  }

  public function deletePost($id)
  {
    $this->db->query('DELETE FROM posts WHERE  post_id = :id');
    $this->db->bind(':id', $id);
    return $this->db->execute();
  }



  public function uploadImage($file)
  {
    $name = $file['name'];
    $tmpName = $file['tmp_name'];
    $size = $file['size'];
    // $err = $file['err'];
    $ext = explode('.', $name);
    $ext = strtolower(end($ext));
    if (in_array($ext, $this->allowed) && $size < 1000000) {
      $root = dirname(dirname(APPROOT));
      if (!file_exists($root . '/uploads'))
        exec('mkdir -p ' . $root . '/uploads');
      $dest = 'uploads/' . uniqid('', true) . '.' . $ext;
      move_uploaded_file($tmpName, dirname(dirname(APPROOT)) . '/' . $dest);
      return $dest;
    } else
      return false;
  }
  function saveImage64($data)
  {
    list($type, $data) = explode(';', $data);
    list(, $ext) = explode('/', $type);
    list(, $data) = explode(',', $data);
    $root = dirname(dirname(APPROOT));
    if (!file_exists($root . '/uploads'))
      exec('mkdir -p ' . $root . '/uploads');
    $dest = 'uploads/' . uniqid('', true) . '.' . $ext;
    file_put_contents($root . '/' . $dest, base64_decode($data));
    return $dest;
  }
}
