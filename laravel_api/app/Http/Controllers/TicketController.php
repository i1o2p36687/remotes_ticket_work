<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CommonClass;
use App\Services\TicketClass;
use App\Models\Ticket;
use DB;

class TicketController extends Controller
{   
    /**
     * 取得Ticket列表
     * 
     * @param string request->page  頁數
     * @param string request->limit 一頁幾筆
     * 
     * @return array {result: 結果為 success or fail ,count: 總數, list: 列表}
     */
    public function getTicketList(Request $request){
        try {
            $data = $request->all();
            $page = isset($data['page']) ? (int)$data['page'] : 1;
            $limit = isset($data['limit']) ? (int)$data['limit'] : 20;

            $users = TicketClass::get_ticket_list($data);
            $result = CommonClass::getPageData($users, $page, $limit);

            $response = ['result'=> 'success'];
            $response = array_merge($response, $result);
        } catch (\Exception $e) {
            $response = ['result'=> 'fail', 'msg'=> $e->getMessage()];
        }

        return $response;
    }

    /**
     * 新增Ticket
     * 
     * @param string request->type 
     * @param string request->title
     * @param string request->description
     * @param boolean request->priority
     * 
     * @return array {result: 結果為 success or fail}
     */
    public function createTicket(Request $request){
        try {
            $data = $request->all();

            DB::beginTransaction();

            $data['id'] = '';
            $result = TicketClass::edit_ticket($data);

            DB::commit();

            $response = ['result'=> 'success', 'msg'=> '新增成功'];
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['result'=> 'fail', 'msg'=> $e->getMessage()];
        }

        return $response;
    }

    /**
     * 修改Ticket
     * 
     * @param int request->id
     * @param string request->type 
     * @param string request->title
     * @param string request->description
     * @param boolean request->priority
     * 
     * @return array {result: 結果為 success or fail}
     */
    public function updateTicket(Request $request){
        try {
            $data = $request->all();

            DB::beginTransaction();

            $result = TicketClass::edit_ticket($data);

            DB::commit();

            $response = ['result'=> 'success', 'msg'=> '修改成功'];
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['result'=> 'fail', 'msg'=> $e->getMessage()];
        }

        return $response;
    }

    /**
     * 修改Ticket
     * 
     * @param int request->id
     * @param string request->type 
     * @param int request->status
     * 
     * @return array {result: 結果為 success or fail}
     */
    public function resolveTicket(Request $request){
        try {
            $data = $request->all();
            
            $result = TicketClass::update_status($data['id'], 2);
            
            $response = ['result'=> 'success', 'msg'=> '成功更變為已解決'];
        } catch (\Exception $e) {
            $response = ['result'=> 'fail', 'msg'=> $e->getMessage()];
        }

        return $response;
    }

    /**
     * 修改Ticket
     * 
     * @param int request->id
     * @param string request->type 
     * @param int request->status
     * 
     * @return array {result: 結果為 success or fail}
     */
    public function deleteTicket(Request $request){
        try {
            $data = $request->all();
            
            $result = TicketClass::update_status($data['id'], 0);
            
            $response = ['result'=> 'success', 'msg'=> '已刪除'];
        } catch (\Exception $e) {
            $response = ['result'=> 'fail', 'msg'=> $e->getMessage()];
        }

        return $response;
    }
}