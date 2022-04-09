<?php
namespace App\Services;

class CommonClass {
    /**
     * 取得頁面資訊
     * $data Laravel model 已設定好搜尋條件
     * $page int 要取得頁面
     * $limit int 筆數
     */
    public static function getPageData($data, $page, $limit=15){
        $offset = ($page - 1) * $limit;
        $count = $data->count();

        if($page != 0 && $limit != 0){
            $list = $data->offset($offset)
                        ->limit($limit)
                        ->get();
        }else{
            $list =$data->get();
        }
        
        $list = json_decode(json_encode($list), true);
        return ['list'=> $list, 'count'=> $count];
    }
}