<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
?>

<div class="comments-section" data-article-id="<?= $articleId ?>">
    <h4>Добавить первый комментарий:</h4>

    <?php Pjax::begin(['id' => 'new-comment']); ?>
        <?php $form = ActiveForm::begin([
            'id' => 'first-comment-form',
            'action' => ['/article/add-comment'],  // Действие для добавления комментария
            'options' => ['data-pjax' => true],    // Включаем поддержку PJAX
        ]); ?>

        <?= $form->field($commentModel, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($commentModel, 'email')->input('email') ?>
        <?= $form->field($commentModel, 'text')->textarea(['rows' => 4]) ?>
        <?= $form->field($commentModel, 'article_id')->hiddenInput(['value' => $articleId])->label(false) ?>
        <?= $form->field($commentModel, 'verifyCode')->widget(\yii\captcha\Captcha::class, [
            'captchaAction' => 'site/captcha',  // Путь к действию CAPTCHA
            'options' => ['class' => 'form-control'],
        ])->label('Введите код с картинки') ?>

        <?= Html::submitButton('Добавить комментарий', ['class' => 'btn btn-primary']) ?>

        <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>
</div>

<!-- Скрипт для AJAX отправки комментариев -->
<?php
$script = <<<JS
$('#first-comment-form').on('beforeSubmit', function(event) {
    event.preventDefault();  // Предотвращаем стандартную отправку формы

    var form = $(this);
    var articleId = form.find('input[name="Comment[article_id]"]').val(); // Получаем ID статьи

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(response) {
            if (response.success) {
                // Найдем соответствующий контейнер для комментариев на основе articleId
                $('.comments-section[data-article-id="' + articleId + '"]').append(response.comment);
                form.trigger('reset');  // Очищаем форму после добавления
            } else {
                // Если возникли ошибки, выводим их
                alert('Ошибка добавления комментария: ' + JSON.stringify(response.errors));
            }
        },
        error: function() {
            alert('Произошла ошибка при отправке комментария.');
        }
    });

    return false;  // Предотвращаем стандартную отправку формы
});
JS;

$this->registerJs($script);
?>