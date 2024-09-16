<?php

namespace app\models;

use yii\db\ActiveRecord;

class Article extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%article}}';
    }

    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
        ];
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, ['article_id' => 'id'])->where(['parent_id' => null]);
    }
}
