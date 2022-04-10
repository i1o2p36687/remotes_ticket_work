import request from '@/utils/request'

//列表
export function fetchList(query){
    return request({
        url: '/api/get_ticket_list',
        method: 'get',
        params: query
    })
}

//新增會員
export function createTicket(data){
    return request({
        url: '/api/create_ticket',
        method: 'post',
        data
    })
}

//修改會員
export function updateTicket(data){
    return request({
        url: '/api/update_ticket',
        method: 'put',
        data
    })
}

//解決功能
export function resolveTicket(data){
    return request({
        url: '/api/resolve_ticket',
        method: 'put',
        data
    })
}

//刪除
export function deleteTicket(data){
    return request({
        url: '/api/delete_ticket',
        method: 'put',
        data
    })
}