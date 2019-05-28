<template>
<div id="app">

    <section class="section">
        <div class="container">

            <h1 class="title is-1">zdjecia</h1>

            <div class="columns">
                <div class="column is-4">
                    <form @submit.prevent="handleSearch">
                        <label class="label" for="term">Wpisz kod</label>
                        <div class="control">
                            <input class="input" id="term" type="text" v-model="term" required autofocus>
                        </div>

                        <div class="control">
                            <button class="button is-secondary" :class="{'is-loading': isLoading}" type="submit">Szukaj
                                </button>
                        </div>
                    </form>
                </div>
            </div>

            <ul class="columns">
                <li class="column is-one-third" v-for="photo in photos" v-bind:key="photo.id">

                    <div class="card">
                        <div class="card-content">
                            <div class="media">
                                <div class="media-content">
                                    <p>Id zdjęcia: {{photo.id}}</p>
                                    <p>Id albumu: {{photo.albumId}}</p>
                                    <p>Miniaturka<img :src="photo.thumbnailUrl" /></p>
                                </div>
                            </div>

                            <div class="content">
                                <br>
                                <a :href="photo.url" class="button is-primary" target="_blank">Pełna rozdzielczość</a>
                            </div>
                        </div>
                    </div>

                </li>
            </ul>

        </div>
    </section>
</div>

</template>

<script>
import axios from 'axios'
let ax = axios.create({
  baseURL: 'https://jsonplaceholder.typicode.com/'
});

new Vue({
  el: '#app',

  data () {
    return {
      photos: [],
      term: '',
      isLoading: false
    }
  },

  methods: {
    handleSearch () {
      this.isLoading = true
      ax.get(`photos?albumId=${this.term}`).then(response => {
        this.photos = response.data
        this.isLoading = false
      })
    }
  }
})
</script>

<style>
body {
    margin:0;
    padding:0;
    font-family:sans-serif;
    background:#fbfbfb;
}
.card {
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    width:300px;
    min-height:400px;
    background:#fff;
    box-shadow:0 20px 50px rgba(0,0,0,.1);
    border-radius:10px;
    transition:0.5s;
}
.card:hover {
    box-shadow:0 30px 70px rgba(0,0,0,.2);
}
.card .box {
    position:absolute;
    top:50%;
    left:0;
    transform:translateY(-50%);
    text-align:center;
    padding:20px;
    box-sizing:border-box;
    width:100%;
}
.card .box .img {
    width:120px;
    height:120px;
    margin:0 auto;
    border-radius:50%;
    overflow:hidden;
}
.card .box .img img {
    width:100%;
    height:100%;
}
.card .box h2 {
    font-size:20px;
    color:#262626;
    margin:20px auto;
}
.card .box h2 span {
    font-size:14px;
    background:#e91e63;
    color:#fff;
    display:inline-block;
    padding:4px 10px;
    border-radius:15px;
}
.card .box p {
    color:#262626;
}
.card .box span {
    display:inline-flex;
}
.card .box ul {
    margin:0;
    padding:0;
}
.card .box ul li {
    list-style:none;
    float:left;
}
.card .box ul li a {
    display:block;
    color:#aaa;
    margin:0 10px;
    font-size:20px;
    transition:0.5s;
    text-align:center;
}
.card .box ul li:hover a {
    color:#e91e63;
    transform:rotateY(360deg);
}
</style>
