<template>
  <div id="image-viewer">
    <b-modal
      id="image-carousel-modal"
      title="Lesson Image Preview"
      header-bg-variant="primary"
      header-text-variant="white"
      size="xl"
      hide-footer
    >
      <b-carousel id="image-carousel" ref="carousel" :interval="0"
        :controls="false"
        :indicators="false"
        :no-animation="true"
        v-model="activeIndex">

        <b-carousel-slide v-for="(image, index) in images" :key="index" :img-src="getBaseURL(image.path)" :caption="image.file_name"></b-carousel-slide>
      </b-carousel>

      <div class="thumbnail-container">
        <div
          v-for="(image, index) in images"
          :key="index"
          class="thumbnail"
          :class="{ active: activeIndex === index }"
          @click="setActive(index)"
        >
          <img :src="getBaseURL(image.path)" alt="Thumbnail" />
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
export default {
  data() {
    return {
      images: [],
      activeIndex: 0,
    };
  },
  methods: {
    showImageViewer(index, images) {
      console.log("image viewer", index, images);

      this.images = images;
      this.setActive(index);

      this.$bvModal.show("image-carousel-modal");
    },
    setActive: function (index) {
      console.log(index);
      this.activeIndex = index;
    },
    getBaseURL(path) {
      	return window.location.origin + "/" + path;
    }    
  },
};
</script>

<style>
.thumbnail-container {
  display: flex;
  justify-content: center;
  margin-top: 10px;
}

.thumbnail {
  margin: 0 5px;
  cursor: pointer;
}

.thumbnail img {
  width: 100px;
  height: 75px;
  object-fit: cover;
  border: 1px solid #ccc;
}

.thumbnail.active img {
  border-color: blue;
}
</style>