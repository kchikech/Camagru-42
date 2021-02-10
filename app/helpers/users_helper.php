<?php
class UsersHelper
{
    public function __construct(User $model)
    {
        $this->userModel = $model;
    }

    public function validate_name($name)
    {
    }

    public function validate_password($password)
    {
    }

    public function validate_email($email)
    {
        if (empty($email))
            return 'Email should not be empty';
        else if ($this->userModel->find_user_by_email($email)) {
            return 'Email already exist!';
        } else return '';
    }
    


    public function validate_confirm_password($confirm_password)
    {
    }
}
