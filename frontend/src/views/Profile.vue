<template>
  <div id="profile">
    <div class="profile_content">
        <div class="info">
            <UserInfo/>
        </div>
    </div>
  </div>
</template>
<script>
import UserInfo from '../components/user/UserInfo.vue';
import { HTTP } from "../http-common";
export default {
    el: "#profile",
    components: { UserInfo },
    created() {
      let token = localStorage.getItem('token');
      if (token) {
        HTTP().get('user')
        .then(response => {
            if (!('name' in response.data) && !('email' in response.data)) {
                window.location.href = '/';
            }
        });
      } else {
          window.location.href = '/';
      }
    }
}
</script>
<style scoped>
.info{
    max-width: 500px;
    margin: 0 auto;
    margin-top: 50px;
}
</style>
