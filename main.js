const app = new Vue({
    el: '#app',
    data: {
        code: '',
        codes: '',
        id: ''
    },
    created: function () {
        axios.get('https://jsonplaceholder.typicode.com/todos/5')
            .then(function (response) {
                this.codes = response.data
                console.log(codes);
                this.id = this.codes.id;
                console.log(id);
                {
                    console.log(id);
                }
                if(id == 5)

                console.log('equal')
                else
                console.log('nie')
            }
            )
            .catch(function (error) {
                this.codes = "Zly kod"
            })
    },
    methods: {
        createPost() {
            axios.post('http://rest.learncode.academy/api/fotobudka/code', {
                code: this.code
            },
                {
                    headers: {
                        'Content-type': 'application/json',
                    }
                }).then((response) => { })
                .catch((e) => {
                    console.error(e)
                })
            }
    }
});