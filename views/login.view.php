<?php $this->title = "Login";?>

<h2 class="text-center text-warning">Login Account</h2>

<?php $form = app\core\forms\Form::begin('', 'post'); //echo form tag and initiate Form Obj?> 
  
  <?php echo $form->field($model, 'email')->emailField();?>
  <?php echo $form->field($model, 'password')->passwordField();  // __tpString work when echo before that line do work?> 
  <div class="d-flex mt-4">
    <button type="submit" class="btn btn-primary ms-auto">Submit</buttom>
  </div>
  
<?php echo app\core\forms\Form::end();?>