const app = new Vue({
    el: '#app',
    data: {
        code: '',
    },
    methods: {
        this.code = '',
        axios.post('http://rest.learncode.academy/api/fotobudka/code' + code)
            .then(function (response) {

            })
    }
});