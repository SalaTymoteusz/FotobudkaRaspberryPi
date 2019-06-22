<template>
  <div>
    <div id="app">
      <navbar>
        <div class="top-bar-under"><div class="logo-under"></div></div>
        <div class="top-bar"><img src="@/assets/logo.png"/></div>
        <div id="wrapper">
          <input type="checkbox" id="menu" name="menu" class="menu-checkbox">
          <div class="menu">
            <label class="menu-toggle" for="menu">
              <span>Toggle</span>
            </label>
            <ul>
              <li>
                <a v-on:click="closeMenu" href="#">
                  <router-link to="/">HOME</router-link>
                </a>
              </li>
              <li>
                <a v-on:click="closeMenu" href="#">
                  <router-link v-if="!isLoggedIn" to="/login">LOGIN</router-link>
                </a>
              </li>
              <li>
                <a v-on:click="closeMenu" href="#">
                  <router-link v-if="!isLoggedIn" to="/register">REGISTER</router-link>
                </a>
              </li>
              <li>
                <a v-on:click="closeMenu" v-if="isLoggedIn" @click="logout" class="btn">LOGOUT</a>
              </li>
            </ul>
          </div>
        </div>
      </navbar>

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
      return require("./assets/logo.png");
    },
    closeMenu() {
      document.getElementById("menu").checked = false;
    }
  }
};
</script>
<style>
body {
  background: rgb(40, 16, 66) !important;
}
#app {
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
</style>
<!-- 
additional menu depth options

<li>      
        <label for="menu-3">Menu-3</label>
        <input type="checkbox" id="menu-3" name="menu-3" class="menu-checkbox">
        <div class="menu">
          <label class="menu-toggle" for="menu-3"><span>Toggle</span></label>
          <ul>
            <li>
              <a href="#">Menu-3-1</a>
            </li>
            <li>
              <label for="menu-3-2">Menu-3-2</label>
              <input type="checkbox" id="menu-3-2" name="menu-3-2" class="menu-checkbox">
              <div class="menu">
                <label class="menu-toggle" for="menu-3-2"><span>Toggle</span></label>
                <ul>
                  <li>
                    <a href="#">Menu-3-2-1</a>
                  </li>
                  <li>
                    <a href="#">Menu-3-2-2</a>
                  </li>
                  <li>
                    <label for="menu-3-2-3">Menu-3-2-3</label>
                    <input type="checkbox" id="menu-3-2-3" name="menu-3-2-3" class="menu-checkbox">
                    <div class="menu">
                      <label class="menu-toggle" for="menu-3-2-3"><span>Toggle</span></label>
                      <ul>
                        <li>
                          <a href="#">Menu-3-2-3-1</a>
                        </li>
                        <li>
                          <a href="#">Menu-3-2-3-2</a>
                        </li>
                        <li>
                          <a href="#">Menu-3-2-3-3</a>
                        </li>
                        <li>
                          <a href="#">Menu-3-2-3-4</a>
                        </li>
                      </ul>
                    </div>
                  </li>
                  <li>
                    <a href="#">Menu-3-2-4</a>
                  </li>
                </ul>
              </div>
            </li>
            <li>
              <a href="#">Menu-3-3</a>
            </li>
            <li>
              <a href="#">Menu-3-4</a>
            </li>
          </ul>
        </div>
      </li>
      <li>
        <label for="menu-4">Menu-4</label>
        <input type="checkbox" id="menu-4" name="menu-4" class="menu-checkbox">
        <div class="menu">
          <label class="menu-toggle" for="menu-4"><span>Toggle</span></label>
          <ul>
            <li>
              <a href="#">Menu-4-1</a>
            </li>
            <li>
              <a href="#">Menu-4-2</a>
            </li>
            <li>
              <a href="#">Menu-4-3</a>
            </li>
            <li>
              <a href="#">Menu-4-4</a>
            </li>
          </ul>
        </div>
      </li>      
      <li>
        <label for="menu-5">Menu-5</label>
        <input type="checkbox" id="menu-5" name="menu-5" class="menu-checkbox">
        <div class="menu">
          <label class="menu-toggle" for="menu-5"><span>Toggle</span></label>
          <ul>
            <li>
              <a href="#">Menu-5-1</a>
            </li>
            <li>
              <a href="#">Menu-5-2</a>
            </li>
            <li>
              <a href="#">Menu-5-3</a>
            </li>
            <li>
              <a href="#">Menu-5-4</a>
            </li>
          </ul>
        </div>
      </li>
      <li>
        <a href="#">Menu-6</a>
              </li>-->