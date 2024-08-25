<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var int $point */
?>

<h1>Quản Lý Điểm Thưởng</h1>

<p><strong>Điểm hiện có:</strong> <?= Html::encode($point) ?></p>

<div style="display: flex; gap: 10px;">
    <?php if (!empty(Yii::$app->session->get('user')['staffids'])): ?>
        <?= Html::a('Tặng Điểm', Url::to(['point/get-staff', 'ids' => Yii::$app->session->get('user')['staffids']]), ['class' => 'btn btn-primary']) ?>
    <?php endif; ?>
    
    <?= Html::a('Đổi Voucher', Url::to(['voucher/index']), ['class' => 'btn btn-success']) ?>
</div>