<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MKelurahanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mkelurahan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'kelurahanId') ?>

    <?= $form->field($model, 'kelurahanNama') ?>

    <?= $form->field($model, 'kecamatanId') ?>

    <?= $form->field($model, 'hargaDaerah') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>