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

  public function edit($slug) 
  {
    // 該当記事の取得
    $article = $this->Articles->findBySlug($slug)->firstOrFail();

    // リクエストがputかpostであるかの判定
    if ($this->request->is(['put', 'post'])) {
      // putかpostである場合には記事の更新を行う。
      $article = $this->Articles->patchEntity($article, $this->request->getData());
      if ($this->Articles->save($article)) {
        // 更新が成功したら成功のメッセージを表示し、indexにリダイレクトする。
        $this->Flash->success(__('Your article has been updated.'));
        return $this->redirect(['action' => 'index']);
      }
      // 更新が失敗したらエラーメッセージを表示する。
      $this->Flash->error(__('Unable to update your article.'));
    }

    // articleをビューにセットする。
    $this->set('article', $article);
  }
}

?>