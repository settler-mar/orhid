<?php

namespace app\modules\user\models;

use Yii;

/**
 * This is the model class for table "lb_country".
 *
 * @property integer $id
 * @property string $name_ru
 * @property string $name
 * @property string $code
 * @property string $currency_code
 * @property string $currency
 * @property string $iso
 * @property string $timezone
 *
 * @property LbCity[] $lbCities
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lb_country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_ru', 'currency'], 'string', 'max' => 255],
            [['name', 'timezone'], 'string', 'max' => 100],
            [['code'], 'string', 'max' => 2],
            [['currency_code'], 'string', 'max' => 5],
            [['iso'], 'string', 'max' => 3],
            ['in_white', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_ru' => 'Name Ru',
            'name' => 'Name',
            'code' => 'Code',
            'currency_code' => 'Currency Code',
            'currency' => 'Currency',
            'iso' => 'Iso',
            'timezone' => 'Timezone',
            'flag' => 'Flag',
            'flag' => 'Flag',
            'in_white'=>'In white list'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLbCities()
    {
        return $this->hasMany(LbCity::className(), ['country_id' => 'id']);
    }
}
