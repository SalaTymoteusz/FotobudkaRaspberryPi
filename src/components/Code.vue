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
                    <input type="text" id="term" class="input" spellcheck="false" v-model="term" required autofocus v-touppercase>
                  </div>
                  <div class="group">
                    <button type="submit" v-bind:class="{'button': normalButton, 'madButton': !normalButton}" value="Submit"><div v-if="wrongCode">WRONG CODE</div><div v-if="!wrongCode">Submit</div></button>
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
      <div class="row">
        <div v-if="toolbar" class="login-wrap-toolbar">
          <button class="smallButton" v-on:click="gridL = !gridL, gridS = !gridS">
            <font-awesome-icon :icon="['fas', 'expand-arrows-alt']"/>Grid Size
          </button>
        </div>
      </div>
    </div>
    <gallery :images="urls" :index="index" @close="index = null"></gallery>
    <div v-bind:class="{'cards-wrapper': gridL, 'cards-wrapper-S': gridS}">
      <div class="card-grid-space" v-for="(image, id) in images" :key="id">
        <div class="card">
          <div class="img-wrapper">
            <img :src="image.url" alt="image.title" @click="index = id">
            <h2>
              <facebook class="social" :url="image.url" scale="2"></facebook>
              <twitter class="social" :url="image.url" scale="2"></twitter>
              <pinterest class="social" :url="image.url" scale="2"></pinterest>
            </h2>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--  insert into card grid space for animation
   data-aos="fade-up"
  data-aos-delay="10"-->
</template>

<script>
import VueGallery from "vue-gallery";
import axios from "axios";
import { Facebook } from "vue-socialmedia-share";
import { Twitter } from "vue-socialmedia-share";
import { Pinterest } from "vue-socialmedia-share";

/* let ax = axios.create({
  baseURL: "https://fotobudkaraspberry.000webhostapp.com/getPhoto.php"
}); */

let ax = axios.create({
  baseURL: "https://fotobudka.projektstudencki.pl/getPhoto.php"
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
      fullPage: true,
      toolbar: false,
      gridL: true,
      gridS: false,
      normalButton: true,
      wrongCode: false

    };
  },
  methods: {
    getLoader(imgname) {
      return require("../assets/loader.gif");
    },
    /* handleSearch() {
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
              this.toolbar = true;
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
    } */
    handleSearch() {
      this.normalButton = true;
      this.images = [];
      this.urls = [];
      this.isLoading = true;
      const CancelToken = axios.CancelToken;
      const source = CancelToken.source();
      ax.get(`/?series_code=${this.term}`)
        .then(response => {
          this.wrongCode = false;
          console.log("Connection with server established.");
          this.images = response.data;
          console.log("Downloaded images: ", this.images);
          this.urls = this.images.map(a => a.url);
          console.log("Extracted urls: ", this.urls);
          if (response.status == 200) {
            this.isLoading = false;
            this.toolbar = true;
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
          if(error.response.status == 500)
          this.wrongCode = true;
          this.normalButton = false;
          this.isLoading = false;
        });
    }
  },
  components: {
    gallery: VueGallery,
    Facebook,
    Twitter,
    Pinterest,
  }
};
</script>