<template>
	<div>
		<div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-2"></div>
            <div class="col-lg-5 col-md-4 login-box">
                <div class="col-lg-12 login-key">
                    <i class="fa fa-key" aria-hidden="true"></i>
                </div>
                <div class="col-lg-12 login-title">
                    FOTOBUDKA
                </div>
                <div class="col-lg-12 login-form">
                    <div class="col-lg-12 login-form">
                        <form @submit.prevent="register">
                            <div class="form-group">
                                <label class="form-control-label">IMIĘ I NAZWISKO</label>
                                <input v-model="name" required autofocus type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">EMAIL</label>
                                <input required v-model="email" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">HASŁO</label>
                                <input v-model="password" required type="password" class="form-control" id="password" >
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">POWTÓRZ HASŁO</label>
                                <input id="password-confirm" v-model="password_confirmation" required type="password" class="form-control" i>
                            </div>

                            <div class="col-lg-12 loginbttm text-right">
                              
                                <div class="login-btm  login-button">
                                    <button type="submit" class="btn-auth btn-primary"><span class="btn-auth-text">REJESTRACJA</span></button>
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