<?php

namespace app\controllers;

use Yii;
use app\models\Point;
use app\models\PointSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\httpclient\Client;
use yii\web\Response;
use app\models\AwardPointsForm;
use yii\data\ArrayDataProvider;

/**
 * PointController implements the CRUD actions for Point model.
 */
class PointController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Point models.
     *
     * @return string|Response
     */
    public function actionIndex()
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Yii::$app->session->has('user') || !Yii::$app->session->has('token')) {
            return $this->redirect(['site/login']);
        }

        $user = Yii::$app->session->get('user');
        $id = $user['id']; // Lấy ID của người dùng từ session

        // Gửi yêu cầu đến API để lấy điểm
        $client = new Client();
        $response = $client->get('http://localhost/backend/web/user-profile/get', ['id' => $id])->send();

        if ($response->isOk) {
            $data = $response->data;

            // Kiểm tra xem có dữ liệu điểm không
            if (isset($data['data']['point']) && $data['data']['point'] !== null) {
                $point = $data['data']['point'];

                return $this->render('index', [
                    'point' => $point
                ]);
            } else {
                Yii::$app->session->setFlash('error', 'Điểm không tìm thấy cho ID người dùng .');
                return $this->redirect(['site/login']);
            }
        } else {
            Yii::$app->session->setFlash('error', 'Lỗi khi lấy dữ liệu từ API.');
            return $this->redirect(['site/login']);
        }
    }

    public function actionSelect($id)
    {
        $model = new AwardPointsForm();
        return $this->render('select-view', [
            'employeeId' => $id,
            'point' => Yii::$app->session->get('user')['point'],
            'model' => $model
        ]);
    }

    public function actionGetStaff($ids = null)
    {
        $employees = [];
        if ($ids) {
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl('http://localhost/backend/web/user-profile/get-staff')
                ->setData(['ids' => $ids])
                ->send();

            if ($response->isOk && $response->data['success']) {
                $employees = $response->data['data'];
            } else {
                Yii::$app->session->setFlash('error', 'Không thể lấy danh sách nhân viên.');
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $employees,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('staff-form', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAwardPoints()
{
    // Khởi tạo mô hình
    $model = new AwardPointsForm();

    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        $client = new Client();
        $user = Yii::$app->session->get('user')['id'];
        // Danh sách các cặp id và điểm (thay thế bằng dữ liệu thực tế)
        $dataList = [
            ['id' => $model->id, 'point' => $model->point],
            ['id' => $user, 'point' => -$model->point],
        ];

        // Lặp qua danh sách và gửi yêu cầu HTTP cho từng cặp
        foreach ($dataList as $data) {
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl('http://localhost/backend/web/user-profile/add-point-to-staff')
                ->setData([
                    'id' => $data['id'],
                    'plus' => $data['point'],
                ])
                ->send();

            if ($response->isOk) {
                $responseData = $response->data;
                if (isset($responseData['success']) && $responseData['success']) {
                    Yii::$app->session->setFlash('success', 'Điểm đã được tặng thành công cho ID: ' . $dataList[0]['id']);
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to award points for ID: ' . $data['id'] . '. ' . ($responseData['message'] ?? ''));
                }
            } else {
                Yii::$app->session->setFlash('error', 'Failed to connect to the external API for ID: ' . $data['id']);
            }
        }

        return $this->redirect(['index']); // Chuyển hướng đến trang phù hợp
    } else {
        Yii::$app->session->setFlash('error', 'Invalid data.');
    }
    return $this->redirect(['index']); // Chuyển hướng đến trang phù hợp
}

}
