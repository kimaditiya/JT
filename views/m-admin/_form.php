<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MAdmin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="madmin-form">

    <?php $form = ActiveForm::begin([
	    'id'=>$model->formName(),
	   'layout' => 'horizontal'

   ]); ?>


    <?= $form->field($model, 'adminPassword')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'adminNama')->textInput(['maxlength' => true]) ?>

      <?php
    if(!$model->isNewRecord){
    ?>


   <?= $form->field($model, 'adminStatus')->checkbox(); ?>

    <?php
}

    ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
