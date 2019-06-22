<template>
  <div>
    <div class="container">
      <form @submit.prevent="handleSearch">
        <div class="row">
          <div class="login-wrap-home">
            <div class="login-html">
              <input id="tab-1" type="radio" name="tab" class="sign-in" checked>
              <label for="tab-1" class="tab">Enter your code</label>
              <input id="tab-2" type="radio" name="tab" class="sign-up">
              <label for="tab-2" class="tab"></label>
              <div class="login-form">
                <div class="sign-in-htm">
                  <div class="group">
                    <input type="text" id="term" class="input" v-model="term" required autofocus>
                  </div>
                  <div class="group">
                    <button type="submit" class="button" value="Submit">Submit</button>
                    <div v-if="isLoading">
                      <div class="loader"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <gallery :images="urls" :index="index" @close="index = null"></gallery>
    <div class="cards-wrapper">
      <div
        class="card-grid-space"
        data-aos="fade-up"
        data-aos-delay="100"
        v-for="(image, id) in images"
        :key="id"
        @click="index = id"
      >
        <a class="card">
          <img :src="image.url" alt="image.title">
        </a>
      </div>
    </div>
  </div>
</template>

<script>
import VueGallery from "vue-gallery";
import axios from "axios";

let ax = axios.create({
  baseURL: "https://fotobudkaraspberry.000webhostapp.com/getPhoto.php"
});

/* let ax = axios.create({
  baseURL: "https://jsonplaceholder.typicode.com/photos"
}); */

function randInt(max) {
  return Math.floor(Math.random() * Math.floor(max));
}

export default {
  data: function() {
    return {
      items: [],
      images: [],
      urls: [],
      index: null,
      term: "",
      isLoading: false,
      fullPage: true
    };
  },
  methods: {
    getLoader(imgname) {
      return require("../assets/loader.gif");
    },
    /*     handleSearch() {
      this.images = [];
      this.urls = [];
      this.isLoading = true;
      const CancelToken = axios.CancelToken;
      const source = CancelToken.source();
      ax.get(`?albumId=${this.term}`)
        .then(response => {
          console.log("Connection with server established.");
          this.images = response.data;

          if (response.status != 404) {
            console.log("Downloaded images: ", this.images);
            this.urls = this.images.map(a => a.url);
            console.log("Extracted urls: ", this.urls);
            if (response.status == 200) {
              this.isLoading = false;
            }
          } else {
            this.isLoading = false;
          }
        })
        .catch(error => {
          if (error.response) {
            console.warn(error.response.data);
            console.warn(error.response.status);
            console.warn(error.response.headers);
          } else if (error.request) {
            console.warn(error.request);
          } else {
            console.warn("Error", error.message);
          }
          this.isLoading = false;
        });
    }, */
    handleSearch() {
      this.images = [];
      this.urls = [];
      this.isLoading = true;
      const CancelToken = axios.CancelToken;
      const source = CancelToken.source();
      ax.get(`/?series_code=${this.term}`)
        .then(response => {
          console.log("Connection with server established.");
          this.images = response.data;

          if (response.status != 404) {
            console.log("Downloaded images: ", this.images);
            this.urls = this.images.map(a => a.url);
            console.log("Extracted urls: ", this.urls);
            if (response.status == 200) {
              this.isLoading = false;
            }
          } else {
            this.isLoading = false;
          }
        })
        .catch(error => {
          if (error.response) {
            console.warn(error.response.data);
            console.warn(error.response.status);
            console.warn(error.response.headers);
          } else if (error.request) {
            console.warn(error.request);
          } else {
            console.warn("Error", error.message);
          }
          this.isLoading = false;
        });
    }
  },
  components: {
    gallery: VueGallery
  }
};
</script>