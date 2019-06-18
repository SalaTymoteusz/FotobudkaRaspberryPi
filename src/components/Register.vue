<template>
      <div class="row">
        <div class="login-wrap">
          <div class="login-html">
            <input id="tab-1" type="radio" name="tab" class="sign-in" checked>
            <label for="tab-1" class="tab">Sign Up</label>
            <input id="tab-2" type="radio" name="tab" class="sign-up">
            <label for="tab-2" class="tab"></label>
            <div  @submit.prevent="register" class="login-form">
              <div class="sign-in-htm">
                <div class="group">
                  <label for="user" class="label">Username</label>
                  <input v-model="name" required autofocus type="text" class="input">
                </div>
                <div class="group">
                  <label for="user" class="label">E-mail</label>
                  <input id="user" v-model="email" type="text" class="input">
                </div>
                <div class="group">
                  <label for="pass" class="label">Password</label>
                  <input v-model="password" required type="password" class="input" data-type="password">
                </div>
                <div class="group">
                  <label for="pass" class="label">Confirm Password</label>
                  <input v-model="password_confirmation" required type="password" class="input" data-type="password">
                </div>
                <div class="group">
                  <input type="submit" class="button" value="Sign Up">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
</template>
<script>
    export default {
        data(){
            return {
                name : "",
                email : "",
                password : "",
                password_confirmation : "",
                is_admin : null
            }
        },
        methods: {
            register: function () {
                let data = {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    is_admin: this.is_admin
                }
                this.$store.dispatch('register', data)
               .then(() => this.$router.push('/'))
               .catch(err => console.log(err))
            }
        }
    }
</script>
<style scoped>

</style>