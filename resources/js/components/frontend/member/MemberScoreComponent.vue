<template>    

    <div class="profile bg-lightred pt-0 px-0">
        <div class="col-md-12 bg-red text-white pt-2 pb-2 text-center">
            <strong>テストスコア履歴</strong>        
            <span class="btnAddScoreHolder float-right">
                <span v-b-modal.modalUpdateMemberForm><i class="fas fa-plus"></i></span>
            </span>
        </div>

        <div class="col-md-12  pt-2 pb-2">
            <div class="mt-3 mb-4">

                <div id="memberAddExamScoreForm">

                    <b-modal id="modalUpdateMemberForm" title="テストスコア履歴" @show="resetModal">

                        <form id="updateMemberForm" name="updateMemberForm" @submit.prevent="handleUpdateMemberSubmit">   


                            <!--[start] Exam (New)-->
                            <div id="examination-section" class="section">

                                <div class="row pt-2">
                                    <div class="col-4">                       
                                        <div class="pl-2 small"> <span class="text-danger">*</span> Type of Examination </div>
                                    </div>                   
                                    <div class="col-8">
                                        <select id="examType" name="examType" v-model="examType" @change="handleChangeExamType($event)" class="form-control form-control-sm pl-0  col-md-10">
                                            <option value="" class="mx-0 px-0">Select Examination Type</option>
                                            <option value="IELTS" class="mx-0 px-0">IELTS</option>
                                            <option value="TOEFL">TOEFL iBT</option>
                                            <option value="TOEFL_Junior">TOEFL Junior</option>
                                            <option value="TOEFL_Primary_Step_1">TOEFL Primary Step 1</option>
                                            <option value="TOEFL_Primary_Step_2">TOEFL Primary Step 2</option>
                                            <option value="TOEIC_Listening_and_Reading">TOEIC Listening and Reading</option>
                                            <option value="TOEIC_Speaking">TOEIC Speaking</option>
                                            <option value="EIKEN">EIKEN(英検）</option>
                                            <option value="TEAP">TEAP</option>
                                            <option value="Other_Test">Other Test</option>
                                        </select>       
                                    </div>                     
                                </div>

                                <div class="row pt-2">
                                    <div class="col-4">                       
                                        <div class="pl-2 small"> <span class="text-danger">*</span> Examination Date </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="mb-2 ">
                                            <datepicker id="examDate" 
                                                name="examDate"                                          
                                                v-model="examDate"
                                                :value="examDate"
                                                :format="examDateFormatter"
                                                :placeholder="'Select Date'"
                                                :input-class="[ 'form-control form-control-sm col-md-10 ']"
                                                :language="ja"
                                            ></datepicker>  

                                        </div>
                                    </div>
                                </div>    

                            </div>           

                            <div id="examScoreContainer" class="row">
                                <div class="col-12">  
                                    <!--[start] Dynamic Examination Scores -->
                                    <IELTScoreComponent :examScore="examScore" :size="this.size"></IELTScoreComponent>
                                    <ToeflScoreComponent :examScore="examScore" :size="this.size"></ToeflScoreComponent>
                                    <ToeflJuniorScoreComponent :examScore="examScore" :size="this.size"></ToeflJuniorScoreComponent>
                                    <ToeflPrimaryStep1ScoreComponent :examScore="examScore" :size="this.size"></ToeflPrimaryStep1ScoreComponent>
                                    <ToeflPrimaryStep2ScoreComponent :examScore="examScore" :size="this.size"></ToeflPrimaryStep2ScoreComponent>
                                    <ToeicListeningAndReadingScoreComponent :examScore="examScore" :size="this.size"></ToeicListeningAndReadingScoreComponent>
                                    <ToeicSpeakingScoreComponent :examScore="examScore" :size="this.size"></ToeicSpeakingScoreComponent>
                                    <EikenScoreComponent :examScore="examScore" :size="this.size"></EikenScoreComponent>
                                    <TeapScoreComponent :examScore="examScore" :size="this.size"></TeapScoreComponent>                                    
                                    <!--[end] Dynamic Examination Scores -->

                                    <!--[start] Other-->
                                    <div id="ScoresComponent" class="ScoresComponent">
                                        <!--[start] TEAP- -->
                                        <div id="examination-score-Other_Test" class="section examScoreHolder">
                                            <div class="row pt-2">
                                                <div class="col-4">                       
                                                    <div class="pl-2 small  mb-2"> <span class="text-danger">*</span> Score </div>             
                                                </div>
                                                <div class="col-8">            
                                                    <input id="otherScore" name="otherScore" v-model="examScore.Other_Test.otherScore" class="form-control form-control-sm col-md-3">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--[end]-->
                                </div>
                            </div>
                        </form>

                        <template #modal-footer>
                            <div class="buttons-container w-100">
                                <p class="float-left"></p>
                                <b-button variant="primary" size="sm" class="float-right mr" id="addExamScore" v-on:click="addExamScore" @click="show=false">Save Exam Score</b-button>
                                <b-button variant="danger" size="sm" class="float-right mr-2" @click="$bvModal.hide('modalUpdateMemberForm')">Cancel</b-button>                            
                            </div>

                            <div class="loading-container">
                                <b-button variant="primary" size="sm" class="float-right mr">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Loading...
                                </b-button>
                            </div>

                        </template>
                                            
                    </b-modal>


                    <!-- RECENT SCORES -->
                    <div class="row">                   
                        <div class="col-12">
                            <div class="latest-score mb-3">
                                <span class="font-weight-bold small">Exam Date:</span> <span class="small">{{ this.latestScore.examDate }}</span> <br/>
                                <span class="font-weight-bold small">Exam Type:</span>  <span class="small">{{ this.latestScore.examType }}</span> <br/>
                                <div v-for="(value, name) in this.latestScore.examScores" :key="name">
                                    <span class="font-weight-bold small">{{ capitalizeFirstLetter(name) }}</span>: 
                                    <span class="small">{{ value }}</span>
                                </div>
                                <br/> 
                            </div>

                            <a id="viewAllExamScores" href="getAllScores"  data-toggle="modal" data-target="#showAllMemberExamScoreModal">All Scores</a>

                            <b-modal id="examHistory" ref="examHistoryModal" title="Exam Scores">
                                <input type="hidden" id="memberExamUserID" v-model="memberinfo.user_id">
                                <div id="memberExamScores">
                                    <span v-html="this.examScores"></span>
                                </div>
                            </b-modal>
                        </div>
                    </div>
                    <!--[end]-->

                </div>
            </div>
        </div>
    </div>



</template>

<script>
import Vuelidate from "vuelidate";
Vue.use(Vuelidate);

import PurposeComponent from "../../purpose/PurposeComponent.vue";
//Import Score Types
import IELTScoreComponent from "../../scores/IELTScoreComponent.vue";
import ToeflScoreComponent from "../../scores/ToeflScoreComponent.vue";
import ToeflJuniorScoreComponent from "../../scores/ToeflJuniorScoreComponent.vue";
import ToeflPrimaryStep1ScoreComponent from "../../scores/ToeflPrimaryStep1ScoreComponent.vue";
import ToeflPrimaryStep2ScoreComponent from "../../scores/ToeflPrimaryStep2ScoreComponent.vue";
import ToeicListeningAndReadingScoreComponent from "../../scores/ToeicListeningAndReadingScoreComponent.vue";
import ToeicSpeakingScoreComponent from "../../scores/ToeicSpeakingScoreComponent.vue";
import EikenScoreComponent from "../../scores/EikenScoreComponent.vue";
import TeapScoreComponent from "../../scores/TeapScoreComponent.vue";


import * as Moment from 'moment'
import Datepicker from 'vuejs-datepicker';
import {en, ja} from 'vuejs-datepicker/dist/locale';


export default {
    name: "MemberScoreComponent",
    components: {
        Datepicker, PurposeComponent,
        IELTScoreComponent, 
        ToeflScoreComponent, ToeflJuniorScoreComponent,
        ToeflPrimaryStep1ScoreComponent, ToeflPrimaryStep2ScoreComponent, 
        ToeicListeningAndReadingScoreComponent, ToeicSpeakingScoreComponent, 
        EikenScoreComponent,
        TeapScoreComponent,
    },
    props: {
        memberinfo: Object,      
		purpose: Array,		
        memberlatestexamscore: Object,
		csrf_token: String,		
		api_token: String		
    },
    data() {
        return {
            submitted: false,
            ja: ja,         

            size: {
                leftColumn  : "col-4",
                rightColumn : "col-8",
                select      : "col-10",
            },   

            //Exam Date (Form Entry)
            examDate: "",
            uExamDate: "",
            examType: "",
            examScore: {
                IELTS: {                    
                    speakingBandScore : "",
                    writingBandScore : "",
                    readingBandScore : "",
                    listeningBandScore : "",
                    overallBandScore : "",
                }, 
                TOEFL: {                    
                    speakingScore: "",
                    writingScore: "",
                    readingScore: "",
                    listeningScore: "",
                    total: "",
                },
                TOEFL_Junior: {                    
                    listening: "",
                    languageFormAndMeaning: "",
                    reading: "",
                    total: "",
                },
                TOEFL_Primary_Step_1: {                    
                    reading: "",                    
                    listening: "",
                    total: "",               
                },
                TOEFL_Primary_Step_2: {                    
                    reading: "",                    
                    listening: "",
                    total: "",               
                },
                TOEIC_Listening_and_Reading: {                    
                    reading: "",                    
                    listening: "",
                    total: "",     
                },
                TOEIC_Speaking: {
                    speaking: "",
                },
                EIKEN: {
                    grade_5: "",
                    grade_4: "",
                    grade_3_1st_stage: "",
                    grade_pre_2_1st_stage: "",
                    grade_2_1st_stage: "",
                    grade_pre_1_1st_stage: "",
                    grade_1_1st_stage: "",

                    grade_3_2nd_stage: "",
                    grade_pre_2_2nd_stage: "",
                    grade_2_2nd_stage: "",
                    grade_pre_1_2nd_stage: "",
                    grade_1_2nd_stage: "",                    
                },
                TEAP: {                    
                    speakingScore: "",
                    writingScore: "",
                    readingScore: "",
                    listeningScore: "",
                    total: "",
                },
                Other_Test: {
                    otherScore: "",
                }
            }, 

            //Latest Score (show recently added scores)
            latestScore: { 
                examDate: "",
                examType: "",
                examScores: "",                
            },

            //list exam scores (paginated)
            examScores: []
        };
    },      
    mounted: function () 
	{
        this.getMemberLatestExamScore();	
    },
    methods: {       
        addExamScore(event) 
        {
            this.submitted = true;

            //SHOW LOADER HERE
            $(document).find('.modal-footer').find('div.buttons-container').hide();
            $(document).find('.modal-footer').find('div.loading-container').show();

            axios.post("/api/addMemberExamScore?api_token=" + this.api_token, 
            {
                method          : "POST",
                memberID        : this.memberinfo.user_id,
                examDate        : this.uExamDate,
                examType        : this.examType,
                examScore       : this.examScore,                       
            }).then(response => {

                //HIDE LOADER HERE
                $(document).find('.modal-footer').find('div.buttons-container').show();
                $(document).find('.modal-footer').find('div.loading-container').hide();
                                
                if (response.data.success === false) 
                {    
                    this.highlightExamElement();
                } else {                    

                    this.latestScore.examDate = response.data.examDate;
                    this.latestScore.examType = response.data.examType;                    
                    this.latestScore.examScores = JSON.parse(response.data.examScores);

                    $(document).find('.modal-footer').hide();

                    $(document).find('#updateMemberForm').slideUp(500, function() {
                        $(document).find('#updateMemberForm').html('<div class="alert alert-success text-center" role="alert">Thank you! your score has been submitted</div>');
                        $(document).find('#updateMemberForm').slideDown(500, function() {
                             $(document).find('#updateMemberForm').show();
                        });
                    });             

                    setTimeout(function(scope) {
                         scope.$bvModal.hide('modalUpdateMemberForm', 500);
                    }, 3500, this);

                    this.$forceUpdate();
                }
			}).catch(function(error) {

                //HIDE LOADER HERE
                $(document).find('.modal-footer').find('div.buttons-container').show();
                $(document).find('.modal-footer').find('div.loading-container').hide();

                // handle error
                alert("Error " + error);
                //console.log(error);
            }); 

            event.preventDefault()
        },        
        handleChangeExamType(event) 
        {
            this.submitted = false;
            let examTypeValue = event.target.value;                    
            let examType = examTypeValue.replace(/\s+/g, '-');
            this.hideClass('examScoreHolder');
            if (examType.length  > 0 ) {
                this.showElementId('examination-score-'+ examType);
            }
            this.removeHighlightExamElement();
        },
        showElementId(id) {
            document.getElementById(id).style.display = "block";
        },
        hideElementId(id) {
            document.getElementById(id).style.display = "none";
        },
        showClass(className) {
            var elements = document.getElementsByClassName(className)
            for (var i = 0; i < elements.length; i++){
                elements[i].style.display = "none";
            }           
        },
        hideClass(className) {
            var elements = document.getElementsByClassName(className)
            for (var i = 0; i < elements.length; i++){
                elements[i].style.display = "none";
            }        
        },
        handleUpdateMemberSubmit() 
        {
            this.submitted = true;
            alert ("submit test")
        },
        dateFormatter(date) 
        {
            let fdate = Moment(date).format('YYYY年 MM月 D日');                      
            return fdate;            
        },        
        highlightExamElement()  
        {                       
            let examType = document.getElementById('examType').value;
            let examDate = this.examDate;
            let selection = $('div#examination-score-'+examType).find('select');

            if (examType.length == 0 ) {
                 $('#examType').addClass('border border-danger')
            } else {               
                $(document).find('#examType').removeClass('border border-danger')
            }

            if (examDate == 0) {
                 $('#examDate').addClass('border border-danger')
            } else {
                 $(document).find('#examDate').removeClass('border border-danger')
            }

            selection.each(function() {
                let elementID = $(this).attr('id');
                let numeric = parseInt($(this).val())
                if(!$.isNumeric(numeric)) 
                {
                    console.log(elementID + "  will be highlighted");
                    $('#'+elementID).addClass('border border-danger')
                } else {
                    $('#'+elementID).removeClass('border border-danger')
                }
            });        
        },
        removeHighlightExamElement() 
        {        
            let examType = document.getElementById('examType').value;
            let selection = $('div#examination-score-'+examType).find('select');
            let examDate = document.getElementById('examDate').value;
        
            if (examType.length == 0) {
                 $('#examType').addClass('border border-danger')
            } else {
               
                  $(document).find('#examType').removeClass('border border-danger')
            }

            if (examDate.length == 0) {
                 $(document).find('#examDate').removeClass('border border-danger')
            } else {                 
                 //$('#examDate').addClass('border border-danger')
            }


            selection.each(function() 
            {
                let elementID = $(this).attr('id');
                let numeric = parseInt($(this).val())
                if(!$.isNumeric(numeric)) 
                {
                    $('#'+elementID).removeClass('border border-danger')
                }
            });
        },
        getTotalScore(ExamType) 
        {
            let selection = $('div#examination-score-'+ExamType).find('select');
            console.log(selection.length);

            let total = 0;
            let filled_selection_length = 0;

            selection.each(function() 
            {
                let elementID = $(this).attr('id');
                let numeric = parseInt($(this).val())
                if($.isNumeric(numeric)) 
                {
                    filled_selection_length++

                    if (elementID.includes("total")) {
                        //this will not be added to total score, since this is a total score element
                    } else {
                        total = parseInt(total) + parseInt($(this).val());                    
                        console.log($(this).attr('id') + " " + parseInt($(this).val() ));
                    }
                } else {
                    console.log("empty");
                }

            });
            //console.log (filled_selection_length + " ? length ? " + selection.length);

            //if (filled_selection_length == (selection.length - 1) ||   filled_selection_length == selection.length  ) 
            if (filled_selection_length == selection.length  ) 
            {
                console.log("Filled Elements " + filled_selection_length)
                console.log("total :  " + total );
                return parseInt(total);
            } else {
                console.log("not all filled!")
            }
        },

        showExamHistoryModal() 
        {            
            this.$refs['examHistoryModal'].show(); 

            axios.post("/api/getAllMemberExamScore?page=1&api_token=" + this.api_token, 
            {
                method       : "POST",
                limit        : 1,
                memberID     : this.memberinfo.user_id,
            })
            .then(response => 
            {              
                if (response.data.success === false) {
                    alert (response.data.message);                    
                } else {

                    this.examScores = response.data.scores;
                     
                }
			}).catch(function(error) {               
                alert("Error " + error);                
            });

            this.$forceUpdate();
        },
       
        getMemberLatestExamScore() 
        {        
            axios.post("/api/getMemberLatestScore?api_token=" + this.api_token,
            {
                method       : "POST",
                limit        : 1,
                memberID     : this.memberinfo.user_id,
            }).then(response => {     
                if (response.data.success === true) { 
                    this.latestScore.examDate = response.data.examDate;
                    this.latestScore.examType = response.data.examType;                    
                    this.latestScore.examScores = JSON.parse(response.data.examScores);
                } else {
                    alert (response.data.message);        
                }
			}).catch(function(error) {               
                alert("Error " + error);                
            });
        },
        examDateFormatter(date) 
        {

            let fdate           = this.dateFormatter(date);
            this.uExamDate      = date;
            this.$forceUpdate();       

            if (this.submitted === true) {
                this.highlightExamElement();
            }          

            return fdate;
        },  
        capitalizeFirstLetter(string) {
            let newString = string.charAt(0).toUpperCase() + string.slice(1);
            newString = newString.replace(/_/g, " ")

            //add space before big letters
            return newString.replace(/([A-Z])/g, ' $1').trim(); 
        },
        resetModal() {
            this.submitted = false;
            this.resetScoreData();            
        },
        resetScoreData() {

            this.examDate = "";
            this.uExamDate = "";
            this.examType = "";

            this.examScore = {
                IELTS: {                 
                    speakingBandScore : "",
                    writingBandScore : "",
                    readingBandScore : "",
                    listeningBandScore : "",
                    overallBandScore : "",            
                }, 
                TOEFL: {                   
                    speakingScore: "",
                    writingScore: "",
                    readingScore: "",
                    listeningScore: "",
                    total: "",
                },
                TOEFL_Junior: {                    
                    listening: "",
                    languageFormAndMeaning: "",
                    reading: "",
                    total: "",
                },
                TOEFL_Primary_Step_1: {                    
                    reading: "",                    
                    listening: "",     
                    total: "",               
                },
                TOEFL_Primary_Step_2: {                    
                    reading: "",                    
                    listening: "",     
                    total: "",                                   
                },
                TOEIC_Listening_and_Reading: {                    
                    reading: "",                    
                    listening: "",            
                    total: "",                         
                },
                TOEIC_Speaking: {
                    speaking: "",
                },
                EIKEN: {
                    grade_5: "",
                    grade_4: "",
                    grade_3_1st_stage: "",
                    grade_pre_2_1st_stage: "",
                    grade_2_1st_stage: "",
                    grade_pre_1_1st_stage: "",
                    grade_1_1st_stage: "",

                    grade_3_2nd_stage: "",
                    grade_pre_2_2nd_stage: "",
                    grade_2_2nd_stage: "",
                    grade_pre_1_2nd_stage: "",
                    grade_1_2nd_stage: "",  
                    total: "",                  
                },
                TEAP: {
                    
                    speakingScore: "",
                    writingScore: "",
                    readingScore: "",
                    listeningScore: "",    
                    total: "",            
                },
                Other_Test: {
                    otherScore: "",
                }
            }         
        }                 
    }  
};

</script>


<style type="text/css" >

    .sub_options, .examScoreHolder, .loading-container {
        display: none;
    }   

</style>