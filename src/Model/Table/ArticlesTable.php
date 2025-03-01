<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query\SelectQuery;
use Cake\Utility\Text;
use Cake\Event\EventInterface;
use Cake\Validation\Validator;
use Cake\Collection\Collection;

class ArticlesTable extends Table 
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Tags', [
            'dependent' => true,
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
        if ($entity->tag_string) {
            $entity->tags = $this->_buildTags($entity->tag_string);
        }

        if ($entity->isNew() && !$entity->slug) {
            $sluggedTitle = Text::slug($entity->title);
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }

    protected function _buildTags($tagString) 
    {
        $newTags = array_map('trim', explode(',', $tagString));
        $newTags = array_filter($newTags);
        $newTags = array_unique($newTags);

        $out = [];
        $query = $this->Tags->find()
             ->where(['Tags.title IN' => $newTags]);

             $existingTags = (new Collection($query->all()))->extract('title')->toList();

        foreach($existingTags as $existing) {
            $index = array_search($existing, $newTags);
            if ($index !== false) {
                unset($newTags[$index]);
            }
        }

        foreach($query as $tag) {
            $out[] = $tag;
        }

        foreach($newTags as $tag) {
            $out[] = $this->Tags->newEntity(['title' => $tag]);
        }

        return $out;
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
