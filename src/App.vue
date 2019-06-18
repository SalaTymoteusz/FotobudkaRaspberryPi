<template>
  <div>
    <div id="app">
      <div id="menuArea">
        <input type="checkbox" id="menuToggle" hidden/>
        <label for="menuToggle" class="menuOpen"><div class="open"></div></label>

        <div class="menu menuEffects">
          <label for="menuToggle"></label>
          <div class="menuContent">
            <ul>
              <li><a href="#" class="btn menuToggle"><router-link to="/">Home</router-link></a></li>
              <li><a href="#" class="btn"><router-link v-if="!isLoggedIn" to="/login">Login</router-link></a></li>
              <li><a v-if="isLoggedIn" @click="logout" class="btn">Logout</a></li>
              <li><a href="#" class="btn"><router-link v-if="!isLoggedIn" to="/register">register</router-link></a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="top-panel">
            top panel
      </div>
        <main class="container">
          <router-view/>
        </main> 
    </div>
  </div>
</template>
<script>

export default {
  name: "App",
  computed: {
    isLoggedIn: function() {
      return this.$store.getters.isLoggedIn;
    }
  },
  methods: {
    logout: function() {
      this.$store.dispatch("logout").then(() => {
        this.$router.push("/login");
      });
    },
    created: function() {
      this.$http.interceptors.response.use(undefined, function(err) {
        return new Promise(function(resolve, reject) {
          if (
            err.status === 401 &&
            err.config &&
            !err.config.__isRetryRequest
          ) {
            this.$store.dispatch(logout);
          }
          throw err;
        });
      });
    },
   getImgUrl(pic) {
    return require('./assets/logo.png')
  }
  }
};
</script>
<style>
body {
  background: rgb(40, 16, 66) !important;
  min-width:300px;
}
#app {
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
</style>
