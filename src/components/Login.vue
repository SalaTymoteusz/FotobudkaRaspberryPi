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
    
    
		  <div class="img-wrapper">
			<img src="https://scontent-lga3-1.cdninstagram.com/vp/d463fae660dfebcee14ad405ec34ef11/5D38F164/t51.2885-15/sh0.08/e35/s750x750/53740642_423012385121406_298701359536165197_n.jpg?_nc_ht=scontent-lga3-1.cdninstagram.com&ig_cache_key=MjAwMTk1NDAxMDI2NTI4MTA5MQ%3D%3D.2" alt="Atul Prajapati">
			<h2>David james</h2>
			<ul>
				<li><a href="https://github.com/atuljustano"><i class="fab fa-github"></i></a></li>
				<li><a href="https://www.instagram.com/atulkprajapati2000/"><i class="fab fa-instagram"></i></a></li>
				<li><a href="https://twitter.com/atuljustano"><i class="fab fa-twitter"></i></a></li>
				<li><a href="https://www.youtube.com/channel/UCf-KfxuY8PZBSD_8RW2nYsw"><i class="fab fa-youtube"></i></a></li>
			</ul>
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

