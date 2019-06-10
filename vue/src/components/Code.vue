<template>
  <div>
    <div class="container">
      <h1 class="title is-1 text-center">Photos</h1>
      <div class="row">
          <div class="col-sm text-center">
              <form @submit.prevent="handleSearch">
                  <label class="label" for="term">Enter your code!</label>
                  <div class="control text-center">
                      <input class="input" id="term" type="text" v-model="term" required autofocus>
                  </div>
              </form>
          </div>
      </div>
      <gallery :images="urls" :index="index" @close="index = null"></gallery>
      <div class="container-fluid photos">
        <div class="row align-items-stretch" v-for="(image, id) in images" :key="id" @click="index = id">
          <div class="col-12 col-md-12 col-lg-12 d-block photo-item" data-aos="fade-up" data-aos-delay="100">
              <img :src="image.url" alt="image.title" class="img-fluid">
              <div class="photo-text-more">
                <span class="icon icon-search"></span>
              </div>
          </div>   
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import VueGallery from 'vue-gallery'
import axios from 'axios'
let ax = axios.create({
    baseURL: 'https://fotobudkaraspberry.000webhostapp.com/getPhoto.php/'
});

export default {
  data: function () {
      return {
        items: [],
        images:[],
        urls:[],
        index: null,
        term:'',
        isLoading: false
      };
    },
    methods: {
      handleSearch() {
          this.isLoading = true;
          axios.get(`${'https://cors-anywhere.herokuapp.com/'}https://fotobudkaraspberry.000webhostapp.com/getPhoto.php/?series_code=${this.term}`).then(response => {
              console.log('API call went okay');
              this.images = response.data;
              console.log('images',this.images);
              this.urls = this.images.map(a => a.url);
              console.log('urls',this.urls);
          }).catch((error)=>{
              console.warn('Something is wrong with API');
          })
          this.isLoading = false;
          var array1 = this.images;


          const map1 = array1.map(o => o.url);

        console.log('map');
        console.log(map1);
          
      },
    },

    components: {
      'gallery': VueGallery
    }
}
</script>
