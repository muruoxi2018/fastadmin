<?php

namespace app\common\model;

use think\Model;
/**
 * 文章模型
 */
class Article extends Model
{
    // 表名
    protected $name = 'article';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    // /**
    //  * 获取文章列表
    //  * @param array $where   查询条件
    //  * @param string $order  排序
    //  * @param string $field  字段
    //  * @param int $limit    页数
    //  * @return array
    //  */
    // public function getList($where = array(), $order = '', $field = '*', $limit = 10)
    // {
    //     $map = array();
    //     if (isset($where['id']) && $where['id'] != '') {
    //         $map['id'] = $where['id'];
    //     }
    //     if (isset($where['title']) && $where['title'] != '') {
    //         $map['title'] = ['like', '%' . $where['title'] . '%'];
    //     }
    //     if (isset($where['status']) && $where['status'] != '') {
    //         $map['status'] = $where['status'];
    //     }
    //     if (isset($where['content']) && $where['content'] != '') {
    //         $map['content'] = $where['content'];
    //     }
    //     $arr = Db::name('article')->where($map)->order($order)->field($field)->limit($limit)->select();
    //     return $arr;
    // }

    // /**
    //  * 获取文章详情
    //  * @param int $id   文章id
    //  * @param string $field  字段
    //  * @return model
    //  */
    // public function getOne($id, $field = '*')
    // {
    //     $map = array();
    //     $map['id'] = $id;
    //     $arr = Db::name('article')
    //         ->where($map)
    //         ->field($field)
    //         ->find();
    //     // if ($arr['author'] == '') {
    //     //     $arr['author'] = $arr->admin->nickname;
    //     // }
    //     // $arr['category']=$arr->category->name;
    //     return $arr;
    // }

    // /**
    //  * 添加文章
    //  * @param array $data   数据
    //  * @return int
    //  */

    // public function add($data)
    // {
    //     $arr = Db::name('article')->insert($data);
    //     return $arr;
    // }

    // /**
    //  * 修改文章
    //  * @param array $data   数据
    //  * @return int
    //  */
    // public function edit($data)
    // {
    //     $map = array();
    //     $map['id'] = $data['id'];
    //     $arr = Db::name('article')->where($map)->update($data);
    //     return $arr;
    // }

    // /**
    //  * 删除文章
    //  * @param int $id   文章id
    //  * @return int
    //  */
    // public function del($id)
    // {
    //     $map = array();
    //     $map['id'] = $id;
    //     $arr = Db::name('article')->where($map)->delete();
    //     return $arr;
    // }

    
}
