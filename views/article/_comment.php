<?php
use yii\helpers\Html;
?>

<div class="comment">
    <p><strong><?= Html::encode($comment->name) ?>:</strong></p>
    <p><?= nl2br(Html::encode($comment->text)) ?></p>
    <p><small><?= Html::encode($comment->created_at) ?></small></p>
</div>
