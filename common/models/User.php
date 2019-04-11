<?php

namespace common\models;

use Yii;
use yii\base\Security;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string $email
 * @property int $group_id
 * @property string $photo
 * @property string $created_at
 * @property string $updated_at
 * @property Group $group
 */
class User extends \yii\db\ActiveRecord
{
    public $photoPath = '/uploads/';
    public $oldRecord;
    public $password_field;

    public function afterFind()
    {
        $this->oldRecord = clone $this;

        parent::afterFind();

    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password', 'email', 'group_id',  'created_at'], 'required'],
            [['group_id'], 'integer'],
            [['photo'], 'safe'],
            [['photo'], 'file', 'extensions'=>'jpg, gif, png'],
            [['created_at', 'updated_at'], 'safe'],
            [['login', 'password_field'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 64],
            [['photo'], 'string', 'max' => 256],
            [['login'], 'unique'],
            [['email'], 'unique'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['group_id' => 'id']],
        ];
    }

    public function beforeSave($insert) {

        if(!empty($this->password_field))
            $this->password = Yii::$app->security->generatePasswordHash($this->password_field);

        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
            'email' => 'Email',
            'group_id' => 'Group ID',
            'photo' => 'Photo',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'group_id']);
    }


}
