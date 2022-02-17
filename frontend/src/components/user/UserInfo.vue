<template>
    <div id="user_info">
        <div class="info_content">
            <div class="info_img">
                 <img alt="Vue logo" class="avatar" src="../../assets/account_icon.svg">
            </div>
            <div class="info_texts">
                <p>
                    <label for="name">Name:</label>
                    <span>{{name}}</span>
                </p>
                <p>
                    <label for="login">Login:</label>
                    <span>{{ email }}</span>
                </p>
                <p>
                    <label for="date">Date:</label>
                    <input type="date" v-model="reg_date">
                    
                </p>
            </div>
        </div>
    </div>
</template>
<script>
import { HTTP } from '../../http-common';
    export default {
        data(){
            return{
                errors: [],
                name: "",
                email : "",
                reg_date : "",
            }
        }, 
        name: 'UserInfo',
        methods: {
            created() {
                HTTP().get('user')
                .then(response => {
                    this.name = response.data.name
                    this.email = response.data.email
                    this.reg_date = response.data.reg_date.date.match(/\d{4}-\d{2}-\d{2}/)
                })
                .catch(e => {
                    this.errors.push(e)
                })  
            }
        },
        mounted() {
            this.created();
        }
    }
</script>
<style >
.info_content{
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-pack: distribute;
        justify-content: space-around;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center;
    -ms-flex-wrap: wrap;
        flex-wrap: wrap;
    border-radius: unset;
    padding: 24px 16px;
    -webkit-box-shadow: 0 0 30px rgb(24 106 196 / 44%);
            box-shadow: 0 0 30px rgb(24 106 196 / 44%);
}
.info_texts{
    margin-left: 20px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
        flex-wrap: wrap;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
        -ms-flex-direction: column;
            flex-direction: column;
    -webkit-box-pack: justify;
        -ms-flex-pack: justify;
            justify-content: space-between;
    height: 88px;
}
.info_texts p span,input {
    margin-left: 10px;
}
.info_texts p span {
    margin-left: 5px;
    color: rgba(0, 0, 0);
    font-size: 12px;
    font-weight: 600;
    line-height: 1;
    text-transform: uppercase;
    letter-spacing: 0.2em;
}
.info_texts p label {
    color: rgba(0, 0, 0, 0.6);
    font-size: 12px;
    font-weight: 500;
    line-height: 1;
    text-transform: uppercase;
    letter-spacing: 0.2em;
}
.info_img img {
    width: 150px;
}
</style>