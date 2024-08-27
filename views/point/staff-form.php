<?php

use yii\helpers\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProvider */
/** @var app\models\UserProfileSearch $searchModel */


$this->title = 'Danh Sách Nhân Viên';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::a(
    '<i class="fa fa-arrow-left"></i> <', 
    ['index'], 
    ['class' => 'btn btn-secondary', 'title' => 'Return']
) ?>

<div class="staff-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'employee_id',
            'fullname',
            'username',
            'idcard',
            'address:ntext',
            'phonenumber',
            'point',
            [
                'class' => ActionColumn::class,
                'template' => '{select}',
                'buttons' => [
                    'select' => function ($url, $model, $key) {
                        return Html::a('Chọn', $url, [
                            'title' => Yii::t('app', 'Chọn'),
                            'class' => 'btn btn-primary',
                        ]);
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    if ($action === 'select') {
                        return Url::to(['point/select', 'id' => $model['id']]);
                    }
                    return Url::toRoute([$action, 'id' => $model['id']]);
                },
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
