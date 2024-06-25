<?php

namespace App\Controller;

use Cake\Controller\Controller;

class ArticlesController extends Controller 
{
  public function index()
  {
    $articles = $this->paginate($this->Articles);
    $this->set(compact('articles'));
  }
}

?>