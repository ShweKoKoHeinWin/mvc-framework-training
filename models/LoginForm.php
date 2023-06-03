<?php

namespace app\models;

use app\core\Application;
use app\core\Model;

    class LoginForm extends Model
    {
        public string $email = "";
        public string $password = "";
        public function rules() : array
        {
            return [
                'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
                'password' => [self::RULE_REQUIRED]
            ];
        }

        public function login()
        {
            $user = User::findOne(['email' => $this->email]);
            if(!$user) {    // get sql data to check if data is not exist
                $this->addError('email', 'User does not exist with this email'); //no exist then add error
                return false;   //stop program
            }

            if(!password_verify($this->password, $user->password)) {    //check if password is not correct
                $this->addError('password', 'Password is incorrect');   //add password error
                return false;   //stop program
            }

            return Application::$app->login($user); // all correct do login
        }

        public function labels() : array
        {
            return [
                'email' => 'Your email',
                'password' => 'Password'
            ];
        }
    }

?>