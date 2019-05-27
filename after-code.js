const app = new Vue({
    el: '#app',
    data: {
        id: [],
        url: ''
    },
    
    created: function () {

        var parameter = window.location.hash.substring(1);
        axios.get('https://jsonplaceholder.typicode.com/photos/' + parameter)
        .then(function (response) {
            console.log(response.data)
            var url = response.data.url;
            console.log(url);

        })
    },
    methods: {
        showcode()
        {
            console.log(this.parameter)
        }
    }


});