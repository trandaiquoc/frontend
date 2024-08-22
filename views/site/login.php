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
                        'class' => 'form-label'
                    ],
                    'inputOptions' => [
                        'class' => 'form-control'
                    ],
                    'errorOptions' => [
                        'class' => 'invalid-feedback'
                    ]
                ]]
            );
            ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div class="form-group text-center">
                <?= Html::submitButton('Đăng nhập', ['class' => 'btn btn-primary px-3 py-1', 'name' => 'login-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>