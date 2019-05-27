const app = new Vue({
    el: '#app',
    data: {
        code: '',
        codes: '',
        id: []
    },
    created: function () {
        axios.get('https://jsonplaceholder.typicode.com/todos')
            .then(function (response) {
                this.codes = response.data;
                var all_id = [];
                for (id in codes) {
                    if (codes.hasOwnProperty(id)) {
                        all_id += id;
                    }
                }
                console.log(all_id)
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
                }
                )
        },
        verifyCode() {
            console.log(jotpe.all_id)
            for (var i in jotpe.all_id) {
                if (this.all_id == this.code)
                    console.log('sieeema');
                else 
                    console.log(this.all_id);
                }
            
        },
        captureCode() {
            console.log(this.code);
            this.verifyCode();
        }
        }
});