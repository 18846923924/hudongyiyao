<?php


namespace app\model;


use think\Model;

class AdminUser extends Model
{
    protected $table = 'admin_user';

    protected $pk = 'au_id';
}