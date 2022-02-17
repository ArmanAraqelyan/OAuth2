import axios from 'axios';

export const API = 'http://localhost:8080';
export const CLIENT_ID = '1';

export const HTTP = function () {
  let headers = {};

  let token = JSON.parse(localStorage.getItem('token'));

  switch(token.token_type) {
    case 'Bearer':
      headers.Authorization = `Bearer ${token.access_token}`;
  }

  return axios.create(
    {
      baseURL: API,
      headers: headers,
    }
  )
}

export const AUTH_DATA = {
    client_secret: '1234',
    grant_type: 'authorization_code',
    client_id: CLIENT_ID,
    redirect_uri: 'http://localhost:9090/oauth',
    code: ''
}

export const SELF_QUERY = '?client_id=1&scope=1&response_type=code&redirect_uri=http://localhost:9090/oauth';