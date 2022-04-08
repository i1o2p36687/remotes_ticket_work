
export function getToken() {
  // return Cookies.get(TokenKey)
  return localStorage.getItem('access_token')
}

export function getRefreshToken() {
  return localStorage.getItem('refresh_token')
}

export function setToken(token) {
  return localStorage.setItem('access_token', token)
}

export function setRefreshToken(refresh_token) {
  return localStorage.setItem('refresh_token', refresh_token)
}

export function removeToken() {
  return localStorage.removeItem('access_token')
}

export function removeRefreshToken() {
  return localStorage.removeItem('refresh_token')
}
