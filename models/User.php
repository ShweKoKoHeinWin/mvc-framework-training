<?php

namespace app\models;

use app\core\UserModel;
use app\core\Application;

    class User extends UserModel
    {
        const STATUS_INACTIVE = 0;
        const STATUS_ACTIVE = 1;
        const STATUS_DELECTED = 2;

        public string $name = '';
        public string $email = '';
        public string $password = '';
        public string $passwordConfirm = '';
        public int $status = self::STATUS_INACTIVE;

        public function tableName():string 
        {
            return "users";
        }

        public function save() 
        {
            $this->status = self::STATUS_INACTIVE;
            return $this->save();
        }

        public function register()
        {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            
            return parent::save();
        }

        public function rules(): array {
            return [
                'name' => [self::RULE_REQUIRED],
                'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class' => self::class]],
                'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 24]],
                'passwordConfirm' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']]
            ];
        }

        public function attributes() : array 
        {
            return ['name' , 'email' , 'password', 'status'];
        }

        public function labels() :array {
            return [
                'name' => "Name",
                'email' => "Email",
                'password' => "Password",
                'passwordConfirm' => "Confirm Password"
            ];
        }

        public function primaryKey() : string 
        {
            return 'id';
        }

        public function getDisplayName() : string
        {
            return $this->name;
        }
    }

?>