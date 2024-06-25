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

  public function view($slug = null)
  {
    $article = $this->Articles->findBySlug($slug)->firstOrFail();
    $this->set(compact('article'));
  }
}

?>