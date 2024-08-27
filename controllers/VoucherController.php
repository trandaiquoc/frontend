<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\httpclient\Client;
use yii\data\ArrayDataProvider;
use app\models\Voucher;
use yii\web\NotFoundHttpException;

class VoucherController extends Controller
{
    private $apiUrl = "https://mocki.io/v1/244d9ba5-2e10-452e-a36c-04875fa5ca0e";

    public function actionIndex()
    {
        // Lấy điểm hiện có từ session
        $userSession = Yii::$app->session->get('user');
        $point = isset($userSession['point']) ? $userSession['point'] : 0;

        $client = new Client();
        $response = $client->get($this->apiUrl)->send();

        if ($response->isOk) {
            $data = $response->data;
            $vouchers = [];
            foreach ($data['vouchers'] as $item) {
                $voucher = new Voucher();
                $voucher->attributes = $item;
                $vouchers[] = $voucher;
            }

            $dataProvider = new ArrayDataProvider([
                'allModels' => $vouchers,
                'pagination' => [
                    'pageSize' => 5,
                ],
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'point' => $point,
            ]);
        } else {
            throw new \yii\web\HttpException(500, 'Cannot fetch vouchers.');
        }
    }

    public function actionRedeem($id)
    {
        // Fetch voucher data from external API
        $client = new Client();
        $response = $client->get($this->apiUrl)->send();

        if ($response->isOk) {
            $vouchers = $response->data['vouchers'];
            $voucher = null;
            foreach ($vouchers as $item) {
                if ($item['id'] == $id) {
                    $voucher = $item;
                    break;
                }
            }

            if (!$voucher) {
                throw new NotFoundHttpException('Voucher not found.');
            }

            // Check current points
            $currentPoints = Yii::$app->session->get('user')['point'];
            if ($currentPoints < $voucher['point']) {
                Yii::$app->session->setFlash('error', 'Not enough points to redeem this voucher.');
                return $this->redirect(['index']);
            }
            $user = Yii::$app->session->get('user');
            // Kiểm tra nếu thông tin người dùng đã tồn tại và có điểm
            if (isset($user) && isset($user['point'])) {
                // Giảm điểm
                $user['point'] -= $voucher['point'];
                
                // Cập nhật lại giá trị điểm trong session
                Yii::$app->session->set('user', $user);
            }

            // Call API to deduct points
            $apiClient = new Client();
            $apiResponse = $apiClient->get('http://localhost/backend/web/user-profile/add-point-to-staff', [
                'senderId' => "System",
                'plus' => -$voucher['point'],
                'receiverId' => Yii::$app->session->get('user')['id'],
            ])->send();

            if ($apiResponse->isOk) {
                Yii::$app->session->setFlash('success', 'Voucher redeemed successfully!');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to redeem voucher.');
            }

            return $this->render('redeem', [
                'voucher' => $voucher,
            ]);
        } else {
            throw new \yii\web\HttpException(500, 'Cannot fetch vouchers.');
        }
    }
}
