<?php
use yii\bootstrap5\Modal;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $employees */

Modal::begin([
    'title' => 'Danh Sách Nhân Viên',
    'id' => 'employee-modal',
    'size' => 'modal-lg',
]);

echo "<div id='employee-list'>";
foreach ($employees as $employee) {
    echo "<p>" . Html::encode($employee['fullname']) . "</p>";
}
echo "</div>";

Modal::end();

