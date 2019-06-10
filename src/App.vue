<template>
<div>
  <div id="app">
    <sidebar-menu @collapse="onCollapse"  @itemClick="onItemClick" :menu="menu" />
    <div  class="site-wrap">
      <!-- <router-link to="/about">About</router-link><span v-if="isLoggedIn"> | <a @click="logout">Logout</a></span> | -->
      <!--
      <header id="page-wrap" class="header-bar d-flex d-lg-block align-items-center">
        <div class="site-logo">
          <router-link to="/">FOTOBUDKA</router-link>
        </div>
        
        <div class="d-inline-block d-xl-none ml-md-0 ml-auto py-3" style="position: relative; top: 3px;"><a href="#" class="site-menu-toggle js-menu-toggle text-white"><span class="icon-menu h3"></span></a></div>

        <div class="main-menu">
          <ul class="js-clone-nav">
            <li><router-link to="/">Home</router-link></li>
            <li class="active"><router-link to="/code">Example</router-link></li>
            <li v-if="!isLoggedIn"><router-link to="/login">Login</router-link></li>
            <li v-if="!isLoggedIn"><router-link to="/register">Register</router-link></li>
            <li><span v-if="isLoggedIn"><a @click="logout">Logout</a></span></li>
          </ul>
        </div>
      </header> 
      -->
      <main class="main-content">
        <router-view/>
      </main>
</div> <!-- .site-wrap --> 

</div>

</div>
</template>
<script>

  export default {
    name: 'App',
    data() {
            return {
                menu: [
                    {
                        header: true,
                        title: 'FOTOBUDKA',
                        // component: componentName
                        // visibleOnCollapse: true
                        // class: ''
                        // attributes: {}
                    },
                    {
                        href: '/',
                        title: 'Home',
                        icon: {
                            element: 'font-awesome-icon',
                            attributes: {
                            icon: 'home'}
                        }
                    },
                    {
                        href: '/login',
                        title: 'Login',
                        icon: {
                            element: 'font-awesome-icon',
                            attributes: {
                            icon: 'user'}
                        }
                    },
                    {
                        href: '/register',
                        title: 'Register',
                        icon: {
                            element: 'font-awesome-icon',
                            attributes: {
                            icon: 'user-plus'}
                        }
                    },
                ]
            }
        },
    computed : {
      isLoggedIn : function(){ return this.$store.getters.isLoggedIn}
    },
    methods: {
      onCollapse(collapsed) {},
      onItemClick(event, item) {},
      logout: function () {
        this.$store.dispatch('logout')
        .then(() => {
          this.$router.push('/login')
        })
      },
      created: function () {
      this.$http.interceptors.response.use(undefined, function (err) {
        return new Promise(function (resolve, reject) {
          if (err.status === 401 && err.config && !err.config.__isRetryRequest) {
            this.$store.dispatch(logout)
          }
          throw err
        })
      })
    }
  } 
}
</script>

<style>
body{
 background: rgb(29, 29, 43) !important;
}
::-webkit-scrollbar {
    display: none;
}
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
</style>
