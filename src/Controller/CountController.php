<?php

namespace App\Controller;

use App\Controller\AppController;

class CountController extends AppController 
{
  public function initialize(): void
  {
    parent::initialize();
  }

  public function index()
  {
    $this->Authorization->skipAuthorization();
    $session = $this->getRequest()->getSession();
    $count = $session->read('count');
    if($count === null) {
      $count = 0;
      $session->write('count', $count);
    }
    $this->set(compact('count'));
  }

  public function add()
  {
    $this->Authorization->skipAuthorization();
    $session = $this->getRequest()->getSession();
    $count = $session->read('count');
    $count = $count + 1;
    $session->write('count', $count);
    return $this->redirect(['action' => 'index']);
  }

  public function subtract()
  {
    $this->Authorization->skipAuthorization();
    $session = $this->getRequest()->getSession();
    $count = $session->read('count');
    $count = $count - 1;
    $session->write('count', $count);
    return $this->redirect(['action' => 'index']);
  }

  public function reset()
  {
    $this->Authorization->skipAuthorization();
    $session = $this->getRequest()->getSession();
    $session->delete('count');
    return $this->redirect(['action' => 'index']);
  }
}