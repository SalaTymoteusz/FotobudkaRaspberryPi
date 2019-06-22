<template>
  <div class="row">
    <div class="login-wrap">
      <div class="login-html">
        <input id="tab-1" type="radio" name="tab" class="sign-in" checked>
        <label for="tab-1" class="tab">Sign In</label>
        <input id="tab-2" type="radio" name="tab" class="sign-up">
        <label for="tab-2" class="tab"></label>
        <div @submit.prevent="login" class="login-form">
          <div class="sign-in-htm">
            <div class="group">
              <label for="user" class="label">E-mail</label>
              <input v-model="email" type="text" class="input">
            </div>
            <div class="group">
              <label for="pass" class="label">Password</label>
              <input v-model="password" type="password" class="input" data-type="password">
            </div>
            <div class="hr"></div>
            <div class="group">
              <input type="submit" class="button" value="Sign In">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: "",
      password: ""
    };
  },
  methods: {
    login: function() {
      let email = this.email;
      let password = this.password;
      this.$store
        .dispatch("login", { email, password })
        .then(() => this.$router.push("/"))
        .catch(err => console.log(err));
    }
  }
};
</script>

