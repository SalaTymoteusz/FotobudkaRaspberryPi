<template>
	<div>
		<div class="containerlogin-box">
        <div class="row">
            <div class="col-lg-3 col-md-2"></div>
            <div class="col-lg-5 col-md-4 login-box">
                <div class="col-lg-12 login-key">
                    <i class="fa fa-key" aria-hidden="true"></i>
                </div>
                <div class="col-lg-12 login-title">
                    FOTOBUDKA
                </div>
				<div class="container">
				<div class="row">
					<div class=" col">
						<a href="#">
							<button class="social-buttons btn btn-outline-primary"><font-awesome-icon :icon="['fab', 'google']" /></button>
						</a>
					</div>	     
					<div class="col">
						<a href="#">
							<button class="social-buttons btn btn-outline-primary"><font-awesome-icon :icon="['fab', 'instagram']" /></button>
						</a>
					</div>	            
					<div class="col">
						<a href="#">
							<button class="social-buttons btn btn-outline-primary"><font-awesome-icon :icon="['fab', 'facebook']" /></button>
						</a>
					</div>
				</div>
				</div>
                <div class="col-lg-12 login-form">
                    <div class="col-lg-12 login-form">
                        <form class="login" @submit.prevent="login">
                            <div class="form-group">
                                <label class="form-control-label">EMAIL</label>
                                <input required v-model="email" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">HASŁO</label>
                                <input required v-model="password" type="password" class="form-control" i>
                            </div>
                            <div class="col-lg-12 loginbttm text-right">
                              
                                <div class="login-btm  ">
                                    <button type="submit" class="btn-auth btn-primary"><span class="btn-auth-text">LOGIN</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-3 col-md-2"></div>
            </div>
        </div>
    </div>
	</div>
</template>

<script>
export default {
		data(){
			return {
				email : "",
            	password : ""
            }
		},
		methods: {
		 	login: function () {
		   		let email = this.email 
		   		let password = this.password
		   		this.$store.dispatch('login', { email, password })
		   		.then(() => this.$router.push('/'))
		   		.catch(err => console.log(err))
		   	}
		}
}
</script>

