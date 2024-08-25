<?php
namespace app\models;

use yii\base\Model;
use yii\data\ArrayDataProvider;

/**
 * UserProfileSearch represents the model behind the search form of `app\models\UserProfile`.
 */
class UserProfileSearch extends UserProfile
{
    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates a data provider instance with search query applied
     *
     * @param array $params
     * @param array $data
     * @return ArrayDataProvider
     */
    public function search($params, $data)
    {
        // Chuyển mảng dữ liệu thành đối tượng UserProfile
        $models = array_map(function ($item) {
            return new UserProfile($item);
        }, $data);

        // Tạo DataProvider và áp dụng các điều kiện tìm kiếm
        $dataProvider = new ArrayDataProvider([
            'allModels' => $models,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        // Nạp dữ liệu từ params vào mô hình tìm kiếm
        $this->load($params);

        // Lọc dữ liệu
        $dataProvider->allModels = array_filter($models, function (UserProfile $model) {
            return ($this->id === null || $model->id == $this->id) &&
                   ($this->employee_id === null || strpos($model->employee_id, $this->employee_id) !== false) &&
                   ($this->fullname === null || strpos($model->fullname, $this->fullname) !== false) &&
                   ($this->username === null || strpos($model->username, $this->username) !== false) &&
                   ($this->idcard === null || strpos($model->idcard, $this->idcard) !== false) &&
                   ($this->taxcode === null || strpos($model->taxcode, $this->taxcode) !== false) &&
                   ($this->address === null || strpos($model->address, $this->address) !== false) &&
                   ($this->phonenumber === null || strpos($model->phonenumber, $this->phonenumber) !== false) &&
                   ($this->bankaccountnumber === null || strpos($model->bankaccountnumber, $this->bankaccountnumber) !== false) &&
                   ($this->password === null || strpos($model->password, $this->password) !== false) &&
                   ($this->token === null || strpos($model->token, $this->token) !== false) &&
                   ($this->staffids === null || strpos($model->staffids, $this->staffids) !== false) &&
                   ($this->point === null || $model->point == $this->point) &&
                   ($this->locked === null || $model->locked == $this->locked);
        });

        return $dataProvider;
    }
}
