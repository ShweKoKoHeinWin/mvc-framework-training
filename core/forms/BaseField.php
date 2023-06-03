<?php

namespace app\core\forms;

use app\core\Model;

    abstract class BaseField
    {
        public Model $model;
        public string $attritube;

        public function __construct(Model $model, $attribute)
        {
            $this->model = $model;
            $this->attribute = $attribute;
        }

        abstract public function renderInput() : string;

        public function __toString()    //once we echo obj this will work
        {
            return sprintf(
                '<div class="form-group mb-2">
                    <label>%s</label>
                    %s
                    <em class="text-muted">%s</em>
                </div>',
                $this->model->getLabel($this->attribute),  // to show labels for user friendly call from getLabel()
               $this->renderInput(),
                $this->model->getFirstError($this->attribute)
            );
        }
    }

?>