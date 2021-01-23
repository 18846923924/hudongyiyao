<?php
/**
 * Description:
 * Created by PhpStorm.
 *
 *
 * Date: 2019/11/6 0006
 * Time: 17:44
 */

namespace app\model;


use think\Model;

class User extends Model
{
    protected $pk = 'user_id';

    public function parent()
    {
        return $this->hasOne('User','parent_id');
    }

    public function account()
    {
        return $this->hasOne('UserAccount','user_id');
    }
}