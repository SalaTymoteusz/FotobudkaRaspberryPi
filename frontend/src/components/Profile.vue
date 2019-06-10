<template>
	<div class="profile">
		<div class="container">
      <div class="row">
          <div class="col icon-middle ">
            <h1>Under construction</h1>
          </div>
      </div>
      <div class="row">
          <div class="col">
            <h1 class="icon-middle"><font-awesome-icon :icon="['fas', 'business-time']" /></h1>
          </div>
      </div>
    </div>
	</div>
</template>
<script>
  export default {
    name: 'Profile',
    computed : {
      isLoggedIn : function(){ return this.$store.getters.isLoggedIn}
    },
    methods: {
      logout: function () {
        this.$store.dispatch('logout')
        .then(() => {
          this.$router.push('/login')
        })
      }
    },
    created: function () {
      this.$http.interceptors.response.use(undefined, function (err) {
        return new Promise(function (resolve, reject) {
          if (err.status === 401 && err.config && !err.config.__isRetryRequest) {
            this.$store.dispatch(logout)
          }
          throw err;
        });
      });
    }
  }
</script>
<style>
.icon-middle{
  text-align: center;
}
</style>
