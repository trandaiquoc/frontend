<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $voucher */

$this->title = 'Voucher Redeemed';
?>

<h1><?= Html::encode($this->title) ?></h1>

<p><strong>Voucher Code:</strong> <?= Html::encode($voucher['code']) ?></p>
<p><strong>Thank you!</strong> Your voucher has been redeemed successfully.</p>

<?= Html::a('Return to Vouchers', ['index'], ['class' => 'btn btn-primary']) ?>
