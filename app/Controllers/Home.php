<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
		//$post = $this->request->getPost();
        return view('welcome');//, ['post' => $post]);
    }
}
