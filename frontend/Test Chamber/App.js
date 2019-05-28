let ax = axios.create({
    baseURL: 'https://jsonplaceholder.typicode.com/'
});

new Vue({
    el: '#app',

    data() {
        return {
            photos: [],
            term: '',
            itemsPerRow: 5,
            isLoading: false
        };
    },
    computed: {
        rowCount: function () {
            return Math.ceil(this.photos.length / this.itemsPerRow);
        },
    },
    methods: {
        handleSearch() {
            this.isLoading = true;

            ax.get(`photos?albumId=${this.term}`).then(response => {
                console.log('API call went okay');
                this.photos = response.data;
                this.isLoading = false;
            }).catch((error)=>{
                console.warn('Something is wrong with API');
            })
        },
         itemCountInRow: function (index) {
             return this.photos.slice((index - 1) * this.itemsPerRow, index * this.itemsPerRow)
        }
    }
})