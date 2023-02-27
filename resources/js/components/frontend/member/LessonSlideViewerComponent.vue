<template>

    <div id="slides-viewer-holder" class="row">  

        <div class="col-12">

            <div> Slide {{ currentSlide }} of {{ this.$props.slide_history.length }} </div>

            <div id="slides">
                <div class="image-border">
                    <div v-for="(slideHistoryItem, slideIndex) in slide_history" :key="slideIndex" >
                        <img :src="JSON.parse(slideHistoryItem.data)"  class="img-fluid" v-if="(slideIndex + 1) == currentSlide" />
                    </div>
                </div>
                <button class="btn btn-primary" @click="prev()">Previous</button>
                <button class="btn btn-primary" @click="next()">Next</button>
            </div>

        </div>

        <div class="col-12 mt-4">

            <div id="rating-container" class="row"> 
                <div class="col-3">
                    Understanding Level
                </div>
                <div class="col-1"> : </div>
                <div class="col-8">
                    <b-form-rating v-model="rating" variant="warning" class="mb-2" no-border readonly size="lg"></b-form-rating>
                </div>
            </div>

        </div>

    </div>




</template>

<script>
export default {
    name: "lessonSlideViewerComponent",
    components: { },
    props: {
        csrf_token: String,		
        api_token: String,
        reservation: Object,
        slide_history: Array,

    },
    data() {
        return {
            currentSlide: 1,
            rating: 5
        };
    },
    mounted() {
        this.currentSlide = 1;        
    },
    methods: {
        prev() {
            if (this.currentSlide > 1) {
                this.currentSlide--;
                this.$forceUpdate();
            }   
         
        },    
        next() {
            if (this.currentSlide < this.$props.slide_history.length) {
                this.currentSlide++;
                this.$forceUpdate();
            } 
        }
    }
};
</script>

<style lang="scss" scoped>

    .image-border {
        border: 7px solid #0072ba;
        margin: 3px;
        
    }

</style>


