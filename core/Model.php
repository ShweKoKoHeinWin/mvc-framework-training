<?php

namespace app\core;

    abstract class Model // to handle form data
    {//define constant of error
        public const RULE_REQUIRED = 'required';
        public const RULE_EMAIL = 'email';
        public const RULE_MIN = 'min';
        public const RULE_MAX = 'max';
        public const RULE_MATCH = 'match';
        public const RULE_UNIQUE = 'unique';

        public array $errors = []; //to collect errors

        public function loadData($data) //load data from form
        {
            foreach($data as $key => $value) {
                if(property_exists($this, $key)) { //assign data value pairs to handlerModel
                    $this->{$key} = $value;
                    
                }
            }
        }

        public function labels() : array
        {
            return [];
        }

        public function getLabel($attribute)
        {
            return $this->labels()[$attribute] ?? $attribute;
        }

        public abstract function rules(): array;    //must defined rules

        public function validate()  //test data
        {
            foreach($this->rules() as $attribute => $rules) {
                $value = $this->{$attribute};
                foreach($rules as $rule) {
                    $rulename = $rule;
                    if(!is_string($rulename)) {
                        $rulename = $rule[0];
                    }

                    if($rulename === self::RULE_REQUIRED && !$value) {
                        $this->addErrorForRules($attribute, self::RULE_REQUIRED);
                    }

                    if($rulename === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $this->addErrorForRules($attribute, self::RULE_EMAIL);
                    }

                    if($rulename === self::RULE_MIN && strlen($value) < $rule['min']) {
                        $this->addErrorForRules($attribute, self::RULE_MIN, $rule);
                    }

                    if($rulename === self::RULE_MAX && strlen($value) > $rule['max']) {
                        $this->addErrorForRules($attribute, self::RULE_MAX, $rule);
                    }

                    if($rulename === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                        $rule['match'] = $this->getLabel($rule['match']);   //makeing error message user friendly by doing $rule['match] to user display password label;
                        $this->addErrorForRules($attribute, self::RULE_MATCH, $rule);
                    }

                    if($rulename === self::RULE_UNIQUE) {
                        $className = $rule['class'];
                        $uniqueAttr = $rule['attribute'] ?? $attribute;
                        $tableName = $className::tableName();

                      
                        $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                        $statement->bindValue(":attr", "$value");
                        $statement->execute();
                        $record = $statement->fetchObject();

                        if($record) {
                            $this->addErrorForRules($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);   //addErrorForRules with $attribute and 
                        }
                    }
                }
            }
            return empty($this->errors);
        }

        private function addErrorForRules(string $attribute, string $rule, $params = [])
        {
            $message = $this->errorMessage()[$rule] ?? "";
            foreach($params as $key => $value) {
                $message = str_replace("{{$key}}", $value, $message);
            }
            $this->errors[$attribute][] = $message;
        }

        public function addError(string $attribute, string $message)
        {
            $this->errors[$attribute][] = $message;
        }

        public function errorMessage()
        {
            return [
                self::RULE_REQUIRED => 'This field isrequired',
                self::RULE_EMAIL => 'Enter valid email',
                self::RULE_MIN => 'Min length of this field must be {min}',
                self::RULE_MAX => 'Max length of this field must be {max}',
                self::RULE_MATCH => 'This field must be the same as {match}',
                self::RULE_UNIQUE => 'Record with this {field} already exists'
            ];
        }

        public function hasError($attribute) {
            return $this->errors[$attribute] ?? false;
        }

        public function getFirstError($attribute) {
            return $this->errors[$attribute][0] ?? false;
        }
    }

?>
