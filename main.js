const app = new Vue({
    el: '#app',
    data: {
        code: null,
    },
    methods: {
        createPost() {
            axios.post('http://rest.learncode.academy/api/fotobudka/code', {
                code: this.code
            },
                {        headers: {
                        'Content-type': 'application/json',
                    }
            }).then((response) => { })
                .catch((e) => {
                    console.error(e)
                })
        }
    }
});