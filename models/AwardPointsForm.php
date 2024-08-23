<?php

namespace app\models;

use Yii;
use yii\base\Model;

class AwardPointsForm extends Model
{
    public $id;
    public $point;

    public function rules()
    {
        return [
            [['id', 'point'], 'required'],
            [['id'], 'integer'],
            [['point'], 'number'],
        ];
    }
}