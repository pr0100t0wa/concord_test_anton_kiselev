<?php

namespace backend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

class Admin implements IdentityInterface
{

    public $username = 'admin';
    public $password = 'admin';
    private $auth_key;

    public function __construct()
    {

        $this->auth_key = Yii::$app->security->generateRandomString();
    }


    public function validateAdmin($password, $username){
           if($password == $this->password && $username == $this->username){
               return true;
           }
           return false;
    }

    public static function findIdentity($id)
    {
        return new Admin();
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId()
    {
        return 0;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        throw new NotSupportedException('"validateAuthKey" is not implemented.');
    }

}

