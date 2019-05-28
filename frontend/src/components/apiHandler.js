let ax = axios.create({
    baseURL: 'https://jsonplaceholder.typicode.com/'
});

new Vue({
    el: '#app',

    data() {
        return {
            photos: [],
            term: '',
            isLoading: false
        };
    },

 

    methods: {
        handleSearch() {
            this.isLoading = true;

            ax.get(`photos?albumId=${this.term}`).then(response => {
                this.photos = response.data;
                this.isLoading = false;
            })
        }
    }
})