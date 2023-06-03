<?php 

namespace app\core\forms;

use app\core\Model;
    class InputField extends BaseField
    {
        public const TYPE_TEXT = 'text';
        public const TYPE_PASSWORD = 'password';
        public const TYPE_EMAIL = 'email';
        public const TYPE_NUMBER = 'number';
        public const TYPE_FILE = 'file';

        public string $fieldType = "text";

        public function __construct(Model $model,string $attribute)
        {
            parent::__construct($model, $attribute);
            $this->fieldType = self::TYPE_TEXT;
        }

        public function passwordField()
        {
            $this->fieldType = self::TYPE_PASSWORD;
            return $this;   //return field to make __toString work when echo this;
        }

        public function emailField()
        {
            $this->fieldType = self::TYPE_EMAIL;
            return $this;
        }

        public function fileField()
        {
            $this->fieldType = self::TYPE_File;
            return $this;
        }
        public function numberField()
        {
            $this->fieldType = self::TYPE_NUMBER;
            return $this;
        }

        public function renderInput() : string
        {
            return sprintf('<input type="%s" name="%s" value="%s" class="form-control %s">',
            $this->fieldType,
            $this->attribute, 
            $this->model->{$this->attribute}, 
            $this->model->hasError($this->attribute) ? "is-invalid" : "",
        );
        }
    }

?>