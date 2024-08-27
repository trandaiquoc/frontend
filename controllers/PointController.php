<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\httpclient\Client;
use yii\web\Response;
use app\models\AwardPointsForm;
use app\models\UserProfile;
use app\models\UserProfileSearch;

/**
 * PointController implements the CRUD actions for Point model.
 */
class PointController extends Controller
{

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
    
                // Gọi API để lấy log điểm thưởng
                $logResponse = $client->get('http://localhost/backend/web/point-log/get-point-log', ['userId' => $id])->send();
    
                $logData = [];
                if ($logResponse->isOk && $logResponse->data['success']) {
                    $logData = $logResponse->data['data']; // Giả sử API trả về dữ liệu dạng mảng trong `data`
                }
    
                return $this->render('index', [
                    'point' => $point,
                    'logData' => $logData,
                    'userId' => $id,
                ]);
            } else {
                Yii::$app->session->setFlash('error', 'Điểm không tìm thấy cho ID người dùng.');
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
        
        // Chuyển đổi dữ liệu thành các đối tượng UserProfile
        $userProfiles = [];
        foreach ($employees as $employeeData) {
            $userProfile = new UserProfile();
            $userProfile->attributes = $employeeData; // Gán dữ liệu cho model
            $userProfiles[] = $userProfile->attributes;
        }
        
        // Tạo UserProfileSearch model và truyền dữ liệu vào đó
        $searchModel = new UserProfileSearch();
        $dataProvider = $searchModel->search([], $userProfiles);
        
        return $this->render('staff-form', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    public function actionAwardPoints()
    {
        // Khởi tạo mô hình
        $model = new AwardPointsForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $client = new Client();
            $user = Yii::$app->session->get('user');
            // Danh sách các cặp id và điểm (thay thế bằng dữ liệu thực tế)
            $dataList = [
                ['senderId' => $user['id'],'receiverId' => $model->id, 'point' => $model->point],
                ['senderId' => "System", 'receiverId' => $user['id'],  'point' => -$model->point],
            ];

            // Lặp qua danh sách và gửi yêu cầu HTTP cho từng cặp
            foreach ($dataList as $data) {
                $response = $client->createRequest()
                    ->setMethod('GET')
                    ->setUrl('http://localhost/backend/web/user-profile/add-point-to-staff')
                    ->setData([
                        'senderId' => $data['senderId'],
                        'receiverId' => $data['receiverId'],
                        'plus' => $data['point'],
                    ])
                    ->send();

                if ($response->isOk) {
                    $responseData = $response->data;
                    if (isset($responseData['success']) && $responseData['success']) {
                        Yii::$app->session->setFlash('success', 'Điểm đã được tặng thành công cho ID: ' . $dataList[0]['receiverId']);
                    } else {
                        Yii::$app->session->setFlash('error', 'Failed to award points for ID: ' . $data['id'] . '. ' . ($responseData['message'] ?? ''));
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to connect to the external API for ID: ' . $data['id']);
                }
            }

            // Kiểm tra nếu thông tin người dùng đã tồn tại và có điểm
            if (isset($user) && isset($user['point'])) {
                // Giảm điểm
                $user['point'] -= $model->point;
                
                // Cập nhật lại giá trị điểm trong session
                Yii::$app->session->set('user', $user);
            }
            return $this->redirect(['index']); // Chuyển hướng đến trang phù hợp
        } else {
            Yii::$app->session->setFlash('error', 'Invalid data.');
        }
        return $this->redirect(['index']); // Chuyển hướng đến trang phù hợp
    }
     public function actionDistributeMonthlyPoints()
    {
        $plus = 1000; //Điểm cần cộng
        // Tạo HTTP client và gửi yêu cầu đến API
        $client = new Client();
        $response = $client->get('http://localhost/backend/web/user-profile/add-point-to-all', [
            'plus' => $plus
        ])->send();

        // Kiểm tra phản hồi từ API
        if ($response->isOk) {
            Yii::$app->session->setFlash('success', 'Điểm đã được cộng cho tất cả nhân viên.');
        } else {
            Yii::$app->session->setFlash('error', 'Lỗi khi cộng điểm cho nhân viên.');
        }
    }
}
