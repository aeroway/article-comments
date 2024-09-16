<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->title;
?>

<div class="article-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::encode($model->content) ?></p>

    <h2>Комментарии</h2>
    <div id="comments">
        <?= $this->render('_comments', ['comments' => $model->comments]) ?>
    </div>

    <h3>Добавить комментарий</h3>
    <div id="comment-form">
        <?php $form = ActiveForm::begin(['id' => 'add-comment-form']); ?>

        <!-- Поле для имени -->
        <?= $form->field($commentModel, 'name')->textInput(['maxlength' => true]) ?>

        <!-- Поле для email -->
        <?= $form->field($commentModel, 'email')->input('email') ?>

        <!-- Поле для текста комментария -->
        <?= $form->field($commentModel, 'text')->textarea(['rows' => 4]) ?>

        <!-- Скрытое поле для article_id -->
        <?= $form->field($commentModel, 'article_id')->hiddenInput(['value' => $model->id])->label(false) ?>

        <!-- Кнопка отправки -->
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$script = <<<JS
$('#add-comment-form').on('beforeSubmit', function(e) {
    var form = $(this);
    $.ajax({
        url: form.attr('action'), // Отправка на указанный в форме action URL
        type: 'POST',
        data: form.serialize(), // Сериализуем данные формы для отправки
        success: function(response) {
            if (response.success) {
                // Если успешно, добавляем комментарий в DOM и очищаем форму
                $('#comments').append(response.comment);
                form.trigger('reset');
            } else {
                // Обработка ошибок валидации
                alert('Ошибка добавления комментария: ' + JSON.stringify(response.errors));
            }
        },
        error: function() {
            alert('Ошибка запроса.');
        }
    });
    return false;
});
JS;
$this->registerJs($script);
?>
