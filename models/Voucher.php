<?php
namespace app\models;

use yii\base\Model;

class Voucher extends Model
{
    public $id;
    public $name;
    public $code;
    public $valid_to;
    public $image_url;
    public $point;

    public function rules()
    {
        return [
            [['id', 'name', 'code', 'valid_to', 'image_url', 'point'], 'safe'],
        ];
    }
}
