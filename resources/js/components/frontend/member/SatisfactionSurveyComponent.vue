<template>


    <b-modal ref="ratingAndFeedBack" header-bg-variant="primary" header-text-variant="white" size="lg" hide-footer>


        <template #modal-header>
            <div class="mx-auto">
                <h4 class="text-center text-white">Satisfaction Survey</h4>
            </div>           
        </template>


        <h5 class="text-maroon">Course Satisfaction Survey</h5>

        <h6 class="font-weight-bold">Your comments and suggestions will assist us in continuosly improving our services</h6>


        <hr/>

        <div id="satisfactionSurveyWrapper" class="row">    

            <div id="starsRating" class="col-8">

                <div class="row">
                    <div class="col-7">
                        <div class="feedback-title">
                            General Course Satisfaction
                        </div>
                    </div>
                    <div class="col-5">
                        <star-rating v-model="generalCourseRating" v-bind:show-rating="false" v-bind:star-size="30" v-bind:animate="true" v-bind:padding="5"></star-rating>            
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-7"> 
                        <div class="feedback-title">           
                            Teacher Performance Satisfaction
                        </div>
                    </div>
                    <div class="col-5">    
                        <star-rating v-model="teacherPerformaceRating" v-bind:show-rating="false" v-bind:star-size="30" v-bind:animate="true" v-bind:padding="5"></star-rating>  
                    </div>                
                </div>

                <div class="row mt-3">
                    <div class="col-7">        
                        <div class="feedback-title">    
                            Student Self-Performance Satisfaction
                        </div>
                    </div>
                    <div class="col-5">    
                        <star-rating v-model="studentSelfPerformanceRation" v-bind:show-rating="false" v-bind:star-size="30" v-bind:animate="true" v-bind:padding="5"></star-rating>
                    </div>
                </div>

            </div>

            <div id="feebacks" class="col-4">
                <div class="text-center">
                    <button class="btn btn-success w-100 mb-4">
                        <span class="small">
                            Write Course Feedback
                        </span>
                    </button>
                    <button class="btn btn-success w-100">
                        <span class="small">
                            Leave Encouraging Messages
                        </span>
                    </button>
                </div>               
            </div>

        </div>

        <div class="my-5 text-center">
            <button class="btn btn-success">
                Submit Feedback
            </button>
        </div>

    </b-modal>

</template>

<style scoped>

    .feedback-title {
        min-height: 40px;
        display: flex;
        align-items: center;
        font-weight: bold;
    }


</style>
<script>
import StarRating from 'vue-star-rating'
export default {
    name: "satisfactionSurveyComponent",
    components: { StarRating },
    data() {
        return {
            generalCourseRating: null,
            teacherPerformaceRating: null,
            studentSelfPerformanceRation: null            
        }
    },    
    methods: {
        showRatingAndFeedback() {
            this.$refs['ratingAndFeedBack'].show();
        }
    }    
}
</script>