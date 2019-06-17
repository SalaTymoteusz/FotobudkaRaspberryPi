<template>
  <div class="vld-parent">
    <loading :active.sync="isLoading" 
        :can-cancel="false" 
        :on-cancel="onCancel"
        :is-full-page="fullPage"></loading>
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
import Loading from 'vue-loading-overlay';

let ax = axios.create({
    baseURL: 'https://fotobudkaraspberry.000webhostapp.com/getPhoto.php'
});

export default {
  data: function () {
      return {
        items: [],
        images:[],
        urls:[],
        index: null,
        term:'',
        isLoading: false,
        fullPage: true
      };
    },
    methods: {
      handleSearch() {
          this.isLoading = true;
          ax.get(`/?series_code=${this.term}`).then(response => {
              console.log('API call went okay');
              this.images = response.data; 
              console.log('images',this.images);
              this.urls = this.images.map(a => a.url);
              console.log('urls',this.urls);
              if(response.status == 200){
                  this.isLoading = false;
               }
          }).catch((error)=>{
              console.warn('Something is wrong with API');
          })
               
      },
      doAjax() {
                this.isLoading = true;
                setTimeout(() => {
                  this.isLoading = false
                },5000)
            },
            onCancel() {
              console.log('User cancelled the loader.')
            }
    },
    components: {
      'gallery': VueGallery,
      Loading
    }
}
</script>