
export function getToken() {
  // return Cookies.get(TokenKey)
  return localStorage.getItem('access_token')
}

export function getTokenExpire() {
  // return Cookies.get(TokenKey)
  return localStorage.getItem('token_expire')
}

export function getRefreshToken() {
  return localStorage.getItem('refresh_token')
}

export function setToken(token) {
  return localStorage.setItem('access_token', token)
}

export function setTokenExpire(token_expire) {
  return localStorage.setItem('token_expire', token_expire)
}

export function setRefreshToken(refresh_token) {
  return localStorage.setItem('refresh_token', refresh_token)
}

export function removeToken() {
  return localStorage.removeItem('access_token')
}

export function removeTokenExpire() {
  return localStorage.removeItem('token_expire')
}

export function removeRefreshToken() {
  return localStorage.removeItem('refresh_token')
}
