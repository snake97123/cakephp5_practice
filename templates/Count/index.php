<div>現在のカウント数：<?= $count ?></div>

<button type="button" onclick="location.href='<?= $this->Url->build(['action' => 'add']) ?>'">カウントアップ</button>
<button type="button" onclick="location.href='<?= $this->Url->build(['action' => 'subtract']) ?>'">カウントダウン</button>
<button type="button" onclick="location.href='<?= $this->Url->build(['action' => 'reset']) ?>'">リセット</button>