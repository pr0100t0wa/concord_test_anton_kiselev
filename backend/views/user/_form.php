<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\models\Group;
use \kartik\datetime\DateTimePicker;
use \kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'login')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_field')->passwordInput(['maxlength' => true])->label('Password') ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'group_id')->dropdownList(
        Group::find()->select(['name', 'id'])->indexBy('id')->column()) ?>


    <?= $form->field($model, 'photo')->widget(FileInput::class, [
            'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                'allowedFileExtensions' => ['jpg', 'gif', 'png'],
                'initialPreview' => [$model->photo],
                'initialPreviewAsData'=>true,
            ]
        ])
    ?>


    <?php if (!$model->isNewRecord) echo $form->field($model, 'created_at')->widget(DateTimePicker::class); ?>

    <?php if (!$model->isNewRecord) echo $form->field($model, 'updated_at')->textInput()->widget(DateTimePicker::class) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
