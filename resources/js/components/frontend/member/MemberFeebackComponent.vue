<template>


    <b-modal ref="memberFeedback" header-bg-variant="primary" header-text-variant="white" size="lg" no-close-on-backdrop hide-footer no-close-on-esc  hide-header-close>


        <template #modal-header>
            <div class="mx-auto">
                <h4 class="text-center text-white">Student Feedback</h4>
            </div>           
        </template>

        <div id="ratingsForm" v-show="showRatings">
            <h5 class="text-maroon">Student Feedback</h5>
           
            <h6 class="font-weight-bold">Your student feedbacks and suggestions will assist us in continuosly improving our services</h6>
            <hr/>
            <div id="ratingsWrapper" class="row">    

                <div id="starsRating" class="col-8">

                    <!--
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
                    -->

                    <div class="row mt-3">
                        <div class="col-7"> 
                            <div class="feedback-title">           
                                Student Performance Satisfaction
                            </div>
                        </div>
                        <div class="col-5"> 
                            <star-rating v-model="studentPerformanceRating" v-bind:show-rating="false" v-bind:star-size="30" v-bind:animate="true" v-bind:padding="5"></star-rating>  
                        </div>                
                    </div>

                    <!--
                    <div class="row mt-3">
                        <div class="col-7">        
                            <div class="feedback-title">    
                                Teacher Self-Performance Satisfaction
                            </div>
                        </div>
                        <div class="col-5">   
                            <star-rating v-model="teacherSelfPerformanceRating" v-bind:show-rating="false" v-bind:star-size="30" v-bind:animate="true" v-bind:padding="5"></star-rating>
                        </div>
                    </div>
                    -->

                </div>

                <div id="feedbacks" class="col-4">
                    <div class="text-center">
                        <button class="btn btn-success w-100 mb-4" @click="showFeebackForm">
                            <span class="small">
                                Write Course Feedback
                            </span>
                        </button>
                        <button class="btn btn-success w-100" @click="showMessageForm">
                            <span class="small">
                                Leave Encouraging Messages
                            </span>
                        </button>
                    </div>               
                </div>

            </div>
            <div class="my-5 text-center">
                <button class="btn btn-success" @click="submitFeedback()">
                    Submit Feedback
                </button>
            </div>            
        </div>



        <div id="feedbackForm" class="row" v-show="showFeedback">
            <div class="col-12">
                <h5 class="text-maroon">Course Feeback</h5>           
                <h6 class="font-weight-bold">Your feedback will help us make the course better and improve our services</h6>
                <hr/>     
                <div id="feedbackFormWrapper">         
                    <vue-ckeditor v-model="feeback" :config="config" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" />
                </div>       

                <div class="my-2 text-center">
                    <button class="btn btn-success" @click="showRatingsForm()">
                        Back
                    </button>
                </div> 

            </div>
        </div>


        <div id="messageForm" class="row" v-show="showMessage">
            <div class="col-12">
                <h5 class="text-maroon">Give us a message </h5>           
                <h6 class="font-weight-bold">Your message will help improve our services and make teachers proud of their effort</h6>
                <hr/>     
                <div id="messageFormWrapper">         
                    <vue-ckeditor v-model="message" :config="config" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" />
                </div>    

                <div class="my-2 text-center">
                    <button class="btn btn-success" @click="showRatingsForm()">
                        Back
                    </button>
                </div>

            </div>
           
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
import VueCkeditor from 'vue-ckeditor2';


export default {
    name: "MemberFeebackComponent",
    components: { StarRating, VueCkeditor},
    props: {
        csrf_token: String,		
        api_token: String 
    },        
    data() {
        return {
            //forms
            showRatings: false,
            showFeedback: false,
            showMessage: false,

            //Stars Container Ratings
            //generalCourseRating: null,
            studentPerformanceRating: null,
            //teacherSelfPerformanceRating: null,

            //Contents
            feeback: null,
            message: null,     

            //Configuration for CKEditor
            config: {
                toolbar: [
                    [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'NumberedList' ],
                    
                ],
                height: 300
            },

            //reservationData
            reservationData: null,
        }
    },    
    methods: {
 
        showMemberFeedbackModal(reservationData) {               
            this.reservationData = reservationData;
            this.showRatingsForm();        
            this.$refs['memberFeedback'].show();
        },
        showRatingsForm() {
            this.showRatings = true,
            this.showFeedback = false;
            this.showMessage = false;         
        },
        showFeebackForm() {
            this.showRatings = false,
            this.showFeedback = true;
            this.showMessage = false;
        },
        showMessageForm() {
            this.showRatings = false,
            this.showFeedback = false;
            this.showMessage = true;        
        },
        onEditorBlur (editor) {
            console.log(editor)
        },
        onEditorFocus (editor) {
            console.log(editor)
        },           
        isArrayValid(arr) {
            return arr.every(element => element === true || element >= 1 || element != null);
        },
        checkLessonRated() 
        {
            //return this.isArrayValid([this.generalCourseRating, this.studentPerformanceRating,this.teacherSelfPerformanceRating])

            return this.isArrayValid([this.studentPerformanceRating])

        },
        submitFeedback() {           
           let isLessonRated = this.checkLessonRated();     

           if (isLessonRated == true) {
                this.postFeeback();
                
           } else {
                alert ("You have not rated your student, please click the stars to rate")
           }
        },
        postFeeback() {        
            axios.post("/api/postMemberFeedback?api_token=" + this.api_token,
            {
                'method'                        : "POST",
                reservation                     : this.reservationData,              
                feeback                         : this.feeback,
                message                         : this.message, 

                //Ratings
                generalCourseRating             : this.generalCourseRating, 
                studentPerformanceRating         : this.studentPerformanceRating, 
                teacherSelfPerformanceRating    : this.teacherSelfPerformanceRating

            }).then(response => {

                if (response.data.success == true) {
                    this.$refs['memberFeedback'].hide();
                    this.$parent.disableSession();
                } else {
                    this.$refs['memberFeedback'].hide();  
                    this.$parent.disableSession();              
                }

            });        
        
        }

    }    
}
</script>