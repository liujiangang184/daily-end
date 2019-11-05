<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Exception;

class Index extends Controller
{

    public function index()
    {
        $data = input('post.');
        $data['ctime'] = date('Y-m-d', time());
        $result = Db::table('daily')->insert($data);
        if ($result) {
            return json([
                "code" => config('code.success'),
                "msg" => '数据插入成功'
            ]);
        } else {
            return json([
                "code" => config('code.fail'),
                "msg" => '数据插入失败'
            ]);
        }
    }

    public function query()
    {
        /*     try{
             }catch (Exception $exception){

             }*/

        $data = $this->request->get();
//        var_dump($data);
//        exit();
        $names = $data['names'];
        $sarr = [
            'names' => $names,
        ];
        if (isset($data['page']) && !empty($data['page'])) {
            $page = $data['page'];

        } else {
            $page = 1;
        }
        if (isset($data['limit']) && !empty($data['limit'])) {
            $limit = $data['limit'];
        } else {
            $limit = 1;
        }

        if (isset($data['order']) && !empty($data['order'])) {
        $order = $data['order'];
    } else {
        $order = 'id';
    }
        if (isset($data['ordertype']) && !empty($data['ordertype'])) {
            $ordertype = $data['ordertype'];
        } else {
            $ordertype = 'desc';
        }


        $daily = Db::table('daily')->where($sarr)
            ->page($page, $limit)->order($order, $ordertype)->select();
        $count = Db::table('daily')->where($sarr)->count();
        if ($count > 0 && count($daily) > 0) {
            return json([
                'code' => config('code.success'),
                'msg' => '数据获取成功',
                'data' => $daily,
                'count' => $count
            ]);
        } else {
            return json([
                'code' => config('code.fail'),
                'msg' => '暂无数据'
            ]);
        }
    }

}
