<?php
namespace App\Services;

use App\Models\RoleMap;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Services\UserClass;
use Auth;
use DB;

class TicketClass {
    //使用者可以查看的type
    private static function get_user_view_type(){
        $user_permission = UserClass::get_user_permission();
        $type = [];
        foreach($user_permission as $permission){
            if(strpos($permission, 'view') !== false){
                $tmp = explode('_', $permission);
                $type[] = $tmp[1];
            }
        }

        return $type;
    }

    //取得 Ticket 列表
    public static function get_ticket_list($data){
        $user_type = self::get_user_view_type();
        $ticket = Ticket::select('id', 'type', 'title', 'description', 'priority', 'status', 'created_at')
                        ->whereIn('type', $user_type)
                        ->where('status', '<>', 0);
        $ticket = $ticket->orderBy('status', 'asc')->orderBy('priority', 'desc')->orderBy('id', 'desc');

        return $ticket;
    }

    //新增或修改 Ticket
    public static function edit_ticket($data){
        //更動欄位
        $ticket_data = [
            'title'=> $data['title'],
            'description'=> $data['description'],
            'priority'=> empty($data['priority']) ? 0 : 1
        ];

        //新增或修改
        if($data['id'] == ''){
            $ticket_data['type'] = $data['type'];
            $ticket_data['created_at'] = date('Y-m-d H:i:s');
            $ticket_id = Ticket::insertGetId($ticket_data);

            if(!$ticket_id){
                throw new \Exception('新增失敗');
            }
            $message = '創建 Ticket';
        }else{
            $ticket_id = $data['id'];
            $update = Ticket::where('id', $ticket_id)->update($ticket_data);
            $message = '修改了內容';
        }

        //新增訊息
        self::add_message($ticket_id, $message, 1);

        return true;
    }

    //更改狀態
    public static function update_status($ticket_id, $status){
        $update = Ticket::where('id', $ticket_id)->update(['status'=> $status]);
        //紀錄
        $message = $status == 2 ? '更改為已解決' : '更改為已刪除';
        self::add_message($ticket_id, $message, 1);

        return true;
    }

    //紀錄 TicketMessage
    public static function add_message($ticket_id, $message, $is_system=0){
        $user_id = Auth::user()->id;
        $message_data = [
            'ticket_id'=> $ticket_id,
            'user_id'=> $user_id,
            'is_system'=> $is_system,
            'message'=> $message
        ];

        if(!$message = TicketMessage::insert($message_data)){
            throw new \Exception('訊息新增失敗');
        }

        return true;
    }
}