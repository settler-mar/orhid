<?php

namespace app\modules\tarificator\models;

use Yii;
use app\modules\tariff\models\Tariff;
use yii\helpers\Json;
/**
 * This is the model class for table "tarificatorTable".
 *
 * @property integer $id
 * @property string $name
 * @property double $price
 * @property string $description
 * @property string $includeData
 */
class TarificatorTable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tarificatorTable';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','timer', 'price', 'description','color'], 'required'],
            [['timer'], 'integer'],
            [['price'], 'number'],
            [['description','color'], 'string'],
            [['name'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'timer' => 'Time',
            'price' => 'Price',
            'color' => 'Color',
            'description' => 'Description',
            'includeData' => 'Include Data',
        ];
    }
    public function beforeSave($insert)
    {
        $query = Tariff::find()->all();
        $request = Yii::$app->request->post();
        foreach ($query as $t){
            if ($request['checkBox_'.$t->code]=='1') {
                $jsonArray[$t->code] = "unlimited";
            }
            else if ($request['inputText_'.$t->code]!="") $jsonArray[$t->code] = $request['inputText_'.$t->code];
        }
        $this->includeData = json_encode($jsonArray);
        return parent::beforeSave($insert);
    }
}
