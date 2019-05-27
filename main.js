const app = new Vue({
    el: '#app',
    data: {
        code: '2',
        codes: '',
        id: [],
    },
    created: function () {
        var all_id = [];
        axios.get('https://jsonplaceholder.typicode.com/todos')
            .then(function (response) {
                console.log(response.data.id)

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
            console.log('z')
           /* for (var i in this.all_id) {
                if (this.all_id == this.code)
                    console.log('sieeema');
                else 
                    console.log(this.all_id);
                }*/
            
        },
        captureCode() {
            console.log(this.code);
        },
        passingToNextPage() {
            var queryString = "#" + this.code
            window.location.href = "after-code.html" + queryString
        }
        }
});