<?php

namespace app\modules\user\models;

use Yii;
use yii\web\UploadedFile;
use JBZoo\Image\Image;

/**
 * This is the model class for table "profile".
 *
 * @property integer $user_id
 * @property integer $passport
 * @property integer $birthday
 * @property integer $weight
 * @property integer $height
 * @property integer $eyes
 * @property integer $heir
 * @property integer $education
 * @property integer $religion
 * @property integer $marital_status
 * @property integer $children_count
 * @property integer $lang_proficiency
 * @property integer $smoking
 * @property integer $looking_age_from
 * @property integer $looking_age_to
 * @property integer $intro_age_from
 * @property integer $intro_age_to
 * @property integer $moderated
 * @property string $occupation
 * @property string $lang_name
 * @property string $address
 * @property string $about
 * @property string $ideal_relationship
 * @property string $passport_img_1
 * @property string $passport_img_2
 * @property string $passport_img_3
 * @property string $photos
 * @property string $video
 * @property string $video_about
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{

    public $photo0,$photo1,$photo2,$photo3,$photo4,$photo5;
     /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['passport', 'user_id', 'moderated', 'looking_age_from', 'looking_age_to', 'intro_age_from', 'intro_age_to'], 'integer','message' => 'Must be only numbers.'],
            [['passport', 'weight', 'height', 'eyes', 'heir', 'education', 'religion', 'marital_status', 'children_count', 'lang_proficiency', 'smoking'], 'validateList'],
            [['photo0','photo1','photo2','photo3','photo3','photo4','photo5','passport_img_1','passport_img_2','passport_img_3'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg'],
            [['photo0','photo1','photo2','photo3','photo3','photo4','photo5'], 'image',
                'minHeight' => 600,
                'minWidth' => 600,
                'maxSize' => 1024*1024*2,
                'skipOnEmpty' => true
            ],
            [['passport_img_1','passport_img_2','passport_img_3'], 'image',
                'minHeight' => 800,
                'minWidth' => 800,
                'maxSize' => 1024*1024*6,
                'skipOnEmpty' => true
            ],
            [['video','video_about'],'validateVideo','params'=>['maxSize'=>1024*1024*150]],
            [['occupation', 'lang_name', 'address', 'about', 'ideal_relationship', 'passport_img_1', 'passport_img_2', 'passport_img_3', 'photos', 'video'], 'string', 'max' => 255],
        ];
    }

    public function validateList($attribute, $params){
        if(!($list=$this->getList($attribute))) return true;
        if(!isset($list[$this->$attribute])){
            $this->addError($attribute, 'Error value.');
        }
    }

	public function validateVideo($attribute, $params){
		$file = \yii\web\UploadedFile::getInstance($this, $attribute);
        if ($file) {
			if($file->size>$params['maxSize']){
				$this->addError($attribute, 'Max video size '.((int)($params['maxSize']/(1024*1024))).' Mb ');
			}
		}
	}
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'passport' => 'Passport number',
            'birthday' => 'Birthday',
            'weight' => 'Weight',
            'height' => 'Height',
            'eyes' => 'Eyes',
            'heir' => 'Heir',
            'education' => 'Education',
            'religion' => 'Religion',
            'marital_status' => 'Marital Status',
            'children_count' => 'Children',
            'lang_name' => 'Languages Spoken',
            'lang_proficiency' => 'Lang Proficiency',
            'smoking' => 'Smoking',
            'looking_age_from' => 'Looking Age From',
            'looking_age_to' => 'Looking Age To',
            'intro_age_from' => 'Intro Age From',
            'intro_age_to' => 'Intro Age To',
            'moderated' => 'Moderated',
            'occupation' => 'Occupation',
            'address' => 'Address',
            'about' => 'More about me',
            'ideal_relationship' => 'Ideal Relationship',
            'passport_img_1' => '1-й разворот',
            'passport_img_2' => 'Семейное положение',
            'passport_img_3' => 'Прописка',
            'photos' => 'Personal photo',
            'photo1' => 'Personal photo',
            'photo2' => 'Personal photo',
            'photo3' => 'Personal photo',
            'photo4' => 'Personal photo',
            'photo5' => 'Personal photo',
            'photo0' => 'Personal photo',
            'video' => 'Video',
            'video_about' => 'Video About',
        ];
    }


    /**
     * Поиск пользователя по Id
     * @param int|string $id - ID
     * @return null|static
     */
    public static function findIdentity($id)
    {
        $user = static::findOne(['user_id' => $id]);

        if(!$user) return false;

        //готовим данные для вывода
        $photos=explode(',',$user->photos);
        foreach($photos as $i => $photo){
            $user['photo'.$i]=$photo;
        }

        return $user;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {

        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }

    function getList($param){
        $data_array=array(
            'weight'=>array(
                0   =>'Unknown',
                40  =>'40 kg = 88 lbs',
                41  =>'41 kg = 90 lbs',
                42  =>'42 kg = 92 lbs',
                43  =>'43 kg = 94 lbs',
                44  =>'44 kg = 97 lbs',
                45  =>'45 kg = 99 lbs',
                46  =>'46 kg = 101 lbs',
                47  =>'47 kg = 103 lbs',
                48  =>'48 kg = 105 lbs',
                49  =>'49 kg = 108 lbs',
                50  =>'50 kg = 110 lbs',
                51  =>'51 kg = 112 lbs',
                52  =>'52 kg = 114 lbs',
                53  =>'53 kg = 116 lbs',
                54  =>'54 kg = 119 lbs',
                55  =>'55 kg = 121 lbs',
                56  =>'56 kg = 123 lbs',
                57  =>'57 kg = 125 lbs',
                58  =>'58 kg = 128 lbs',
                59  =>'59 kg = 130 lbs',
                60  =>'60 kg = 132 lbs',
                61  =>'61 kg = 134 lbs',
                62  =>'62 kg = 136 lbs',
                63  =>'63 kg = 139 lbs',
                64  =>'64 kg = 141 lbs',
                65  =>'65 kg = 143 lbs',
                66  =>'66 kg = 145 lbs',
                67  =>'67 kg = 147 lbs',
                68  =>'68 kg = 150 lbs',
                69  =>'69 kg = 152 lbs',
                70  =>'70 kg = 154 lbs',
                71  =>'71 kg = 156 lbs',
                72  =>'72 kg = 158 lbs',
                73  =>'73 kg = 161 lbs',
                74  =>'74 kg = 163 lbs',
                75  =>'75 kg = 165 lbs',
                76  =>'76 kg = 167 lbs',
                77  =>'77 kg = 169 lbs',
                78  =>'78 kg = 172 lbs',
                79  =>'79 kg = 174 lbs',
                80  =>'80 kg = 176 lbs',
                81  =>'81 kg = 178 lbs',
                82  =>'82 kg = 181 lbs',
                83  =>'83 kg = 183 lbs',
                84  =>'84 kg = 185 lbs',
                85  =>'85 kg = 187 lbs',
                86  =>'86 kg = 189 lbs',
                87  =>'87 kg = 192 lbs',
                88  =>'88 kg = 194 lbs',
                89  =>'89 kg = 196 lbs',
                90  =>'90 kg = 198 lbs',
                91  =>'91 kg = 200 lbs',
                92  =>'92 kg = 203 lbs',
                93  =>'93 kg = 205 lbs',
                94  =>'94 kg = 207 lbs',
                95  =>'95 kg = 209 lbs',
                96  =>'96 kg = 211 lbs',
                97  =>'97 kg = 214 lbs',
                98  =>'98 kg = 216 lbs',
                99  =>'99 kg = 218 lbs',
                100 =>'100 kg = 220 lbs',
                101 =>'101 kg = 222 lbs',
                102 =>'102 kg = 225 lbs',
                103 =>'103 kg = 227 lbs',
                104 =>'104 kg = 229 lbs',
                105 =>'105 kg = 231 lbs',
                106 =>'106 kg = 233 lbs',
                107 =>'107 kg = 236 lbs',
                108 =>'108 kg = 238 lbs',
                109 =>'109 kg = 240 lbs',
                110 =>'110 kg = 242 lbs',
                111 =>'111 kg = 245 lbs',
                112 =>'112 kg = 247 lbs',
                113 =>'113 kg = 249 lbs',
                114 =>'114 kg = 251 lbs',
                115 =>'115 kg = 253 lbs',
                116 =>'116 kg = 256 lbs',
                117 =>'117 kg = 258 lbs',
                118 =>'118 kg = 260 lbs',
                119 =>'119 kg = 262 lbs',
                120 =>'120 kg = 264 lbs',
                121 =>'121 kg = 267 lbs',
                122 =>'122 kg = 269 lbs',
                123 =>'123 kg = 271 lbs',
                124 =>'124 kg = 273 lbs',
                125 =>'125 kg = 275 lbs',
                126 =>'126 kg = 278 lbs',
                127 =>'127 kg = 280 lbs',
                128 =>'128 kg = 282 lbs',
                129 =>'129 kg = 284 lbs',
                130 =>'130 kg = 286 lbs',
                131 =>'131 kg = 289 lbs',
                132 =>'132 kg = 291 lbs',
                133 =>'133 kg = 293 lbs',
                134 =>'134 kg = 295 lbs',
                135 =>'135 kg = 298 lbs',
                136 =>'136 kg = 300 lbs',
                137 =>'137 kg = 302 lbs',
                138 =>'138 kg = 304 lbs',
                139 =>'139 kg = 306 lbs',
                140 =>'140 kg = 309 lbs',
                141 =>'141 kg = 311 lbs',
                142 =>'142 kg = 313 lbs',
                143 =>'143 kg = 315 lbs',
                144 =>'144 kg = 317 lbs',
                145 =>'145 kg = 320 lbs',
                146 =>'146 kg = 322 lbs',
                147 =>'147 kg = 324 lbs',
                148 =>'148 kg = 326 lbs',
                149 =>'149 kg = 328 lbs',
                150 =>'150 kg = 331 lbs',
            ),
            'height'=>array(
                0 =>'Unknown',
                '121'=>"121 cm = 4'0\"",
                '124'=>"124 cm = 4'1\"",
                '127'=>"127 cm = 4'2\"",
                '129'=>"129 cm = 4'3\"",
                '132'=>"132 cm = 4'4\"",
                '134'=>"134 cm = 4'5\"",
                '137'=>"137 cm = 4'6\"",
                '139'=>"139 cm = 4'7\"",
                '142'=>"142 cm = 4'8\"",
                '144'=>"144 cm = 4'9\"",
                '147'=>"147 cm = 4'10\"",
                '149'=>"149 cm = 4'11\"",
                '152'=>"152 cm = 5'0\"",
                '154'=>"154 cm = 5'1\"",
                '157'=>"157 cm = 5'2\"",
                '160'=>"160 cm = 5'3\"",
                '162'=>"162 cm = 5'4\"",
                '165'=>"165 cm = 5'5\"",
                '167'=>"167 cm = 5'6\"",
                '170'=>"170 cm = 5'7\"",
                '172'=>"172 cm = 5'8\"",
                '175'=>"175 cm = 5'9\"",
                '177'=>"177 cm = 5'10\"",
                '180'=>"180 cm = 5'11\"",
                '182'=>"182 cm = 6'0\"",
                '185'=>"185 cm = 6'1\"",
                '187'=>"187 cm = 6'2\"",
                '190'=>"190 cm = 6'3\"",
                '193'=>"193 cm = 6'4\"",
                '195'=>"195 cm = 6'5\"",
                '198'=>"198 cm = 6'6\"",
                '200'=>"200 cm = 6'7\"",
                '203'=>"203 cm = 6'8\"",
                '205'=>"205 cm = 6'9\"",
                '208'=>"208 cm = 6'10\"",
                '210'=>"210 cm = 6'11\"",
                '213'=>"213 cm = 7'0\"",
            ),
            'eyes'=>array(
                0=>'Unknown',
                1=>'brown',
                2=>'blue',
                3=>'green',
                4=>'grey',
                5=>'hazel',
            ),
            'heir'=>array(
                0=>'Unknown',
                1=>'black',
                2=>'brown',
                3=>'light-brown',
                4=>'blond',
                5=>'red',
                6=>'white',
                7=>'grey',
                8=>'bald',
            ),
            'education'=>array(
                0=>'Unknown',
                1=>'high school',
                2=>'some college',
                3=>'college',
                4=>'graduate school',
            ),
            'religion'=>array(
                0=>'Unknown',
                1=>'Christianity',
                2=>'Catholic',
                3=>'Orthodoxy',
                4=>'Muslim',
                5=>'Buddhism',
                6=>'Judaism',
                7=>'Atheism',
                8=>'other',
            ),
            'marital_status'=>array(
                0=>'Unknown',
                1=>'never marrie',
                2=>'divorced',
                3=>'widowed',
            ),
            'children_count'=>array(-1=>'Unknown',0=>'No children',1,2,3,4,5,6,7,8),
            'lang_proficiency'=>array(
                0=>'Unknown',
                1=>'basic',
                2=>'intermediate',
                3=>'good',
                4=>'excellent',
            ),
            'smoking'=>array('no','yes'),
        );
        if(!isset($data_array[$param])) return false;
        return $data_array[$param];
    }

    public function getSelectValue($param, $sel){
        $list = Profile::getList($param);
        if(!$list)return false;

        if(is_array($sel))$sel=$sel[$param];
        if($sel==null)return false;
        return $list[$sel];
    }

    public function afterSave($insert, $changedAttributes){
        //Перед сохранением преобрахуем данные в правильный вид

    }

    public function beforeSave($insert){
        //После сохранения обрабатываем файловую информацтю
        //Создаем массив для обновления
        $fileToBd = [];

        //Видео
        if($file=$this->saveVideo('video')){
            $this->removeImage($this->video);
            $fileToBd['video']=$file;
        }
        if($file=$this->saveVideo('video_about')){
            $this->removeImage($this->video_about);
            $fileToBd['video_about']=$file;
        }

        //Страницы паспорта
        if($file=$this->saveImage('passport_img_1')){
            $this->removeImage($this->passport_img_1);
            $fileToBd['passport_img_1']=$file;
        }
        if($file=$this->saveImage('passport_img_2')){
            $this->removeImage($this->passport_img_2);
            $fileToBd['passport_img_2']=$file;
        }
        if($file=$this->saveImage('passport_img_3')){
            $this->removeImage($this->passport_img_3);
            $fileToBd['passport_img_3']=$file;
        }


        $photos=explode(',',$this->photos);
        $fileToBd['photos']=array();
        for($i=0;$i<6;$i++){
            if($file=$this->saveImage('photo'.$i)){
                if(isset($photos[$i])){
                    $this->removeImage($photos[$i]);
                }
                $fileToBd['photos'][]=$file;
            }else{
                if(isset($photos[$i])) {
                    if ($photos[$i] != $this['photo' . $i]) {
                        $this->removeImage($photos[$i]);
                    } else {
                        $fileToBd['photos'][]=$photos[$i];
                    }
                }
            }
        }
        $fileToBd['photos']=implode(',',$fileToBd['photos']);

        //день рождения
        $request = Yii::$app->request;
        $post = $request->post();

        $class = $this::className();
        $class = str_replace('\\', '/', $class);
        $class = explode('/', $class);
        $class=$class[count($class)-1];
        if(strlen($post[$class]['birthday'])>3) {
            $birthday = (strtotime($post[$class]['birthday']));
            $fileToBd['birthday'] = $birthday;
        }

        //var_dump($fileToBd);
        $this::getDb()
            ->createCommand()
            ->update($this->tableName(), $fileToBd, ['user_id' => $this->user_id])
            ->execute();
        return true;
    }

    /**
     * Сохранение изображения (аватара)
     * пользвоателя
     */
    public function saveImage($name)
    {
        $file = \yii\web\UploadedFile::getInstance($this, $name);

        if ($file) {
            $path=Yii::$app->user->identity->userDir;// Путь для сохранения файлов

            $name = rand ( 100000000000 , 999999999999 ); // Название файла
            $exch = explode('.',$file->name);
            $exch=$exch[count($exch)-1];
            $name .= '.' . $exch;
            $outFile = $path . $name ;   // Путь файла и название
            if (!file_exists($path)) {
                mkdir($path, 0777, true);   // Создаем директорию при отсутствии
            }

            $img = (new Image($file->tempName));

            $img->fitToWidth(1024)
                ->saveAs($outFile);


            if($img) {
                return $outFile;
            }else{
                return false;
            }
        }
    }

    public function saveVideo($name)
    {
        $file = \yii\web\UploadedFile::getInstance($this, $name);

        if ($file) {

            $path=Yii::$app->user->identity->userDir;// Путь для сохранения файлов

            $name = rand ( 100000000000 , 999999999999 ); // Название файла
            $exch = explode('.',$file->name);
            $exch=$exch[count($exch)-1];
            $name .= '.' . $exch;
            $outFile = $path . $name ;   // Путь файла и название
            if (!file_exists($path)) {
                mkdir($path, 0777, true);   // Создаем директорию при отсутствии
            }

            if($file->saveAs($outFile)) {
                return $outFile;
            }else{
                return false;
            }
        }
    }

    /**
     * Удаляем изображение при его наличии
     */
    public function removeImage($img)
    {
        if ($img) {
            // Если файл существует
            if (file_exists($img)) {
                unlink($img);
            }
        }
    }

    /*public function getCard()
    {
        return $this->hasMany(User::className(), ['user_id' => 'id']);
    }*/
}
