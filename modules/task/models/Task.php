<?php

namespace app\modules\task\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $task_id
 * @property integer $date_todo
 * @property integer $created_at
 * @property string $params
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'task_id', 'date_todo'], 'required'],
            [['user_id', 'task_id', 'date_todo', 'created_at'], 'integer'],
            [['params'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'task_id' => 'Task ID',
            'date_todo' => 'Date Todo',
            'created_at' => 'Created At',
            'params' => 'Params',
        ];
    }

    public function beforeValidate()
    {
      if($this->isNewRecord){
        $this->created_at=time();
      }
      return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }
}
