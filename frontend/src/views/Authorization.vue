<template>
  <div class="autorization">
    <div class="form" :class="form">
      <div class="form-toggle" @click="hide" :class="toggle" ></div>
        <div class="form-panel one">
          <div class="form-header">
            <h1>Account Login</h1>
          </div>
          <div class="form-content">
              <p v-if="errors.length">
                <ul>
                  <li class="erorrs"  v-for="error in errors" :key="error">{{ error }}</li>
                </ul>
              </p>
              <div class="form-group">
                <label for="username_login">Username</label>
                <input id="username_login" type="text" name="username_login"  v-model.trim="username_login" required="required"/>
              </div>
              <div class="form-group">
                <label for="password_login">Password</label>
                <input id="password_login" type="password" name="password_login" v-model.trim="password_login" required="required"/>
              </div>
              <div class="form-group">
                <button @click="login">Log In</button>
              </div>
          </div>
        </div>
      <div class="form-panel two" @click="show" :class="two">
        <div class="form-header">
          <h1>Register Account</h1>
        </div>
        <div class="form-content">
            <p v-if="reg_errors.length">
                <ul>
                  <li class="erorrs_reg"  v-for="error_reg in reg_errors" :key="error_reg">{{ error_reg }}</li>
                </ul>
              </p>
            <div class="form-group">
              <label for="username">Username</label>
              <input id="username" type="text" name="username" v-model.trim="username"  required="required"/>
            </div>
             <div class="form-group">
              <label for="email">Email Address</label>
              <input id="email" type="email" name="email" v-model.trim="email"  required="required"/>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input id="password" type="password" name="password" v-model.trim="password"  required="required"/>
            </div>
            <div class="form-group">
              <label for="cpassword">Confirm Password</label>
              <input id="cpassword" type="password" name="cpassword" v-model.trim="cpassword"  required="required"/>
            </div>
            <div class="form-group">
              <button @click="registration">Register</button>
            </div>
        </div>
      </div>
    </div>
    <div :class="{ 'loading_bar' : is_loading}">
        <div class="loader_block">
            <div class="loader"></div>
        </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { API, CLIENT_ID, AUTH_DATA, SELF_QUERY, HTTP } from "../http-common";

export default {
  el: ".form",
  data() {
    return {
      errors: [],
      postBody: ["test"],
      reg_errors: [],
      username_login: null,
      password: null,
      cpassword: null,
      email: null,
      username: null,
      password_login: null,
      toggle: "toggle",
      two: "",
      one: "",
      form: "",
      data_login: [this.username_login, this.password_login],
      data_registration: [],
      is_loading: true,
    };
  },

  mounted() {
    let token = localStorage.getItem('token');

    if (token) {
        HTTP().get('user')
        .then(response => {
            if ('name' in response.data && 'email' in response.data) {
                window.location.href = '/profile';
            } else {
                this.is_loading = false;
            }
        });
    } else {
        this.is_loading = false;
    }
  },
  methods: {
    hide() {
      this.toggle = "";
      this.one = "";
      this.two = "";
      this.form = "";
      this.errors = [];
      this.reg_errors = [];
    },
    show() {
      this.toggle = "visible";
      this.one = "hidden";
      this.two = "active";
      this.form = "form_higth";
    },
    validEmail: function (email_login) {
      var re =
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email_login);
    },
    async registration() {
      this.is_loading = true;
      this.reg_errors = [];
      let flag = true;
      if (this.password !== this.cpassword) {
        flag = false;
        this.reg_errors.push("Укажите корректный пароль");
      }
      if (!this.email) {
        flag = false;
        this.reg_errors.push("Требуется указать Email.");
      } else if (!this.validEmail(this.email)) {
        flag = false;
        this.reg_errors.push("Укажите корректный адрес электронной почты.");
      }
      if (!this.password) {
        flag = false;
        this.reg_errors.push("Требуется указать пароль.");
      }
      if (!this.cpassword) {
        flag = false;
        this.reg_errors.push("Требуется указать повторный пароль.");
      }
      if (flag === false) {
        this.is_loading = false;
        return this.reg_errors;
      }
      let reg_data = {
        name: this.username,
        email: this.email,
        password: this.password,
      };
      try {
        const token = Buffer.from(
          `${this.username}:${this.password}`,
          "utf8"
        ).toString("base64");
        const query = window.location.search
          ? window.location.search
          : SELF_QUERY;
        const client = new URLSearchParams(query).get("client_id");
        
        await
            axios.post(`${API}/registration`, reg_data).then((response) => {
                axios
                .get(`${API}/auth/signup` + query, {
                    headers: {
                        Authorization: `Basic ${token}`,
                    },
                })
                .then((response) => {
                    if (client === CLIENT_ID) {
                        let code = new URL(
                            response.request["responseURL"]
                        ).searchParams.get("code");
                        AUTH_DATA.code = code;
                        axios.post(`${API}/oauth/auth`, AUTH_DATA).then((token) => {
                            if (token.data.access_token) {
                                localStorage.setItem("token", JSON.stringify(token.data));
                                window.location.href = "/profile";
                            }
                        });
                    }
                });
        });
      } catch (e) {
        this.is_loading = false;
        this.reg_errors.push(e);
      }
      this.is_loading = false;
    },
    async login() {
      this.is_loading = true;
      this.errors = [];
      let flag = true;
      if (!this.username_login) {
        this.errors.push("Требуется указать Username.");
        flag = false;
      }
      if (!this.password_login) {
        this.errors.push("Требуется указать Password.");
        flag = false;
      }
      if (flag === false) {
        this.is_loading = false;
        return this.errors;
      }
      try {
        const token = Buffer.from(
          `${this.username_login}:${this.password_login}`,
          "utf8"
        ).toString("base64");
        const query = window.location.search
          ? window.location.search
          : SELF_QUERY;
        const client = new URLSearchParams(query).get("client_id");

        await axios
                .get(`${API}/auth/signup` + query, {
                    headers: {
                        Authorization: `Basic ${token}`,
                    },
                })
                .then((response) => {
                    if (client === CLIENT_ID) {
                        let code = new URL(
                            response.request["responseURL"]
                        ).searchParams.get("code");
                        AUTH_DATA.code = code;
                        axios.post(`${API}/oauth/auth`, AUTH_DATA).then((token) => {
                            localStorage.setItem("token", JSON.stringify(token.data));
                            window.location.href = "/profile";
                        });
                    }
                });
      } catch (e) {
        this.is_loading = false;
        this.errors.push(e);
      }
      this.is_loading = false;
    }
  }
}
</script>

<style scoped>
.loading_bar{
    position: absolute;
    z-index: 10000;
    background: #80808061;
    top: 0;
    right: 0;
    left: 0;
    bottom: 0;
}
 .loading_bar > .loader_block{
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
}
.loading_bar .loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.form_higth::-webkit-scrollbar {
  width: 10px;
}
.form_higth::-webkit-scrollbar-track {
  background: #f1f1f1;
}
.form_higth::-webkit-scrollbar-thumb {
  background: red;
}
.form_higth::-webkit-scrollbar-thumb:hover {
  background: #555;
}
.erorrs {
  list-style: none;
  color: red;
  background-color: #ff000030;
  padding: 6px;
  margin: 13px 0px;
  border-radius: 4px;
}
.erorrs_reg {
  list-style: none;
  color: red;
  background-color: white;
  padding: 6px;
  margin: 13px 0px;
  border-radius: 4px;
}
.form_higth {
  height: 100%;
  min-height: 580px;
  display: block;
  overflow-y: scroll !important;
}
body {
  background: linear-gradient(
    45deg,
    rgba(66, 183, 245, 0.8) 0%,
    rgba(66, 245, 189, 0.4) 100%
  );
  color: rgba(0, 0, 0, 0.6);
  font-family: "Roboto", sans-serif;
  font-size: 14px;
  line-height: 1.6em;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
.overlay,
.form-panel.one:before {
  position: absolute;
  top: 0;
  left: 0;
  display: none;
  background: rgba(0, 0, 0, 0.8);
  width: 100%;
  height: 100%;
}
.form {
  z-index: 15;
  position: relative;
  background: #ffffff;
  width: 600px;
  border-radius: 4px;
  box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
  box-sizing: border-box;
  margin: 100px auto 10px;
  overflow: hidden;
}
.form-toggle {
  z-index: 10;
  position: absolute;
  top: 60px;
  right: 60px;
  background: #ffffff;
  width: 60px;
  height: 60px;
  border-radius: 100%;
  transform-origin: center;
  transform: translate(0, -25%) scale(0);
  opacity: 0;
  cursor: pointer;
  transition: all 0.3s ease;
}
.form-toggle:before,
.form-toggle:after {
  content: "";
  display: block;
  position: absolute;
  top: 50%;
  left: 50%;
  width: 30px;
  height: 4px;
  background: #4285f4;
  transform: translate(-50%, -50%);
}
.form-toggle:before {
  transform: translate(-50%, -50%) rotate(45deg);
}
.form-toggle:after {
  transform: translate(-50%, -50%) rotate(-45deg);
}
.form-toggle.visible {
  transform: translate(0, -25%) scale(1);
  opacity: 1;
}
.form-group {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  margin: 0 0 20px;
}
.form-group:last-child {
  margin: 0;
}
.form-group label {
  display: block;
  margin: 0 0 10px;
  color: rgba(0, 0, 0, 0.6);
  font-size: 12px;
  font-weight: 500;
  line-height: 1;
  text-transform: uppercase;
  letter-spacing: 0.2em;
}
.two .form-group label {
  color: #ffffff;
}
.form-group input {
  outline: none;
  display: block;
  background: rgba(0, 0, 0, 0.1);
  width: 100%;
  border: 0;
  border-radius: 4px;
  box-sizing: border-box;
  padding: 12px 20px;
  color: rgba(0, 0, 0, 0.6);
  font-family: inherit;
  font-size: inherit;
  font-weight: 500;
  line-height: inherit;
  transition: 0.3s ease;
}
.form-group input:focus {
  color: rgba(0, 0, 0, 0.8);
}
.two .form-group input {
  color: #ffffff;
}
.two .form-group input:focus {
  color: #ffffff;
}
.form-group button {
  outline: none;
  background: #4285f4;
  width: 100%;
  border: 0;
  border-radius: 4px;
  padding: 12px 20px;
  color: #ffffff;
  font-family: inherit;
  font-size: inherit;
  font-weight: 500;
  line-height: inherit;
  text-transform: uppercase;
  cursor: pointer;
}
.two .form-group button {
  background: #ffffff;
  color: #4285f4;
}
.form-group .form-remember {
  font-size: 12px;
  font-weight: 400;
  letter-spacing: 0;
  text-transform: none;
}
.form-group .form-remember input[type="checkbox"] {
  display: inline-block;
  width: auto;
  margin: 0 10px 0 0;
}
.form-group .form-recovery {
  color: #4285f4;
  font-size: 12px;
  text-decoration: none;
}
.form-panel {
  padding: 60px calc(5% + 60px) 60px 60px;
  box-sizing: border-box;
}
.form-panel.one:before {
  content: "";
  display: block;
  opacity: 0;
  visibility: hidden;
  transition: 0.3s ease;
}
.form-panel.one.hidden:before {
  display: block;
  opacity: 1;
  visibility: visible;
}
.form-panel.two {
  z-index: 5;
  position: absolute;
  top: 0;
  left: 95%;
  background: #4285f4;
  width: 100%;
  min-height: 100%;
  padding: 60px calc(10% + 60px) 60px 60px;
  transition: 0.3s ease;
  cursor: pointer;
}
.form-panel.two:before,
.form-panel.two:after {
  content: "";
  display: block;
  position: absolute;
  top: 60px;
  left: 1.5%;
  background: rgba(255, 255, 255, 0.2);
  height: 30px;
  width: 2px;
  transition: 0.3s ease;
}
.form-panel.two:after {
  left: 3%;
}
.form-panel.two:hover {
  left: 93%;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}
.form-panel.two:hover:before,
.form-panel.two:hover:after {
  opacity: 0;
}
.form-panel.two.active {
  left: 10%;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  cursor: default;
}
.form-panel.two.active:before,
.form-panel.two.active:after {
  opacity: 0;
}
.form-header {
  margin: 0 0 40px;
}
.form-header h1 {
  padding: 4px 0;
  color: #4285f4;
  font-size: 24px;
  font-weight: 700;
  text-transform: uppercase;
}
.two .form-header h1 {
  position: relative;
  z-index: 40;
  color: #ffffff;
}
.pen-footer {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  width: 600px;
  margin: 20px auto 100px;
}
.pen-footer a {
  color: #ffffff;
  font-size: 12px;
  text-decoration: none;
  text-shadow: 1px 2px 0 rgba(0, 0, 0, 0.1);
}
.pen-footer a .material-icons {
  width: 12px;
  margin: 0 5px;
  vertical-align: middle;
  font-size: 12px;
}

.cp-fab {
  background: #ffffff !important;
  color: #4285f4 !important;
}
</style>

