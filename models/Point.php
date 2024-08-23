<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Point".
 *
 * @property int $id
 * @property string $employee_id
 * @property string $fullname
 * @property string $username
 * @property string $idcard
 * @property string|null $taxcode
 * @property string|null $address
 * @property string|null $phonenumber
 * @property string|null $bankaccountnumber
 * @property string $password
 * @property string|null $token
 * @property string|null $created_at
 * @property string|null $staffids
 * @property int $point
 * @property int $locked
 */
class Point extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Point';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_id', 'fullname', 'username', 'idcard', 'password'], 'required'],
            [['address'], 'string'],
            [['created_at'], 'safe'],
            [['point', 'locked'], 'integer'],
            [['employee_id', 'username', 'idcard', 'taxcode', 'bankaccountnumber'], 'string', 'max' => 50],
            [['fullname', 'password', 'token', 'staffids'], 'string', 'max' => 255],
            [['phonenumber'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'Employee ID',
            'fullname' => 'Fullname',
            'username' => 'Username',
            'idcard' => 'Idcard',
            'taxcode' => 'Taxcode',
            'address' => 'Address',
            'phonenumber' => 'Phonenumber',
            'bankaccountnumber' => 'Bankaccountnumber',
            'password' => 'Password',
            'token' => 'Token',
            'created_at' => 'Created At',
            'staffids' => 'Staffids',
            'point' => 'Point',
            'locked' => 'Locked',
        ];
    }
}
