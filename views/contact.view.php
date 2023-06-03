<?php

use app\core\forms\Form;
use app\core\forms\TextField;

?>

<?php $this->title = "Contact";?>

<?php $form = Form::begin('', 'post');?>
<?php echo $form->field($model, 'subject');?>
<?php echo $form->field($model, 'email')->emailField();?>
<?php echo new TextField($model, 'body');?>
<div class="d-flex mt-4">
    <button type="submit" class="btn btn-primary ms-auto">Submit</buttom>
  </div>
<?php echo Form::end();?>