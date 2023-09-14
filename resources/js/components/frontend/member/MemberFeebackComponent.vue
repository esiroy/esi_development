<template>


    <b-modal ref="memberFeedbackModal" header-bg-variant="primary" header-text-variant="white" 
    size="lg" no-close-on-backdrop hide-header hide-footer no-close-on-esc  hide-header-close>

       
        <template #modal-header>
            <div class="mx-auto text-center text-white">
                Student Feedback
            </div>           
        </template>
       

        <div id="ratingsForm" v-show="showRatings">

           
            <div class="info-container">
                <h5 class="text-maroon">Student Feedback</h5>
                <h6 class="font-weight-bold">Your student feedbacks and suggestions will assist us in continuosly improving our services</h6>
                <hr/>
            </div>
         

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

             <div id="lessonMaterialInfoContainer" class="row">
                <div id="lessonMaterialInfo" class="col-12">
                    <div class="row">

                        <div class="col-5"> 
                            <div class="title">           
                                Lesson Details
                            </div>
                            <hr class="m-0">
                            <div class="text-secondary small pt-1"> 
                                The detailed information of the lesson 
                            </div>
                        </div>

                        <div class="col-7">
                            <div class="card block">
                             
                                <div class="card-body" v-if="material != null">
                                    <div>
                                        <span class="text-dark font-weight-bold w-25">Course: </span>
                                        <span class="text-secondary">{{ material.course }}</span>
                                    </div>                                  
                                    <div>
                                        <span class="text-dark font-weight-bold  w-25">Material: </span>
                                        <span class="text-secondary">{{ material.material }}</span>
                                    </div>
                                    <div>
                                        <span class="text-dark font-weight-bold  w-25">Subject: </span> 
                                        <span class="text-secondary">{{ material.subject }}</span>
                                    </div>                                      
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             </div>

            <div id="ratingsContainer" class="row">
                <div id="starsRating" class="col-12  mt-3">
                    <div class="row">
                        <div class="col-5"> 
                            <div class="title">           
                                Student Performance
                            </div>
                            <hr class="m-0">
                            <div class="text-secondary small pt-1"> 
                                <div class="font-weight-bold">Please rate your student from 1 to 5 Star rating </div>
                                1 for the lowest satisfaction rating <br/>
                                5 for the highest satisfaction rating
                            </div>
                        </div>
                        <div class="col-7"> 
                            <div class="mt-3">
                                <star-rating v-model="studentPerformanceRating" 
                                v-bind:show-rating="false" v-bind:star-size="30" 
                                v-bind:animate="true" v-bind:padding="5"></star-rating>  
                            </div>
                        </div>                
                    </div>
                </div>
            </div>

            <div id="lessonStatusContainer" class="row">
                <div id="lessonStatus" class="col-12">
                    <div class="row mt-3">
                        <div class="col-5"> 
                            <div class="title">           
                                Lesson Status
                            </div>
                            <hr class="m-0">
                            <div class="text-secondary small pt-1"> 
                                Choose complete if you totally finished the lesson
                            </div>                            
                        </div>
                        <div class="col-7"> 
                            <b-form-select v-model="lessonStatusSelected" :options="options"></b-form-select>                           
                        </div>                
                    </div>

                    <div class="row mt-3" v-if="this.lessonStatusSelected != null && this.lessonStatusSelected == 'INCOMPLETE'">                      
                        <div class="col-5"> 
                            <div class="title">           
                                Select Member's Next Lesson
                            </div>
                            <hr class="m-0">
                            <div class="text-secondary small pt-1"> 
                                Notify next tutor for the next lesson slide to start on
                            </div>                                
                        </div>
                        <div class="col-7"> 
                            <b-form-select v-model="lessonSelected" :options="nextLessonOptions"></b-form-select>                           
                        </div>                        
                    </div>
                </div>              
            </div>

            <div id="feedbackContainer" class="row" v-show="showFeedback">
                <div id="feedbackForm" class="col-12">
                    <div class="row mt-3">
                        <div class="col-5">                            
                            <div class="title">Student Feeback</div>          
                            <hr class="m-0">
                            <div class="text-secondary small pt-1">     
                                Student feedback will help us understand our student's demand and 
                                make the course better and improve our services
                            </div>                            
                        </div>
                        <div class="col-7">                        
                            <vue-ckeditor v-model="feedback" :config="config" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-1 mt-3" role="tablist">
                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button block v-b-toggle.teachers_notes variant="info">Add Teacher's note</b-button>
                    </b-card-header>
                    <b-collapse id="teachers_notes" accordion="my-accordion-1" role="tabpanel">
                        <b-card-body>
                            <div id="notesContainer" class="row">
                                <div id="notesForm" class="col-12">
                                    <div class="row mt-3">
                                        <div class="col-5">                            
                                            <div class="title">Teacher Notes</div>          
                                            <hr class="m-0">
                                            <div class="text-secondary small pt-1">     
                                                Add a note for the next teacher 
                                            </div>                            
                                        </div>
                                        <div class="col-7">                        
                                            <vue-ckeditor v-model="notes" :config="config" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </b-card-body>
                    </b-collapse>
                </b-card>
            </div>



            <div class="accordion-2" role="tablist">
                <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle.homework variant="info">Add Homework</b-button>
                </b-card-header>
                <b-collapse id="homework" accordion="my-accordion-2" role="tabpanel">
                    <b-card-body>
                        <div id="homeWorkContainer" class="row">
                            <div id="homeWorkForm" class="col-12">
                                <div class="row mt-3">
                                    <div class="col-5">                            
                                        <div class="title">Student Homework</div>          
                                        <hr class="m-0">
                                        <div class="text-secondary small pt-1">     
                                            Attach a homework for your student
                                        </div>                            
                                    </div>
                                    <div class="col-7">                                               
                                        <HomeWorkUploader 
                                            ref="homeWorkUploader" 
                                            :user_info="this.$props.user_info"
                                            :reservation="this.$props.reservation"
                                            :api_token="this.api_token" 
                                            :csrf_token="this.csrf_token"
                                            @post-feedback="postFeedback"
                                        >
                                        </HomeWorkUploader>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-card-body>
                </b-collapse>
                </b-card>
            </div>
            
            <div class="mt-4 mb-2 text-center">
                <button class="btn btn-success" @click="submitFeedback()">

                    <b-icon icon="exclamation-circle-fill" variant="danger" v-show="errorMessage">
                    </b-icon> 

                    Submit Feedback
                </button>
            </div>            
        </div>

    </b-modal>

</template>

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


</style>
<script>
import StarRating from 'vue-star-rating'
import VueCkeditor from 'vue-ckeditor2';
import HomeWorkUploader from './HomeWorkUploaderComponent.vue'

export default {
    name: "MemberFeebackComponent",
    components: { StarRating, VueCkeditor, HomeWorkUploader},
    props: {
        user_info: {
            type: [Object, String],
            required: true
        },
        reservation: Object,       
        csrf_token: String,		
        api_token: String 
    },        
    data() {
        return {
            consecutiveSchedules: [],

            errorMessage: null,

            //Selected LESSON
            files: [],

            lessonStatusSelected: null,
            options: [
                { value: null, text: 'SELECT AN OPTION' },
                { value: 'COMPLETED', text: 'COMPLETED' },
                { value: 'INCOMPLETE', text: 'INCOMPLETE' }                
            ],


            lessonSelected: null,
            nextLessonOptions: [
                { value: null, text: 'SELECT AN OPTION' },
            ],


            //forms
            showRatings: false,
            showFeedback: true,
            showMessage: false,

            //Stars Container Ratings
            //generalCourseRating: null,
            studentPerformanceRating: null,
            //teacherSelfPerformanceRating: null,

            //Contents
            feedback: null,
            message: null,     

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
            notes: "",
            homework: null,

            //material
            material: null,
        }
    },    
    methods: {
        test() {

            alert ("testing member feedback");
        },
        updateConsecutiveSchedules(schedules) {
            this.consecutiveSchedules = schedules;
        },
        updateLessonDetails(newSegments) {

            let segments = newSegments;

            let course = segments[0];
            let subject = segments[segments.length - 1];
            let lessonMaterial = segments[segments.length - 2];

            this.material = {
                'segments': segments,
                'course': course,
                'material': lessonMaterial,
                'subject': subject
            }

            console.log(this.material);
           
        },
  
        getLessonSlideDetails() {
            alert ("get lesson slide detials")
        
        },
        hideMemberFeedbackModal() {
            this.$refs['memberFeedbackModal'].hide();
        },
        showMemberFeedbackModal(reservationData, files) 
        {

            console.log(this.material);


            if (files == null || files.length == 0) {            
                this.nextLessonOptions.push({
                    value: 1,
                    text: "LESSSON SLIDE " + 1
                });
            } else {
                Object.keys(files).forEach((item, index ) => {                
                    let lessonIndex = index + 1;
                    this.nextLessonOptions.push({
                        value: lessonIndex,
                        text: "LESSSON SLIDE " + lessonIndex
                    })            
                });
            }
            
            this.reservationData = reservationData;
            this.showRatingsForm();        
            this.$refs['memberFeedbackModal'].show();
        },
        showRatingsForm() {
            this.showRatings = true,
            this.showFeedback = true;
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
            return this.isArrayValid([this.studentPerformanceRating]);
        },
        hasLessonStatus() {
            if (this.lessonStatusSelected == 'INCOMPLETE') {            
                return this.isArrayValid([this.lessonStatusSelected, this.lessonSelected]);
            } else {            
                return this.isArrayValid([this.lessonStatusSelected])
            }        
        },
        hasFeedback() {
            if (this.feedback == '')
                return false;
            else return true;
        },        
        submitFeedback() {     

            let homeworkCount   = this.$refs.homeWorkUploader.getFileCount()
            let isLessonRated   = this.checkLessonRated();     
            let hasFeedback     = this.hasFeedback();
            let hasLessonStatus = this.hasLessonStatus();

            if (isLessonRated == true && hasFeedback == true && hasLessonStatus == true) {
                this.errorMessage = null;     
                if (homeworkCount >= 1) {
                     this.$refs.homeWorkUploader.startUpload();
                } else {
                    this.postFeedback();
                }               
            } else if (isLessonRated == false && hasFeedback == false && hasLessonStatus == false) {
                this.errorMessage = "Please rate your student and add a student feedback";
            } else if (isLessonRated == false) {
                this.errorMessage = "Please rate your student using stars";
            } else if (hasLessonStatus == false) {
                 if (this.lessonStatusSelected == 'INCOMPLETE') {  
                    this.errorMessage = "Please select lesson status and its next lesson";
                 } else {
                    this.errorMessage = "Please select lesson status";
                 }
            } else if (hasFeedback == false) {
                this.errorMessage = "Please add a student feedback";
            } else {
                 this.errorMessage = "Oops! You have not rated your student and added a feedback";
            } 
        },
        postFeedback() { 
        
            axios.post("/api/postMemberFeedback?api_token=" + this.api_token,
            {
                'method'                    : "POST",               
                'reservation'               : this.reservationData,         
                'consecutiveSchedules'      : this.consecutiveSchedules,
                'material'                  : JSON.stringify(this.material),
                'feedback'                  : this.feedback,
                //'message'                   : this.message, ()
                'lessonStatus'              : this.lessonStatusSelected,
                'nextLessonSelected'        : this.lessonSelected,
                'studentPerformanceRating'  : this.studentPerformanceRating, 
                'notes'                     : this.notes,                 
                //generalCourseRating             : this.generalCourseRating,                 
                //teacherSelfPerformanceRating    : this.teacherSelfPerformanceRating
            }).then(response => {

                if (response.data.success == true) {
                    this.$refs['memberFeedbackModal'].hide();

                    this.$nextTick(() => {
                       this.redirect('admin');                       
                    });                    
                } else {
                    alert ("Error:", response.data.message);
                }
            });

        },
        redirect(url) {
            window.location.href = window.location.origin + "/" + url
        },        

    }    
}
</script>