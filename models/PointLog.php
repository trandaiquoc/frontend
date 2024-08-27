<?php

namespace app\models;

use Yii;
use yii\base\Model;

class PointLog extends \yii\db\ActiveRecord
{
    public $id;
    public $senderid;
    public $sendername;
    public $receiverid;
    public $receivername;
    public $pointsadded;
    public $createdat;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['senderid', 'sendername', 'receiverid', 'receivername', 'pointsadded'], 'required'],
            [['senderid', 'receiverid', 'pointsadded'], 'integer'],
            [['createdat'], 'safe'],
            [['sendername', 'receivername'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'senderid' => 'Senderid',
            'sendername' => 'Sendername',
            'receiverid' => 'Receiverid',
            'receivername' => 'Receivername',
            'pointsadded' => 'Pointsadded',
            'createdat' => 'Createdat',
        ];
    }
}
