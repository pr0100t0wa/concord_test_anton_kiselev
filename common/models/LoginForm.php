<?php
namespace common\models;

use backend\models\Admin;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_admin;



    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->_admin = new Admin();
    }

    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            [['username', 'password'], 'validateAdmin'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
        ];
    }

    public function validateAdmin($attribute, $params)
    {
        if (!$this->hasErrors()) {

            if (!$this->_admin->validateAdmin($this->password, $this->username)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {

        if ($this->validate()) {

            return true;
//            return Yii::$app->user->login($this->_admin, $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }


}
