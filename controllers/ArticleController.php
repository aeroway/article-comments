<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Article;
use app\models\Comment;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\web\Response;

class ArticleController extends Controller
{
    public function actionIndex()
    {
        $articleModel = new Article();  // Модель для новой статьи
        $commentModel = new Comment();  // Модель для комментария

        // Обработка добавления статьи
        if ($articleModel->load(Yii::$app->request->post()) && $articleModel->save()) {
            // Если статья добавлена, очищаем модель
            $articleModel = new Article();
        }

        // Настройка DataProvider для вывода статей через GridView
        $dataProvider = new ActiveDataProvider([
            'query' => Article::find()->with('comments'),
            'pagination' => ['pageSize' => 5],
        ]);

        return $this->render('index', [
            'articleModel' => $articleModel,
            'commentModel' => $commentModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // Обработка добавления комментариев
    public function actionAddComment()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;  // Возвращаем JSON-ответ для AJAX
        $commentModel = new Comment();
    
        if ($commentModel->load(Yii::$app->request->post()) && $commentModel->save()) {
            // Рендерим новый комментарий
            return [
                'success' => true,
                'comment' => $this->renderPartial('_comment', ['comment' => $commentModel])
            ];
        }
    
        // Возвращаем ошибки валидации, если сохранение не удалось
        return [
            'success' => false,
            'errors' => $commentModel->errors
        ];
    }
}
