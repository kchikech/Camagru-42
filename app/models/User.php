<?php
class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Register user

    public function register($data)
    {
        $this->db->query('INSERT INTO users (username, name, email, password, token) VALUES(:username, :name, :email, :password, :token)');

        $this->db->bind(':username', $data['username']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':token', $data['token']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Login

    public function login($username, $password)
    {
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }

    // Check Email
    public function find_user_by_email($email)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();

        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    public function find_user_by_username($username)
    {
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);
        $row = $this->db->single();

        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }


    public function find_user_by_id($id)
    {
        $this->db->query('SELECT * FROM users WHERE user_id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();

        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    public function update_info($data)
    {
        $this->db->query('UPDATE users 
                    SET username = :username,
                    name = :fullname,
                    email = :email,
                    notif = :notif
                    WHERE user_id = :id ');
        $this->db->bind(':username', $data['new_username']);
        $this->db->bind(':fullname', $data['new_fullname']);
        $this->db->bind(':email', $data['new_email']);
        $this->db->bind(':id', $data['user_id']);
        $this->db->bind(':notif', $data['notif']);


        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function change_password($data)
    {
        $this->db->query('UPDATE users 
                    SET password = :password
                    WHERE user_id = :id ');
        $this->db->bind(':password', $data['new_password']);
        $this->db->bind(':id', $data['user_id']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function resetPassword($data)
    {
        $this->db->query('UPDATE users 
                    SET password = :password
                    WHERE email = :email ');
        $this->db->bind(':password', $data['new_password']);
        $this->db->bind(':email', $data['email']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function checkResetToken($token)
    {
        $this->db->query('SELECT * FROM resetPasswd WHERE token = :token AND expires >= :expiration');
        $this->db->bind(':token', $token);
        $this->db->bind(':expiration', date('U'));
        if ($row = $this->db->single())
            return $row;
        else
            return false;
    }

    public function createResetToken($data)
    {
        if ($this->cleanReset($data['email'])) {
            $this->db->query('INSERT INTO resetPasswd (email, token, expires) VALUE (:email, :token, :expiration)');
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':token', $data['token']);
            $this->db->bind(':expiration', $data['expiration'], PDO::PARAM_INT);
            return $this->db->execute();
        } else
            return false;
    }

    public function cleanReset($email)
    {
        $this->db->query('DELETE FROM resetPasswd WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }


    public function confirm_email($email)
    {
        $this->db->query('UPDATE users 
                    SET verified = 1
                    WHERE email = :email ');
        $this->db->bind(':email', $email);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
