<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ArticlesTagsTable extends Table
{
  public function initialize(array $config): void
  {
      parent::initialize($config);

      $this->setTable('articles_tags');
      $this->setDisplayField('article_id');
      $this->setPrimaryKey(['article_id', 'tag_id']);

      $this->belongsTo('Articles', [
          'foreignKey' => 'article_id',
          'joinType' => 'INNER',
      ]);
      $this->belongsTo('Tags', [
          'foreignKey' => 'tag_id',
          'joinType' => 'INNER',
      ]);
  }
}