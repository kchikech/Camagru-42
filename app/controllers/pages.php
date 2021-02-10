<?php
class Pages extends Controller
{
    public function __construct()
    {
        $this->setupModel = $this->model('Setup');
    }
    public function setup()
    {
        $this->setupModel->execute();
        redirect('posts');
    }

    public function  index()
    {
        redirect('posts');
    }

}
