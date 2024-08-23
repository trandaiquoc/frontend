<?php

namespace app\models;

use yii\base\Model;
use yii\data\ArrayDataProvider;

class EmployeeSearch extends Model
{
    public $id;
    public $employee_id;
    public $fullname;
    public $username;
    public $idcard;
    public $address;
    public $phonenumber;
    public $point;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['id', 'employee_id', 'fullname', 'username', 'idcard', 'address', 'phonenumber'], 'safe'],
            [['point'], 'integer'],
        ];
    }

    /**
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     * @return ArrayDataProvider
     */
    public function search($params, $employees)
    {
        $query = $employees;

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        // Apply search filters
        $query = array_filter($query, function ($employee) {
            return (
                (!$this->id || strpos($employee['id'], $this->id) !== false) &&
                (!$this->employee_id || strpos($employee['employee_id'], $this->employee_id) !== false) &&
                (!$this->fullname || strpos($employee['fullname'], $this->fullname) !== false) &&
                (!$this->username || strpos($employee['username'], $this->username) !== false) &&
                (!$this->idcard || strpos($employee['idcard'], $this->idcard) !== false) &&
                (!$this->address || strpos($employee['address'], $this->address) !== false) &&
                (!$this->phonenumber || strpos($employee['phonenumber'], $this->phonenumber) !== false) &&
                (!$this->point || strpos($employee['point'], $this->point) !== false)
            );
        });

        $dataProvider->allModels = $query;

        return $dataProvider;
    }
}
