<?php

namespace app\controllers;

use Yii;
use app\models\Comment;
use yii\web\Controller;
use yii\web\Response;

class CommentController extends Controller
{
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON; // Формат ответа JSON для AJAX
        $model = new Comment();

        // Загружаем данные из POST-запроса
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Успешное сохранение, возвращаем JSON-ответ
            return ['success' => true, 'comment' => $this->renderPartial('_comment', ['comment' => $model])];
        }
die('ok');
        // Ошибка сохранения, возвращаем ошибки в JSON-формате
        return ['success' => false, 'errors' => $model->errors];
    }
}
