<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var int $employeeId */
/** @var int $point */
/** @var app\models\AwardPointsForm $model */
?>

<p><strong>Điểm hiện có:</strong> <?= Html::encode($point) ?></p>
<p><strong>Tặng cho nhân viên có ID:</strong> <?= Html::encode($employeeId) ?></p>

<div class="award-points-form">

    <?php $form = ActiveForm::begin([
        'id' => 'award-points-form',
        'action' => ['award-points'],
        'method' => 'post',
    ]); ?>

    <?= $form->field($model, 'id')->hiddenInput([
        'value' => $employeeId
    ])->label(false) ?>

    <?= $form->field($model, 'point')->textInput([
        'type' => 'number',
        'min' => 1,
        'max' => $point,
    ])->label('<strong>Nhập điểm: </strong>', ['encode' => false]) ?>

    <div class="form-group">
        <?= Html::submitButton('Tặng Điểm', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?= Html::a(
    '<i class="fa fa-arrow-left"></i> Quay Lại', 
    ['index'], 
    ['class' => 'btn btn-secondary', 'title' => 'Quay lại danh sách']
) ?>
