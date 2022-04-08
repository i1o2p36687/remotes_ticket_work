import request from '@/utils/request'

export function login(account, password){
    const data = {account: account, password: password}
    return request({
        url: '/api/login',
        method: 'post',
        data
    })
}