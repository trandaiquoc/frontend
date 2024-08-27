<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\FormatConverter;

/** @var yii\web\View $this */
/** @var int $point */
/** @var array $logData */
/** @var int $userId */
?>

<h1>Quản Lý Điểm Thưởng</h1>

<p><strong>Điểm hiện có:</strong> <?= Html::encode($point) ?></p>

<div style="display: flex; gap: 10px;">
    <?php if (!empty(Yii::$app->session->get('user')['staffids'])): ?>
        <?= Html::a('Tặng Điểm', Url::to(['point/get-staff', 'ids' => Yii::$app->session->get('user')['staffids']]), ['class' => 'btn btn-primary']) ?>
    <?php endif; ?>
    
    <?= Html::a('Đổi Voucher', Url::to(['voucher/index']), ['class' => 'btn btn-success']) ?>
</div>
<br/>
<h2>Biến Động Điểm Thưởng</h2>

<?php if (!empty($logData)): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Ngày</th>
                    <th>Hoạt Động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logData as $log): ?>
                    <?php if ($log['pointsadded'] > 0): ?>
                        <tr>
                            <td><?= Html::encode(date('d/m/Y H:i:s', strtotime($log['createdat']))) ?></td>
                            <td>
                                <?php if ($log['senderid'] == $userId): ?>
                                    Bạn đã tặng nhân viên <strong><?= Html::encode($log['receivername']) ?></strong> <?= Html::encode($log['pointsadded']) ?> điểm.
                                <?php elseif ($log['receiverid'] == $userId && $log['sendername'] != 'System'): ?>
                                    Bạn đã nhận <?= Html::encode($log['pointsadded']) ?> điểm từ quản lý <strong><?= Html::encode($log['sendername']) ?></strong>.
                                <?php else: ?>
                                    Bạn được thưởng <?= Html::encode($log['pointsadded']) ?> điểm cho tháng <strong><?= Html::encode(date('m/Y', strtotime($log['createdat']))) ?></strong>.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info" role="alert">
        Không có biến động điểm thưởng nào được tìm thấy.
    </div>
<?php endif; ?>
