<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;

?>

<div class="comments-section" data-article-id="<?= $articleId ?>">
    <?php foreach ($comments as $comment): ?>
        <div class="comment">
            <p><strong><?= Html::encode($comment->name) ?>:</strong></p>
            <p><?= Html::encode($comment->text) ?></p>

            <!-- Форма ответа на комментарий -->
            <div class="reply-form">
                <?php Pjax::begin(['id' => 'reply-comment-' . $comment->id]); ?>
                    <?php $form = ActiveForm::begin([
                        'id' => 'comment-form-' . $comment->id,
                        'action' => ['/article/add-comment'],
                        'options' => ['data-pjax' => true]
                    ]); ?>

                    <?= $form->field($commentModel, 'name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($commentModel, 'email')->input('email') ?>
                    <?= $form->field($commentModel, 'text')->textarea(['rows' => 2]) ?>
                    <?= $form->field($commentModel, 'article_id')->hiddenInput(['value' => $articleId])->label(false) ?>
                    <?= $form->field($commentModel, 'parent_id')->hiddenInput(['value' => $comment->id])->label(false) ?>
                    <?= $form->field($commentModel, 'verifyCode')->widget(\yii\captcha\Captcha::class) ?>

                    <?= Html::submitButton('Ответить', ['class' => 'btn btn-primary']) ?>

                    <?php ActiveForm::end(); ?>
                <?php Pjax::end(); ?>
            </div>

            <!-- Вложенные ответы -->
            <?php if ($comment->replies): ?>
                <div id="comments-section-<?= $comment->id ?>">
                    <?= Yii::$app->controller->renderPartial('_comments', ['comments' => $comment->replies, 'commentModel' => $commentModel, 'articleId' => $articleId]) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
<!-- Скрипт для AJAX отправки комментариев -->
<?php
$script = <<<JS
$(document).on('beforeSubmit', '[id^="comment-form-"]', function(event) {
    event.preventDefault();  // Предотвращаем стандартное поведение формы

    var form = $(this);
    var commentId = form.find('input[name="Comment[parent_id]"]').val();  // Получаем ID родительского комментария

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(response) {
            if (response.success) {
                // Добавляем новый комментарий в соответствующий контейнер для вложенных комментариев
                $('#comments-section-' + commentId).append(response.comment);
                form.trigger('reset');  // Очищаем форму после добавления
            } else {
                // Обрабатываем ошибки
                alert('Ошибка добавления комментария: ' + JSON.stringify(response.errors));
            }
        },
        error: function() {
            alert('Произошла ошибка при отправке комментария.');
        }
    });

    return false;  // Останавливаем стандартную отправку формы
});
JS;

$this->registerJs($script);
?>