<?php

// Миграция для таблицы комментариев
class m240915_100001_create_comment_table extends \yii\db\Migration
{
    public function safeUp()
    {
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'parent_id' => $this->integer()->null(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Внешний ключ для связи с таблицей статей
        $this->addForeignKey(
            'fk-comment-article_id',
            '{{%comment}}',
            'article_id',
            '{{%article}}',
            'id',
            'CASCADE'
        );

        // Внешний ключ для вложенных комментариев
        $this->addForeignKey(
            'fk-comment-parent_id',
            '{{%comment}}',
            'parent_id',
            '{{%comment}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%comment}}');
    }
}