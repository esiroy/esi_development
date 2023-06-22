<template>


        <b-modal ref="ratingAndFeedBack" header-bg-variant="primary" header-text-variant="white" size="lg" hide-footer no-close-on-backdrop  no-close-on-esc  hide-header-close>
            <template #modal-header>
            <div class="mx-auto">
                <h4 class="text-center text-white">Lesson Satisfaction Survey</h4>
            </div>           
        </template> 

        <div class="info-container">
            <h5 class="text-maroon">Lesson Satisfaction Survey</h5>        
            <h6 class="font-weight-bold"  v-if="showEndPage == false"> 
                必ず満足度（★）を入力してください 
            </h6>
            <h6 class="font-weight-bold"  v-if="showEndPage == true"> 
                (★) 満足度評価ご入力ありがとうございました
            </h6>            
            <hr/>
        </div>

        <div class="ratings-form-container" v-if="showEndPage == false">
     
            <div id="ratingsForm" v-show="showRatings">
                <b-alert show variant="danger" v-show="errorMessage">
                    <div class="d-flex">
                        <div class="flex">
                            <b-icon icon="exclamation-circle-fill" variant="danger" font-scale="2"></b-icon> 
                        </div>
                        <div class="flex pl-2 pt-1 w-100  error-message">
                            {{ errorMessage }}
                        </div>  
                    </div>
                </b-alert>          

                <div id="ratingsContainer" class="row">
                    <div id="starsRating" class="col-12">
                        <div class="row mt-3">
                            <div class="col-5"> 
                                <div class="title">           
                                    Teacher Performance Satisfaction
                                </div>
                                <hr class="m-0">
                                <div class="text-secondary small pt-1"> 
                                Please rate your teacher from 1 Star rating to 5 Star rating for the highest satisfaction
                                </div>                            
                            </div>
                            <div class="col-7"> 

                                <ul class="esi-listings">

                                  <transition-group name="fade">

                                    <li class="hover-list-item" :key="1">

                                        <div class="hover-info">
                                            <div class="small text-success text-left">
                                                <span class="info-text small font-weight-bold">★を左から右に移動すると評価が上がります  (評価は受講歴から変更可能です）</span>
                                            </div>
                                        </div>

                                        <star-rating v-model="teacherPerformanceRating" 
                                        v-bind:show-rating="false"
                                        v-bind:star-size="30" 
                                        v-bind:animate="true" 
                                        v-bind:padding="5"></star-rating>
                                    </li>

                                     </transition-group>
                                </ul>

                            </div>                
                        </div>                   
                    </div>
                </div>

                <!--
                <div id="messageFormContainer" class="row" v-show="showMessage">
                    <div id="messageForm" class="col-12">
                        <div class="row mt-3">
                            <div class="col-5"> 
                                <div class="title">Give us your comments</div>
                                <hr class="m-0">
                                <div class="text-secondary small pt-1">           
                                    Help improve our services and make teachers proud of their effort
                                </div>
                            </div>
                            <div class="col-7"> 
                                <vue-ckeditor v-model="message" :config="config" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" />
                            </div>                
                        </div>                   
                    </div>
                </div>  
                -->

                <div class="mt-4 mb-2 text-center">
                    <button class="btn btn-success" @click="submitSurvey()">
                        Submit
                    </button>
                </div>
            </div>
        </div>


        <div class="thankyou-container" v-if="showEndPage == true">

            <div class="my-4">
                受講いただきありがとうございました。よろしければ
                   
                <div class="d-inline" v-if="this.reservationData.schedule_id">
                    <a :href="getBaseURL('questionnaire/'+this.reservationData.schedule_id)">「アンケート」</a> に答えていただけますと幸いです。（ご希望の方のみ）
                </div> 
                
            </div>

            <div class="mt-4 mb-2 text-center">
                <button class="btn btn-success" @click="exitSurvey()">
                    EXIT
                </button>
            </div>
        </div>



    </b-modal>

</template>

<script>
import StarRating from 'vue-star-rating'
import VueCkeditor from 'vue-ckeditor2';

export default {
    name: "satisfactionSurveyComponent",
    components: { StarRating, VueCkeditor},
    props: {
        csrf_token: String,		
        api_token: String 
    },    
    data() {
        return {

            errorMessage: null,           


            //forms
            showRatings: false,
            showFeedback: false,
            showMessage: false,

            //Stars Container Ratings
            //generalCourseRating: null,
            teacherPerformanceRating: null,
            //studentSelfPerformanceRating: null,

            //Contents
            feeback: null,
            message: null,     

            //showEndPage
            showEndPage: false,

            //Configuration for CKEditor
            config: {
                toolbar: [
                    [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'NumberedList' ],
                    
                ],
                removePlugins: ['easyimage', 'cloudservices', 'exportpdf'],
                height: 150
            },
            //reservationData
            reservationData: null,            
        }
    },    
    methods: {
        getBaseURL(path) {
            return window.location.origin + "/" +path
        },    
        exitSurvey() {            
            window.location.href = this.getBaseURL('home');
        },
        
        showSatisfactionSurveyModal(reservationData) 
        {
            this.reservationData = reservationData;
            this.showRatingsForm();
            this.$refs['ratingAndFeedBack'].show();            
        },
        showThankYou(reservationData) {
            this.reservationData = reservationData;
            this.showEndPage = true;
            this.$refs.ratingAndFeedBack.show();
        },
        showRatingsForm() {
            this.showRatings = true,
            this.showFeedback = false;
            this.showMessage = true;         
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
            //return this.isArrayValid([this.generalCourseRating, this.teacherPerformanceRating, this.studentSelfPerformanceRating])
            return this.isArrayValid([this.teacherPerformanceRating])
        },
        hasMessage() {
            if (this.message == '')
                return false;
            else return true;
        },
        submitSurvey() {      

           let isLessonRated = this.checkLessonRated(); 

           if (isLessonRated == true) {
                this.errorMessage = null;               
                this.postSurvey();
           } else {
                this.errorMessage = "Please rate your teacher using stars";          
           }
        },
        postSurvey() { 

            axios.post("/api/postSatisfactionSurvey?api_token=" + this.api_token,
            {
                method                          : "POST",
                reservation                     : this.reservationData,
                feeback                         : this.feeback,
                //message                         : this.message,
                //Ratings
                teacherPerformanceRating        : this.teacherPerformanceRating, 

                //(Discared below)
                //generalCourseRating             : this.generalCourseRating,                 
                //studentSelfPerformanceRating    : this.studentSelfPerformanceRating

            }).then(response => {
            
                if (response.data.success == true) {


                    this.showThankYou();

                    //this.$refs['ratingAndFeedBack'].hide();
                    //this.$parent.disableSession();

                } else {
                    this.$refs['ratingAndFeedBack'].hide();  
                    this.$parent.disableSession();              
                }

            });
        }
    }    
}
</script>



<style scoped>

    .error-message { 
        vertical-align: text-bottom;
    }

    .title {
        min-height: 40px;
        display: flex;
        align-items: center;
        font-weight: bold;
    }

    h1 { color: #333; }
    ul { list-style-type: none; }
    li { margin-bottom: 10px;}

    .esi-listings {  
        /*height: 30px;      */
        padding: 0;    
    }


    .hover-list-item {
        background-color: rgb(150 222 253 / 10%);
        border-radius: 12px;
        border: 3px solid #cccccc2b;
        padding: 15px 0px 30px 20px;         
    }

    .hover-list-item        .hover-info .info-text{ visibility: hidden; }
    .hover-list-item:hover  .hover-info .info-text{ visibility: visible }

    .fade-enter-active,
    .fade-leave-active {
        background-color: rgba(0, 255, 51, 0.150);
        transition: opacity 0.5s ease-out;
    }

    .fade-enter,
    .fade-leave-to {
        background-color: rgba(0, 255, 51, 0.400);
        opacity: 0;
    }    

</style>