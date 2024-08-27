<?php
use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProvider */
/** @var int $point */

$this->title = 'Available Vouchers';
?>
<?= Html::a(
    '<i class="fa fa-arrow-left"></i> <', 
    ['/point/index'], 
    ['class' => 'btn btn-secondary', 'title' => 'Quay lại danh sách']
) ?>
<h1><?= Html::encode($this->title) ?></h1>
<p><strong>Điểm hiện có: </strong> <?= $point ?></p>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'image_url',
            'format' => 'html',
            'value' => function ($model) {
                return Html::img($model->image_url, ['width' => '100px']);
            },
        ],
        'name',
        'valid_to',
        [
            'attribute' => 'point',
            'label' => 'Point',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{redeem}',
            'buttons' => [
                'redeem' => function ($url, $model) {
                    return Html::a('Redeem', ['redeem', 'id' => $model->id], [
                        'class' => 'btn btn-primary',
                        'data-method' => 'post',
                        'data-confirm' => 'Are you sure you want to redeem this voucher?',
                    ]);
                },
            ],
        ],
    ],
]); ?>

