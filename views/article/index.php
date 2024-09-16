<?php

use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use app\models\Article;
use yii\helpers\Url;

$this->title = 'Статьи';

?>

<h1><?= Html::encode($this->title) ?></h1>

<!-- Форма добавления статьи -->
<?php Pjax::begin(); ?>
<div class="article-form">
    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>

    <?= $form->field($articleModel, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($articleModel, 'content')->textarea(['rows' => 6]) ?>

    <?= Html::submitButton('Добавить статью', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>
</div>


<hr>

<!-- Вывод статей с комментариями -->

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'title',
        'content',
        [
            'label' => 'Комментарии',
            'format' => 'raw',
            'value' => function ($model) use ($commentModel) {
                if (count($model->comments) > 0) {
                    // Если есть комментарии, отображаем их
                    return Yii::$app->controller->renderPartial('_comments', ['comments' => $model->comments, 'commentModel' => $commentModel, 'articleId' => $model->id]);
                } else {
                    // Если нет комментариев, отображаем кнопку "Добавить первый комментарий"
                    return Yii::$app->controller->renderPartial('_first_comment', ['commentModel' => $commentModel, 'articleId' => $model->id]);
                }
            },
        ],
    ],
]); ?>
<?php Pjax::end(); ?>
