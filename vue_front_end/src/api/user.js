import request from '@/utils/request'

export function login(account, password){
    const data = {account: account, password: password}
    return request({
        url: '/api/login',
        method: 'post',
        data
    })
}

export function refreshToken(refresh_token){
    const data = {refresh_token: refresh_token}
    return request({
        url: '/api/refreshToken',
        method: 'post',
        data
    })
}

export function getUserInfo(){
    return request({
        url: '/api/user_info',
        method: 'get'
    })
}

//使用者列表
export function fetchList(query){
    return request({
        url: '/api/get_user_list',
        method: 'get',
        params: query
    })
}

//角色列表
export function fetchRoleList(){
    return request({
        url: '/api/get_role_list',
        method: 'get'
    })
}

//新增會員
export function createUser(data){
    return request({
        url: '/api/create_user',
        method: 'post',
        data
    })
}

//修改會員
export function updateUser(data){
    return request({
        url: '/api/update_user',
        method: 'put',
        data
    })
}