<template>

    <div id="slides-viewer-holder" class="row">  

        <div class="col-12">

            <div> Slide {{ currentSlide }} of {{ this.$props.slide_history.length }} </div>

            <div id="slides">
                <div class="image-border">
                    <div v-for="(slideHistoryItem, slideIndex) in slide_history" :key="slideIndex">
                        <img :src="JSON.parse(slideHistoryItem.data)"  class="img-fluid" v-if="(slideIndex + 1) == currentSlide" />
                    </div>
                </div>
                <button class="btn btn-primary" @click="prev()">Previous</button>
                <button class="btn btn-primary" @click="next()">Next</button>
            </div>

        </div>

        <div class="col-12 mt-3">

            <div class="card esi-card">
                <div class="card-header esi-card-header-title">
                    <span class="small">Tutor Feeback</span>
                </div>
                <div class="card-body">


                    <div class="px-2" v-for="(feedback, feedbackIndex) in member_feedback" :key="feedbackIndex">
                        <div :id="feedbackItem['name']" class="row px-2" v-for="(feedbackItem, dIndex) in feedback['details']" :key="dIndex"> 
                            <div class="col-4">
                                <div class="font-weight-bold small mt-4">{{ feedbackItem['description'] }}</div>
                            </div>
                            <div class="col-8">
                                <b-form-rating v-model="feedbackItem['value']" variant="warning" class="mb-2" no-border readonly size="lg"></b-form-rating>                        
                            </div>
                        </div>

                        <hr/>

                        <div id="tutor-message" class="row px-2"> 
                            <div class="col-5">                            
                                <div class="font-weight-bold small mt-4">Tutor Feedback</div>
                            </div>
                             <div class="col-7">
                                <div class="small my-4">
                                    <span v-html="feedback.feedback || noFeedBackNotification "></span>
                                </div>
                            </div>
                        </div>

                    </div>
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
        member_feedback: Array,
    },
    data() {
        return {
            currentSlide: 1,
            noFeedBackNotification: "<span class='text-danger small'>No Tutor Feedback Found</span>"
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


