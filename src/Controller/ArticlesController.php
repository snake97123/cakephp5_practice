<?php

namespace App\Controller;

use App\Controller\AppController;

class ArticlesController extends AppController 
{
  public function initialize(): void
  {
    parent::initialize();
  }

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

  public function add()
  {
    $article = $this->Articles->newEmptyEntity();

    if($this->request->is('post')) {
      $article = $this->Articles->patchEntity($article, $this->request->getData());

      $article->user_id = 1;

      if($this->Articles->save($article)) {
        $this->Flash->success(__('Your article has been saved.'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('Unable to add your article'));
    }
    $this->set('article', $article);
  }
}

?>