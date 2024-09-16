<?php

namespace app\models;

use yii\db\ActiveRecord;

class Comment extends ActiveRecord
{
    public $verifyCode;

    public static function tableName()
    {
        return '{{%comment}}';
    }

    public function rules()
    {
        return [
            [['article_id', 'name', 'email', 'text'], 'required'],
            [['email'], 'email'],
            [['parent_id', 'article_id'], 'integer'],
            [['text'], 'string'],
            [['verifyCode'], 'captcha'],
        ];
    }

    public function getReplies()
    {
        return $this->hasMany(Comment::class, ['parent_id' => 'id']);
    }

    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Captcha',
        ];
    }
}
