<?php
namespace console\controllers;

use yii\console\Controller;
use Yii;

class MonthlyPointsController extends Controller
{
    public function actionIndex()
    {
        // Gọi actionDistributeMonthlyPoints từ PointController
        Yii::$app->runAction('point/distribute-monthly-points');
    }
}

//chỉnh run_monthly_points.bat
// cd /d D:\PHP\frontend thay đổi thư mục làm việc đến nơi chứa file yii.
// D:\App\wamp64\bin\php\php8.3.6\php.exe là đường dẫn đến trình thông dịch PHP của bạn.
// yii monthly-points/index là lệnh để chạy console command của Yii2.
