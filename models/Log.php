<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property string $logdate
 * @property string $logtime
 * @property string|null $page
 * @property string|null $actionname
 * @property int|null $userid
 * @property string|null $username
 * @property string|null $description
 * @property string|null $createdat
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['logdate', 'logtime'], 'required'],
            [['logdate', 'logtime', 'createdat'], 'safe'],
            [['userid'], 'integer'],
            [['description'], 'string'],
            [['page'], 'string', 'max' => 100],
            [['actionname', 'username'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'logdate' => 'Logdate',
            'logtime' => 'Logtime',
            'page' => 'Page',
            'actionname' => 'Actionname',
            'userid' => 'Userid',
            'username' => 'Username',
            'description' => 'Description',
            'createdat' => 'Createdat',
        ];
    }
}
