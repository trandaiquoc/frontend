<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Đăng nhập';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login w-50 container border rounded-1 px-5 pt-3">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <div>
        <div>
            <?php
            $form = ActiveForm::begin(
                ['fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => [
                        'class' => 'form-label' // Cập nhật class cho nhãn
                    ],
                    'inputOptions' => [
                        'class' => 'form-control' // Cập nhật class cho input
                    ],
                    'errorOptions' => [
                        'class' => 'invalid-feedback' // Cập nhật class cho lỗi
                    ]
                ]]
            );
            ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Tên đăng nhập') ?>

            <?= $form->field($model, 'password')->passwordInput()->label('Mật khẩu') ?>

            <?= $form->field($model, 'rememberMe')->checkbox()->label('Ghi nhớ đăng nhập') ?>

            <div class="form-group text-center">
                <?= Html::submitButton('Đăng nhập', ['class' => 'btn btn-primary px-3 py-1', 'name' => 'login-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>