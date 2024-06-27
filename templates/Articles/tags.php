<h1>
  Article tagged with
  <?= $this->Text->toList(h($tags), 'or') ?>
</h1>

<section>
  <?php foreach($articles as $article): ?>
    <article>
      <h4>
        <?= $this->Html->link(
          $article->title,
          ['controller' => 'Articles', 'action' => 'view', $article->slug]
        ) ?>
      </h4>
    </article>
  <?php endforeach; ?>
</section>