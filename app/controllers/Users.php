<?php
class Users extends Controller
{

    public function __construct()
    {
        $this->userModel = $this->model('User');
        $this->postModel = $this->model('Post');
    }

    public function index()
    {
        redirect('users/login');
    }

    public function verify_email()
    {
        if (isLoggedIn()) {
            redirect('posts');
            return;
        }

        if (isset($_GET['email']) && isset($_GET['token']) && !empty($_GET['email']) && !empty($_GET['token'])) {
            $email = $_GET['email'];
            $token = $_GET['token'];
            $user = $this->userModel->find_user_by_email($email);
            if (!$user) {
                redirect('users/login');
                return;
            }
            // var_dump($token);
            // die();
            if ($user->token === $token && $user->email === $email) {
                if ($user->verified != 1) {
                    $this->userModel->confirm_email($user->email);
                    flash('account_verified', 'Congrats ! your account have been verified. You can login with your credentials', 'tag is-warning is-large');
                    redirect('users/login');
                } else {
                    flash('already_verified', 'Link Expired, your account already verified');
                    redirect('users/login');
                }
            } else
                die("Oops something went wrong !");
        }
    }

    public function register()
    {
        if (isLoggedIn()) {
            redirect('posts');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'token' => '',
                'name_err' => '',
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            // Name validation 
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter your name';
            }

            // Username validation
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter a username';
            } else if ($this->userModel->find_user_by_username($data['username'])) {
                $data['username_err'] = "Username already taken";
            }

            // Email validation
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter your email';
            } else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = "Invalid email format";
            } else {
                if ($this->userModel->find_user_by_email($data['email'])) {
                    $data['email_err'] = "Email already taken";
                }
            }


            // Validate password strength
            $uppercase = preg_match('@[A-Z]@', $data['password']);
            $lowercase = preg_match('@[a-z]@', $data['password']);
            $number    = preg_match('@[0-9]@', $data['password']);
            $specialChars = preg_match('@[^\w]@', $data['password']);



            // Password validation 
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter a password';
            } elseif (strlen($data['password']) < 8) {
                $data['password_err'] = 'Password must be at least 8 characters';
            } elseif (!$uppercase || !$lowercase || !$number || !$specialChars) {
                $data['password_err'] = 'Password should include at least one upper case letter, one number, and one special character.';
            }


            // Confirm password validation
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'please confirm password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            if (empty($data['name_err']) && empty($data['username_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // die('SUCCESS');

                // Hash password 
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                //generate token
                $data['token'] = bin2hex(random_bytes(16));

                // Register user
                if ($this->userModel->register($data)) {
                    verification_mail($data['email'], $data['token']);
                    flash('register_success', 'You\'re registred to Camagru, please check your email for verification');
                    redirect('users/login');
                } else {
                    die('Something went wrong!');
                }
            } else {
                $this->view('users/register', $data);
            }
        } else {
            $data = [
                'name' => '',
                'username' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            $this->view('users/register', $data);
        }
    }

    public function login()
    {
        if (isLoggedIn()) {
            redirect('posts');
        }
        $data = [
            'email' => '',
            'password' => '',
            'email_err' => '',
            'password_err' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];
            $user = $this->userModel->find_user_by_username($data['email']);

            // Email validation
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter your username';
            }
            // Password validation
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter your password';
            }
            // Check user email 
            if (!$user) {
                $data['email_err'] = 'No user found';
            }


            // Check errors if empty 
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Check and set logged in user
                if ($user->verified != 1) {
                    flash("verify_email", "* Please verify your email to access to your account", "has-text-danger-dark");
                    $this->view('users/login', $data);
                }

                $loggedInuser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedInuser && $user->verified == 1) {
                    // create session
                    $this->createUserSession($loggedInuser);
                } else {
                    $data['password_err'] = 'username or password incorrect';
                }
            }
            $this->view('users/login', $data);
        }
        $this->view('users/login', $data);
    }

    public function settings()
    {
        if (!isLoggedIn()) {
            redirect('users/login');
            return;
        }

        $user = $this->userModel->find_user_by_id($_SESSION['user_id']);
        $data = [
            'user_id' => $_SESSION['user_id'],
            'user' => $user,
            'old_password' => '',
            'new_password' => '',
            'conf_new_password' => '',
            'new_username' => '',
            'new_email' => '',
            'new_fullname' => '',
            'notif' => '',

            'username_err' => '',
            'email_err' => '',
            'old_password_err' => '',
            'new_password_err' => '',
            'conf_new_password_err' => '',
            'fullname_err' => ''
        ];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            if (isset($_POST['update'])) {

                $data['new_email'] = trim($_POST['email']);
                $data['new_username'] = trim($_POST['username']);
                $data['new_fullname'] = trim($_POST['fname']);
                if (!isset($_POST['token']) || $_POST['token'] != $_SESSION['token']) {
                    redirect('posts');
                    return;
                }

                if (empty($data['new_email'])) {
                    $data['email_err'] = 'Please enter an email, this field shouldn\'t be empty';
                }

                if (empty($data['new_username'])) {
                    $data['username_err'] = 'Please enter a username, this field shouldn\'t be empty';
                }

                if (empty($data['new_fullname'])) {
                    $data['fullname_err'] = 'Please enter your fullname, this field shouldn\'t be empty';
                }

                if (isset($_POST['notif']) && $_POST['notif'] === 'yes') {
                    $data['notif'] = '1';
                } else {
                    $data['notif'] = '0';
                }

                if ($data['new_email'] === $data['user']->email && $data['new_username'] === $data['user']->username && $data['new_fullname'] === $data['user']->name && $data['notif'] === $data['user']->notif) {
                    redirect('users/settings');
                } else {
                    if ($user = $this->userModel->find_user_by_username($data['new_username']) && $data['new_username'] != $data['user']->username) {
                        $data['username_err'] = 'username already taken';
                    }
                    if ($user = $this->userModel->find_user_by_email($data['new_email'])) {
                        $data['email_err'] = 'email already taken';
                    }
                    if (empty($data['username_err']) && empty($data['fullname_err'])  && empty($data['email_err'])) {
                        if ($this->userModel->update_info($data)) {
                            $user = $this->userModel->find_user_by_id($_SESSION['user_id']);
                            $data['user'] = $user;
                            $this->view('users/settings', $data);
                        }
                    }
                }
            }

            /// if password changed
            else if (isset($_POST['change'])) {

                $data['old_password'] = trim($_POST['old_password']);
                $data['new_password'] = $_POST['new_password'];
                $data['conf_new_password'] = $_POST['conf_new_password'];
                if (!isset($_POST['token']) || $_POST['token'] != $_SESSION['token'])
                    redirect('pages');

                if (empty($data['old_password'])) {
                    $data['old_password_err'] = 'Please enter your old password, this field shouldn\'t be empty';
                } else if (empty($data['new_password'])) {
                    $data['new_password_err'] = 'Please enter your password, this field shouldn\'t be empty';
                } else if (empty($data['conf_new_password'])) {
                    $data['conf_new_password_err'] = 'Please confirm your new password, this field shouldn\'t be empty';
                } else if (password_verify($data['old_password'], $data['user']->password) && $data['new_password'] === $data['conf_new_password']) {

                    if (strlen($data['new_password']) < 8) {
                        $data['new_password_err'] = 'Password must be at least 8 characters';
                    }
                    $data['new_password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
                    if ($this->userModel->change_password($data)) {
                        flash('password_changed', 'Your password has been changed successfully !');
                        $this->view('users/settings', $data);
                    }
                } else {
                    $data['conf_new_password_err'] = 'passwords doesn\'t match';
                }

                $this->view('users/settings', $data);
            }
        }

        $this->view('users/settings', $data);
    }

    public function profile($id)
    {
        if (!isLoggedIn()) {
            redirect('users/login');
            return;
        }
        $data = [
            'user' => '',
            'count_posts' => '',
            'posts' => '',
            'liked' => '',
            'password' => ''
        ];
        $user = $this->userModel->find_user_by_id($id);
        if (!$user) {
            redirect('posts');
        }
        if (!$this->userModel->find_user_by_id($id))
            redirect('posts');
        $postn = $this->postModel->posts_shared($id);
        $posts = $this->postModel->getPostsByUserId($id);
        if ($posts) {
            foreach ($posts as $post) {
                $liked[$post->post_id] = $this->checklike($post->post_id);
            }
        } else
            $liked = 0;


        $data = [
            'user' => $user,
            'count_posts' => $postn,
            'posts' => $posts,
            'liked' => $liked,
            'password' => ''
        ];

        $this->view('users/profile', $data);
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



    /// Forget Password !! 

    public function forget_password()
    {
        if (isLoggedIn()) {
            redirect('posts');
            return;
        }
        $data = [
            'email' => '',
            'email_err' => '',
            'token' => '',
            'expiration' => ''

        ];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'email' => $_POST['email'],
                'email_err' => '',
                'token' => hash('sha256', bin2hex(random_bytes(8))),
                'expiration' => date('U') + 3600,

            ];
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            if ($user = $this->userModel->find_user_by_email($data['email'])) {
                if (forgetPassword_mail($data['email'], $data['token']) && $this->userModel->createResetToken($data)) {
                    flash('forget_email_send', 'Reset password link sent to your email.');
                }
            } else {
                $data['email_err'] = 'No user exists with such email';
            }
            $this->view('users/forget_password', $data);
        }
        $this->view('users/forget_password', $data);
    }

    public function change_forgottenPassword($token = -1)
    {
        if (isLoggedIn()) {
            redirect('posts');
            return;
        }
        if ($token != -1) {
            $data = [
                'token' => $token,
                'email' => '',
                'new_password' => '',
                'conf_new_password' => '',
                'new_password_err' => '',
                'conf_new_password_err' => '',
                'expiration' => '',
            ];

            if ($user = $this->userModel->checkResetToken($token)) {

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                    $data = [
                        'token' => $token,
                        'email' => $user->email,
                        'new_password' => trim($_POST['new_password']),
                        'conf_new_password' => trim($_POST['conf_new_password']),
                        'new_password_err' => '',
                        'conf_new_password_err' => '',
                        'expiration' => $user->expires
                    ];

                    if (empty($data['new_password']))
                        $data['new_password_err'] = 'Please enter your password, this field shouldn\'t be empty';
                    else if (empty($data['conf_new_password']))
                        $data['conf_new_password_err'] = 'Please confirm your new password, this field shouldn\'t be empty';
                    else if ($data['conf_new_password'] != $data['new_password'])
                        $data['new_password_err'] = 'Passwords don\'t match';
                    else if (strlen($data['new_password']) < 8) {
                        $data['new_password_err'] = 'Password must be at least 8 characters';
                    } else if (empty($data['new_password_err']) && empty($data['conf_new_password_err'])) {
                        $data['new_password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
                        if ($this->userModel->resetPassword($data)) {
                            flash('reset_success', 'Your password has been modified successfully');
                            redirect('users/login');
                        } else
                            $this->view('users/reset_password', $data);
                    }
                    $this->userModel->cleanReset($data['email']);
                } else
                    $this->view('users/reset_password', $data);
            } else redirect('users/login');
        }
        $this->view('users/reset_password', $data);
    }

    /// SESSION

    public function createUserSession($user)
    {
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['token'] = bin2hex(random_bytes(32));

        redirect('posts');
    }

    // Logout func
    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['token']);
        session_destroy();
        redirect('users/login');
    }
}
