<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query\SelectQuery;
use Cake\Utility\Text;
use Cake\Event\EventInterface;
use Cake\Validation\Validator;

class ArticlesTable extends Table 
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Tags', [
            'foreignKey' => 'article_id',
            'targetForeignKey' => 'tag_id',
            'joinTable' => 'articles_tags',
        ]);
    }

    // バリデーションメソッド
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('title')
            ->minLength('title', 10)
            ->maxLength('title', 255)

            ->notEmptyString('body')
            ->minLength('body', 10);

        return $validator;
    }

    public function beforeSave(EventInterface $event, $entity, $options) 
    {
        if ($entity->isNew() && !$entity->slug) {
            $sluggedTitle = Text::slug($entity->title);
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }

  
    public function findTagged(SelectQuery $query, array $options) 
    {
        $columns = [
            'Articles.id', 'Articles.user_id', 'Articles.title', 'Articles.body', 'Articles.published', 'Articles.created', 'Articles.slug',
        ];

        $query = $query
            ->select($columns)
            ->distinct($columns);

        if(empty($options['tags'])) {
            $query->leftJoinWith('Tags')
                 ->where(['Tags.title Is' => null]);
        } else {
            $query->innerJoinWith('Tags')
                 ->where(['Tags.title IN' => $options['tags']]);
        }

        return $query->group(['Articles.id']);
    }
}
