<?php
namespace app\controllers;

use app\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{

    /**
     *
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'logout',
                    'login'
                ], // Thêm 'login' vào danh sách các hành động cần kiểm soát
                'rules' => [
                    [
                        'actions' => [
                            'logout'
                        ],
                        'allow' => true,
                        // Cho phép truy cập vào hành động logout nếu có thông tin người dùng và token trong session
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->session->has('user') && Yii::$app->session->has('token');
                        }
                    ],
                    [
                        'actions' => [
                            'login'
                        ],
                        'allow' => false,
                        // Chặn truy cập vào hành động login nếu đã có thông tin người dùng và token trong session
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->session->has('user') && Yii::$app->session->has('token');
                        }
                    ],
                    [
                        'actions' => [
                            'login'
                        ],
                        'allow' => true,
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => [
                        'post'
                    ]
                ]
            ]
        ];
    }

    /**
     *
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (! Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            $responseData = $this->getLoginDataFromApi($model->username, $model->password);

            if ($responseData['success']) {
                // Lưu thông tin người dùng vào session
                Yii::$app->session->set('user', $responseData['data']);
                $user = Yii::$app->session->get('user');
                $user['id'] = 1;
                Yii::$app->session->set('user', $user);
                Yii::$app->session->set('token', $responseData['data']['token']);
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', $responseData['message']);
            }
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model
        ]);
    }

    private function getLoginDataFromApi($username, $password)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('http://localhost/backend/web/user-profile/login')
            ->setData([
            'username' => $username,
            'password' => $password
        ])
            ->send();

        if ($response->isOk) {
            return $response->data;
        }

        return [
            'success' => false,
            'message' => 'Failed to connect to the server.'
        ];
    }

    /**
     * Logout action.
     *
     * @return Response|string
     */
    public function actionLogout()
    {
        // Lấy tên người dùng từ session
        $username = Yii::$app->session->get('user')['username'];

        // Gọi API để đăng xuất
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('http://localhost/backend/web/user-profile/logout')
            ->setData([
            'username' => $username
        ])
            ->send();

        // Xử lý phản hồi từ API
        if ($response->isOk && $response->data['success']) {
            // Xóa thông tin người dùng và token khỏi session
            Yii::$app->session->remove('user');
            Yii::$app->session->remove('token');
        } else {
            // Đặt thông báo lỗi nếu có vấn đề xảy ra
            Yii::$app->session->setFlash('error', 'Logout failed. Please try again.');
        }

        // Chuyển hướng về trang chủ
        return $this->goHome();
    }

}
