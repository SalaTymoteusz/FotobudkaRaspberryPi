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
                                    <button type="submit" class="btn btn-outline-primary">REJESTRACJA</button>
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
<style>
body {
  /*background-image:url("https://i.imgur.com/fZhxtub.jpg");
  background-repeat:no-repeat;
  background-size:cover;*/
    font-family: 'Roboto', sans-serif;
	background-color: #222D32;
}
.login-box {
    margin-top: 75px;
    height: auto;
    background: rgba(8, 36, 49, 0.9);
    text-align: center;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
	font-family: 'Reem Kufi', sans-serif;
}
.login-key {
    height: 100px;
    font-size: 80px;
    line-height: 100px;
    background: -webkit-linear-gradient(#27EF9F, #0DB8DE);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.login-title {
    margin-top: 15px;
    text-align: center;
    font-size: 30px;
    letter-spacing: 2px;
    margin-top: 15px;
    font-weight: bold;
    color: #ECF0F5;
	font-family: 'Reem Kufi', sans-serif;
}
.login-form {
    margin-top: 25px;
    text-align: left;
}
input[type=text] {
    background-color: #1a22266b;
    border: none;
    border-bottom: 2px solid #0DB8DE;
    border-top: 0px;
    border-radius: 0px;
    font-weight: bold;
    outline: 0;
    margin-bottom: 20px;
    padding-left: 0px;
    color: #ECF0F5;
	font-family: 'Reem Kufi', sans-serif;
}
input[type=password] {
    background-color: #1a22266b;
    border: none;
    border-bottom: 2px solid #0DB8DE;
    border-top: 0px;
    border-radius: 0px;
    font-weight: bold;
    outline: 0;
    padding-left: 0px;
    margin-bottom: 20px;
    color: #ECF0F5;
	font-family: 'Reem Kufi', sans-serif;
}
.form-group {
    margin-bottom: 40px;
    outline: 0px;
}
.form-control:focus {
    border-color: inherit;
    -webkit-box-shadow: none;
    box-shadow: none;
    border-bottom: 2px solid #0DB8DE;
    outline: 0;
    background-color: #1A2226;
    color: #ECF0F5;
	transition:1s;
}
input:focus {
    outline: none;
    box-shadow: 0 0 0;
}
label {
    margin-bottom: 0px;
}
.form-control-label {
    font-size: 10px;
    color: #ffffff;
    font-weight: bold;
    letter-spacing: 1px;
}
.btn-outline-primary:hover {
    background-color: #0DB8DE;
    right: 0px;
}
.login-button {
    padding-right: 0px;

    margin-bottom: 25px;
}

.login-text {
    text-align: left;
    padding-left: 0px;
    color: #A2A4A4;
}
.loginbttm {
    padding: 0px;
}
.Conceptualize-text{
font-weight:700;
color:white;
font-family: 'Reem Kufi', sans-serif;
}
.social-buttons{
	color:white;
	font-size:60px;
}
</style>