<?php

namespace app\admin\model\league;


class AuthAdmin extends \app\admin\model\User
{
    // 表名
    protected $name = 'user';
    /**
     * 关联部门中间表
     * @return \think\model\relation\HasMany
     */
    public function dadmin()
    {
        return $this->hasMany('\app\admin\model\league\Admin', 'user_id', 'id');
    }

    /**
     * 关联部门表
     * @return \think\model\relation\BelongsToMany
     */
    public function leagues()
    {
        return $this->belongsToMany('\app\admin\model\league\League','LeagueAdmin','league_id','user_id');
    }

    /**
     * 关联角色组
     * @return \think\model\relation\HasMany
     */
    public function groups()
    {
        return $this->hasMany('\app\admin\model\league\AuthGroupAccess', 'uid', 'id');
    }

}