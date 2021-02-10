<?php

class Posts extends Controller
{
  private $rrp = 5;

  public function __construct()
  {
    if (!isLoggedIn()) {
      // redirect('users/login');
    }

    $this->postModel = $this->model('Post');
    $this->userModel = $this->model('User');
  }

  public function index()
  {
    // Get posts
    // $posts = $this->postModel->getPosts();

    // foreach ($posts as $post) {
    //   $liked[$post->post_id] = $this->checklike($post->post_id);
    // }
    $data = [];
    $this->view('posts/page', $data);
  }

  public function page($page = -1)
  {
    if ($page !== -1) {
      // Get posts
      header('Content-type: text/json');

      $posts = $this->postModel->getPostsBypage($page, $this->rrp);

      foreach ($posts as $post) {
        $post->liked = $this->checklike($post->post_id);
      }
      $data = [
        'posts' => $posts,
      ];

      echo json_encode($data);
    }
  }

  public function add()
  {
    if (!isLoggedIn()) {
      redirect('posts');
      return;
    }
    $data = [
      'user_id' => '',
      'image_type' => '',
      'super' => '',
      'x' => '',
      'y' => '',
      'image' => '',
      'image_err' => ''
    ];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'user_id' => $_SESSION['user_id'],
        'image_type' => trim($_POST['type']),
        'super' => '',
        'x' => $_POST['x'],
        'y' => $_POST['y'],
        'image' => '',
        'image_err' => ''
      ];
      if (isset($_POST['super']))
        $data['super'] = $_POST['super'];
      if ($_POST['type'] == 'camera' && $_POST['token'] === $_SESSION['token']) {
        if (empty($_POST['imageData'])) {
          $data['image_err'] = 'Please take a picture';
        } else {
          $data['image'] = $this->postModel->saveImage64($_POST['imageData']);
          if (!empty($data['super']))
            watermark(dirname(dirname(APPROOT)) . '/' . $data['image'], $data['super'], $data['x'], $data['y']);
        }
      } else if ($_POST['type'] == 'upload' && $_POST['token'] === $_SESSION['token']) {
        if (!empty($_FILES['image']))
          $data['image'] = $this->postModel->uploadImage($_FILES['image']);
        if (empty($data['image']))
          $data['image_err'] = 'Please upload an image';

        if (!empty($data['super']) && !empty($data['image']) )
          watermark(dirname(dirname(APPROOT)) . '/' . $data['image'], $data['super'], $data['x'], $data['y']);
      }



      if (empty($data['image_err']) && $_POST['token'] === $_SESSION['token']) {
        if ($this->postModel->addPost($data)) {
          flash('post_message', 'Post Added');
          redirect('posts');
        } else
          die('Ouups .. something went wrong !');
      } else
        $this->view('posts/add', $data);
    }
    $this->view('posts/add', $data);
  }


  public function add_like($id)
  {
    if (!isLoggedIn()) {
      $data = [
        'logged' => 'false'
      ];
      echo json_encode($data);
      return;
    }
    $like = $this->postModel->userLikes($id, $_SESSION['user_id']);
    $post = $this->postModel->getPostById($id);
    $user = $this->userModel->find_user_by_id($_SESSION['user_id']);

    $plike = $this->postModel->getPostLikes($id);
    $data = [
      'user_id' => $user->user_id,
      'post_id' => $id,
      'likes' => $like,
      'plikes' => $plike->likes,
    ];

    if (!$like) {
      $this->postModel->addLike($data);
    } else {
      $this->postModel->unlikePost($id, $_SESSION['user_id']);
    }
    $plike = $this->postModel->getPostLikes($id);

    $data = [
      'user_id' => $user->user_id,
      'post_id' => $id,
      'likes' => $like,
      'plikes' => $plike->likes,

    ];

    echo json_encode($data);
  }

  public function show($post_id = -1)
  {
    if (!isLoggedIn()) {
      redirect('posts');
      return;
    }
    if ($post_id !== -1) {


      $likedby = $this->postModel->likedBy($post_id);
      $posts = $this->postModel->getPostById($post_id);
      $user = $this->userModel->find_user_by_id($posts->user_id);
      $comments = $this->postModel->getPostComments($post_id);
      $likes = $this->postModel->getPostlikes($post_id);
      $liked = $this->checklike($posts->post_id);
      $data = [
        'post' => $posts,
        'user' => $user,
        'comments' => $comments,
        'likedby' => $likedby,
        'liked' => $liked,
        'comment_err' => ''
      ];
      if (!$posts) {
        redirect('posts');
      }

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        if (isset($_POST['comment'])) {
          $comment = [
            'user_id' => $_SESSION['user_id'],
            'comment' => trim($_POST['comment']),
            'token' => $_POST['token'],
            'post_id' => $post_id
          ];
          if (empty($comment['comment'])) {
            $data['comment_err'] = 'Empty comment is not valid !';
            $this->view('posts/show', $data);
          } elseif ($this->postModel->addComment($comment)) {
            if ($_POST['token'] === $_SESSION['token']) {
              $data['comments'] = $this->postModel->getPostComments($post_id);
              if ($user->notif == 1) {
                comment_mail($user->email, $post_id);
              }
              redirect('posts/show/' . $post_id);
            }
          } else
            die('Something went wrong..');
        }
      }

      $this->view('posts/show', $data);
    } else redirect('posts');
  }
  public function checklike($id)
  {
    if (!isLoggedIn()) {
      return;
    }
    $like = $this->postModel->userLikes($id, $_SESSION['user_id']);
    if ($like)
      return true;
    else return false;
  }

  public function delete_post($id)
  {
    if (isLoggedIn()) {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $post = $this->postModel->getPostById($id);
        if (isset($_POST['delete']) && $post->user_id == $_SESSION['user_id']) {
          if ($_POST['token'] === $_SESSION['token']) {
            if ($this->postModel->deletePost($id)) {
              redirect('posts');
            }
          }
        }
      }
    }
  }
}
