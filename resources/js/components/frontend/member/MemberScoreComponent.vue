<template>
    <div class="profile bg-lightred pt-0 px-0">
        <div class="col-md-12 bg-red text-white pt-2 pb-2 text-center">
            <strong>テストスコア履歴</strong>        
            <span class="btnAddScoreHolder float-right">
                <span v-b-modal.modalUpdateMemberForm><i class="fas fa-plus"></i></span>
            </span>
        </div>

        <div class="col-md-12  pt-2 pb-2">
            <div id="memberAddExamScoreForm" class="modal-container">

                <b-modal id="modalUpdateMemberForm" title="テストスコア履歴" @show="resetModal" @hide="resetButtons">

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
                                        <option value="TOEIC_Writing">TOEIC Writing</option>
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
                                            :input-class="[ 'form-control form-control-sm col-md-10 bg-white']"
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
                                <ToeicWritingScoreComponent :examScore="examScore" :size="this.size"></ToeicWritingScoreComponent>

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
                            <div v-if="updateType == 'update' || updateType == 'edit'">
                                <b-button variant="primary" size="sm" class="float-right mr" id="updateExamScore" v-on:click="updateExamScore">Update Exam Score</b-button>
                            </div>

                            <div v-else>
                                <b-button variant="primary" size="sm" class="float-right mr" id="addExamScore" v-on:click="addExamScore">Save Exam Score</b-button>
                            </div>
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

                        <div class="latest-score-message"></div>

                        <div class="latest-score">
                            <div class="label">
                                <span class="font-weight-bold small">Exam Date:</span> 
                                <span class="small">{{ dateFormatter(this.latestScore.examDate) }}</span>
                            </div>

                            <div class="label">
                                <span class="font-weight-bold small">Exam Type:</span>  
                                <span class="small">{{ this.latestScore.examType }}</span> 
                            </div>

                            <div v-for="(value, name) in this.latestScore.examScores" :key="name">
                                <span class="font-weight-bold small">{{ capitalizeFirstLetter(name) }}</span>: 
                                <span class="small">{{ value }}</span>
                            </div>
                            
                        </div>
                        
                        <b-modal id="examHistory" ref="examHistoryModal" title="Exam Scores">
                            <input type="hidden" id="memberExamUserID" v-model="memberinfo.user_id">
                            <div id="memberExamScores">
                                <span id="memberExamScoreMessage"></span>
                                <span v-html="this.examScores"></span>
                            </div>
                        </b-modal>


                    </div>
                </div>
                <!--[end]-->

            </div>

            <!-- SCORE MODAL Button-->
            <div class="row mt-2">
                <!-- View Scores -->
                <div class="col-6 float-right px-0 mx-0 d-flex justify-content-end">
                    <span v-b-modal.modalMemberExamScoreList >
                        <b-button size="sm" variant="dark"  pill>
                            <b-icon-calculator></b-icon-calculator> <span class="small"> View Scores </span> 
                        </b-button>                   
                    </span>
                    &nbsp;
                </div>

                <!--Score Graphs Button -->
                <div class="col-6  px-0 mx-0">
                    <span v-b-modal.modalMemberExamScoreGraph>
                        <b-button size="sm" variant="primary" pill>
                            <b-icon-bar-chart-fill></b-icon-bar-chart-fill> <span class="small">Score Graph </span>
                        </b-button>                   
                    </span>
                    &nbsp;
                </div>
            </div>

            <!-- [START] SCORE MODAL -->
            <div id="memberExamScoreList" class="modal-container">                    
                <b-modal id="modalMemberExamScoreList" title="テストスコア履歴" size="xl" @show="getMemberExamScoreByType">  

                    <div id="memberExamModalMessage" class="row">
                        <div class="text-center col-md-12">No Data found</div>                            
                    </div>

                    <div class="row">

                        <div class="col-4" v-for="(examScoreType, examScoreTypeIndex) in examScoreTypes" :key="examScoreTypeIndex">

                                <div class="card esi-card mb-3">
                                    <div class="card-header esi-card-header small">
                                        {{ capitalizeFirstLetter(examScoreType) }}

                                        <div class="float-right" v-if="examScoreList[examScoreType].rows >= 1">
                                            <a href="#" @click.prevent="showUpdateScoreForm(examScoreType)"><b-icon icon="pencil-square" aria-hidden="true"></b-icon></a>
                                            <a href="#" @click.prevent="deleteScore(examScoreType, examScoreList[examScoreType].items.details[0].id)"><b-icon icon=" trash" aria-hidden="true"></b-icon></a>
                                        </div>
                                    </div>

                                    <div v-for="(values, index) in examScoreList[examScoreType]" :key="index">
                                        <div :id="examScoreType" :class="examScoreType" v-if="index == 'rows'">

                                            <div  v-if="examScoreList[examScoreType].rows >= 1">

                                                <table class="table esi-table table-bordered table-striped" >

                                                    <tbody :id="item.id" v-for="(item, itemIndexKey) in examScoreList[examScoreType].items.data" :key="itemIndexKey">

                                                        <tr>
                                                            <td> Exam Date </td>
                                                            <td>
                                                                {{ dateFormatter(examScoreList[examScoreType].items.details[itemIndexKey].exam_date) }}
                                                            </td>
                                                        </tr>

                                                        <tr v-for="(field, fieldKey) in examScoreList[examScoreType].fields" :key="fieldKey" >
                                                            <td class="mb-4" >
                                                                {{ FormatObjectKey(field) }}
                                                            </td>
                                                            <td class="mb-4" >
                                                            <!-- {{ item[field] }} (reactive)-->
                                                                {{ examScoreDisplay[examScoreType +'_display'].items.data[0][field]  }}
                                                            </td>                                                                         
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            
                                                <div class="mt-4">

                                                    <b-pagination
                                                        v-model="examScoreList[examScoreType].currentPage"
                                                        @input="changePage(examScoreType, examScoreList[examScoreType].currentPage)"
                                                        :total-rows="examScoreList[examScoreType].rows"
                                                        :per-page="examScoreList[examScoreType].perPage"
                                                        first-text="<<"
                                                        prev-text="<"
                                                        next-text=">"
                                                        last-text=">>"
                                                        size="sm"
                                                        align="center"                                            
                                                    ></b-pagination>

                                                </div>

                                            </div>
                                            <div v-else class="text-center py-5">
                                                 <span class="small text-info">
                                                    No results found
                                                </span>
                                            </div>

                                        </div>
                                    </div>

                                   

                                </div>

                          
                           

                        </div>                        
                    </div>   

                    <template #modal-footer>
                        <div class="buttons-container w-100">
                            <p class="float-left"></p>
                            <b-button variant="primary" size="sm" class="float-right mr-2" @click="$bvModal.hide('modalMemberExamScoreList')">Close</b-button>                            
                        </div>
                    </template>  


                </b-modal>
            </div>
            <!-- [END] SCORE MODAL -->

            <!-- [START] SCORE MODAL GRAPH -->
            <div id="memberExamScoreGraph" class="modal-container">
                <b-modal id="modalMemberExamScoreGraph" title="テストスコア履歴 グラフ" size="xl" @show="getMemberScoreGraph"> 

                    <div id="memberGraphModalMessage" class="row">
                        <div class="text-center col-md-12">No Data found</div>                            
                    </div>

                    <div class="row">
                        <div class="col-4" v-for="(examScoreType, examScoreTypeIndex) in examScoreTypes" :key="examScoreTypeIndex">
                            <line-chart :chart-data="datacollection[examScoreType]"  v-if="loaded"  :options="extraOptions[examScoreType]"></line-chart>
                        </div>
                    </div>

                    <template #modal-footer>
                        <div class="buttons-container w-100">
                            <p class="float-left"></p>
                            <b-button variant="primary" size="sm" class="float-right mr-2" @click="$bvModal.hide('modalMemberExamScoreGraph')">Close</b-button>                            
                        </div>
                    </template>                         
                    
                </b-modal>
            </div>
            <!-- [END] SCORE MODAL -->
        </div>

    </div>
</template>

<script>
    import LineChart from '../../frontend/chart/lineChartComponent.vue';
    import Vuelidate from "vuelidate";
    Vue.use(Vuelidate);import PurposeComponent from "../../purpose/PurposeComponent.vue";
    //Import Score Types
    import IELTScoreComponent from "../../scores/IELTScoreComponent.vue";
    import ToeflScoreComponent from "../../scores/ToeflScoreComponent.vue";
    import ToeflJuniorScoreComponent from "../../scores/ToeflJuniorScoreComponent.vue";
    import ToeflPrimaryStep1ScoreComponent from "../../scores/ToeflPrimaryStep1ScoreComponent.vue";
    import ToeflPrimaryStep2ScoreComponent from "../../scores/ToeflPrimaryStep2ScoreComponent.vue";
    import ToeicListeningAndReadingScoreComponent from "../../scores/ToeicListeningAndReadingScoreComponent.vue";
    import ToeicSpeakingScoreComponent from "../../scores/ToeicSpeakingScoreComponent.vue";
    import ToeicWritingScoreComponent from "../../scores/ToeicWritingScoreComponent.vue";

    import EikenScoreComponent from "../../scores/EikenScoreComponent.vue";
    import TeapScoreComponent from "../../scores/TeapScoreComponent.vue";
    import * as Moment from 'moment'
    import Datepicker from 'vuejs-datepicker';
    import {en, ja} from 'vuejs-datepicker/dist/locale';
    export default 
    {
        name: "MemberScoreComponent",
        components: {
            LineChart,
            Datepicker, PurposeComponent,
            IELTScoreComponent, 
            ToeflScoreComponent, ToeflJuniorScoreComponent,
            ToeflPrimaryStep1ScoreComponent, ToeflPrimaryStep2ScoreComponent, 
            ToeicListeningAndReadingScoreComponent, ToeicSpeakingScoreComponent, ToeicWritingScoreComponent,
            EikenScoreComponent,
            TeapScoreComponent,
        },
        props: {
            memberinfo: Object,      
            purpose: Array,		
            memberlatestexamscore: Object,
            csrf_token: String,		
            api_token: String,
            disabledCreate: Boolean,
        },
        
        data() 
        {
            return {
            
                updateType: "",

                submitted: false,
                ja: ja,

                //slide: 0,
                //sliding: null,

                extraOptions: [],
                //charts
                loaded: false,
                datacollection: [],

                //this is for examp type column
                size: {
                    leftColumn  : "col-4",
                    rightColumn : "col-8",
                    select      : "col-10",
                },   

                //Exam Score Listings
                examScoreTypes: [],
                examScoreList: [],
                examScoreDisplay: [],
                examScoreLink: [],

                //Exam Date (Form Entry)
                examDate: "",
                uExamDate: "",
                examType: "",
                examLevel: "",

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
                        total: ""
                    },
                    TOEIC_Writing: {
                        writing: "",
                        total: "",
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
                        total: ""               
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

        changePage (examType, page) {
           
            this.getMemberExamScoreByPage(examType, page);
        },

        showUpdateScoreForm(examType)
        {  

            clearTimeout(this.messageTimer);

            this.$bvModal.show('modalUpdateMemberForm'); 
            

            //SET AFTER SHOWING MODAL

            this.updateType = 'update';
            this.examType = examType;           

            //test if EIKEN
            let examtypeCheck = examType.split("_");           

            if (examtypeCheck[0] == "EIKEN") 
            {
                this.examType = examtypeCheck[0];
                let levelExamType = examType.split("Grade_"); 
                
                this.examLevel = levelExamType[1];         
                this.examDate = this.examScoreList[examType].items.details[0].exam_date;

                this.$nextTick(() => 
                {
                    document.getElementById("gradeLevel").setAttribute("disabled", "disabled");
                });


            } else {
                this.examType = examType;
                this.examDate = this.examScoreList[examType].items.details[0].exam_date; 
            }


            this.$nextTick(() => 
            {
                document.getElementById("examType").setAttribute("disabled", "disabled");

                this.hideClass('examScoreHolder');              
                let examTypeSelect = this.examType.replace(/\s+/g, '-');
                if (examTypeSelect.length  > 0 ) 
                {               
                    this.showElementId('examination-score-'+ examTypeSelect); 
                    this.examScore[examType] = this.examScoreList[examType].items.data[0] 
                }

                this.removeHighlightExamElement();    
                this.$forceUpdate();                  
            });

            $('#updateButtonContainer').show();

        },

        deleteScore(examType, id) 
       {
            axios.post("/api/deleteMemberExamScore?api_token=" + this.api_token, 
            {
                method          : "POST",
                id              : id,
                examType        : examType,
                                
            }).then(response => {

                //HIDE LOADER HERE
                $(document).find('.modal-footer').find('div.buttons-container').show();
                $(document).find('.modal-footer').find('div.loading-container').hide();
                                
                if (response.data.success === true) 
                {    
                    if (examType == "EIKEN") 
                    {
                        let currentPage = this.examScoreList[examType + '_Grade_' + this.examLevel].currentPage;

                        if (currentPage > 1) {
                            let previous_page_eiken = parseInt(currentPage) - 1;
                            this.getMemberExamScoreByPage(examType + '_Grade_' + this.examLevel, previous_page_eiken);
                        } else {
                            this.getMemberExamScoreByPage(examType, 1);    
                        }
                        

                        
                    } else {
                        
                        let currentPage = this.examScoreList[examType].currentPage;

                        if (currentPage > 1) {
                            let previous_page = parseInt(currentPage) - 1;
                            this.getMemberExamScoreByPage(examType, previous_page);
                        } else {
                            this.getMemberExamScoreByPage(examType, 1);
                        }
                        
                        
                    }

                    this.getMemberLatestExamScore();

                } else {                 
                
                    
                }

			}).catch(function(error) {

                //HIDE LOADER HERE
                $(document).find('.modal-footer').find('div.buttons-container').show();
                $(document).find('.modal-footer').find('div.loading-container').hide();
                console.log(error);
            }); 
        },


        updateExamScore() 
        {
            //SHOW LOADER HERE
            $(document).find('.modal-footer').find('div.buttons-container').hide();
            $(document).find('.modal-footer').find('div.loading-container').show();  

            let examID = null;

            if (this.examType == "EIKEN") 
            {
                examID =  this.examScoreList[this.examType + '_Grade_' + this.examLevel].items.details[0].id;

            } else {

                examID = this.examScoreList[this.examType].items.details[0].id;
            }


            axios.post("/api/updateMemberExamScore?api_token=" + this.api_token, 
            {
                method          : "POST",
                id              : examID,
                memberID        : this.memberinfo.user_id,
                examDate        : this.uExamDate,
                examType        : this.examType,
                examLevel       : this.examLevel,
                examScore       : this.examScore[this.examType],                       
            }).then(response => {

                //HIDE LOADER HERE
                $(document).find('.modal-footer').find('div.buttons-container').show();
                $(document).find('.modal-footer').find('div.loading-container').hide();
                                
                if (response.data.success === false) 
                {    
                    this.highlightExamElement();
                } else {                 
                
                    if (this.examType == "EIKEN") 
                    {

                        this.getMemberExamScoreByPage(this.examType + '_Grade_' + this.examLevel, this.examScoreList[this.examType + '_Grade_' + this.examLevel].currentPage);

                    } else {
                        this.getMemberExamScoreByPage(this.examType, this.examScoreList[this.examType].currentPage);
                    }

                    this.getMemberLatestExamScore();
                    
                    $(document).find('.modal-footer').hide();

                    $(document).find('#updateMemberForm').slideUp(500, function() {
                        $(document).find('#updateMemberForm').html('<div class="alert alert-success text-center" role="alert">Thank you! your score has been submitted</div>');
                        $(document).find('#updateMemberForm').slideDown(500, function() {
                             $(document).find('#updateMemberForm').show();
                        });
                    });

                    this.messageTimer = setTimeout(function(scope) {
                         scope.$bvModal.hide('modalUpdateMemberForm');
                    }, 3500, this);

                    this.$forceUpdate();
                }
			}).catch(function(error) {

                //HIDE LOADER HERE
                $(document).find('.modal-footer').find('div.buttons-container').show();
                $(document).find('.modal-footer').find('div.loading-container').hide();
                console.log(error);
            }); 
        },        


        hideFormModal(name) {
            this.$bvModal.hide(name);
        },        

        getMemberExamScoreByPage(examType, page)  {

            axios.post("/api/getMemberExamScoreByPage?page="+ page +"&api_token=" + this.api_token,            
            {
                method      : "POST",
                memberID    : this.memberinfo.user_id,
                examType    : examType
            }).then(response => {        


                if (response.data.success === true) 
                {

                    this.examScoreList[examType] = response.data.examScoreList[examType];
                    this.examScoreDisplay[examType + '_display'] = response.data.examScoreDisplay[examType + '_display'];
                    this.$forceUpdate();
                }
                else
                {
                    this.examScoreList[examType] = response.data.examScoreList[examType];
                    this.examScoreDisplay[examType + '_display'] = response.data.examScoreDisplay[examType + '_display'];
                 
                }

            }).catch(function(error) {
                console.log("Error " + error);
            });  
        
        },
        getMemberExamScoreByType() 
        {
            axios.post("/api/getMemberExamScoreByType?api_token=" + this.api_token, 
            {
                method      : "POST",
                memberID    : this.memberinfo.user_id,
                examType    : this.examType,
                limit       : 1,
            }).then(response => {               
                if (response.data.success === true) 
                {

                    $('#memberExamModalMessage').hide();
                    this.examScoreTypes = response.data.examTypes;
                    this.examScoreList = response.data.examScoreList;
                    this.examScoreDisplay = response.data.examScoreDisplay;
                }
                else
                {

                    this.examScoreTypes = [];
                    this.examScoreList = [];
                    this.examScoreDisplay = [];

                    console.log(response.data.message);
                }
            }).catch(function(error) {
                console.log("Error " + error);
            });              
        },
        getMemberScoreGraph() 
        { 
            axios.post("/api/getMemberScoreHistory?api_token=" + this.api_token, 
            {
                method      : "POST",
                memberID    : this.memberinfo.user_id,
            }).then(response => {    
                       
                if (response.data.success === true) 
                {
                    $('#memberGraphModalMessage').hide();

                    this.examScoreTypes = response.data.examTypes;
                    this.examScoreList = response.data.examScoreList;
                    let types = this.examScoreTypes;


                    let max = {'IELTS': 9, 'TOEFL': 120, 'TOEFL_Junior': 900, 
                                'TOEFL_Primary_Step_1':  218, 'TOEFL_Primary_Step_2': 230,
                                'TOEIC_Listening_and_Reading': 990, 'TOEIC_Speaking': 200, 'TOEIC_Writing' : 495,
                                'EIKEN_Grade_5': 850,
                                'EIKEN_Grade_4': 1000,     
                                'EIKEN_Grade_3': 2200,                                
                                'EIKEN_Grade_2': 2600,
                                'EIKEN_Grade_1': 3100,                                
                                'EIKEN_Grade_pre_1': 3000,
                                'EIKEN_Grade_pre_2': 2400,
                            }

                    types.forEach((type) => 
                    {            
                        let totals = response.data.examScoreList[type].totals;

                        this.datacollection[type] = {
                            labels: response.data.examScoreList[type].dates,
                            datasets: [
                                {
                                    label: this.capitalizeFirstLetter(type),
                                    backgroundColor: '#'+ Math.floor(Math.random()*16777215).toString(16), 
                                    data: totals,                   
                                }                                
                            ],                           
                        }
                        
                        if (type == "Other_Test") 
                        {                        
                            this.extraOptions['Other_Test'] = null;
                        } else {
                        
                            this.extraOptions[type] = { 
                                scales: {
                                    yAxes: [
                                    {
                                        ticks: {
                                            min: 0,
                                            max: max[type],
                                            stepSize: 1,
                                            reverse: false,
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            };

                        }
                          
                    });
                    this.loaded = true;
                } else {
                    console.log(response.data.message);
                }
            }).catch(function(error) {
                console.log("Error " + error);
            });
        }, 
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
                examLevel       : this.examLevel,
                examScore       : this.examScore[this.examType],                       
            }).then(response => {

                //HIDE LOADER HERE
                $(document).find('.modal-footer').find('div.buttons-container').show();
                $(document).find('.modal-footer').find('div.loading-container').hide();
                                
                if (response.data.success === false) 
                {    
                    this.highlightExamElement();
                } else {                    

                    this.getMemberLatestExamScore();

                    $(document).find('.modal-footer').hide();

                    $(document).find('#updateMemberForm').slideUp(500, function() {
                        $(document).find('#updateMemberForm').html('<div class="alert alert-success text-center" role="alert">Thank you! your score has been submitted</div>');
                        
                        $(document).find('#updateMemberForm').slideDown(500, function() {
                             $(document).find('#updateMemberForm').show();                             
                        });
                    }); 
                    
                    this.messageTimer = setTimeout(function(scope) {
                         scope.$bvModal.hide('modalUpdateMemberForm');
                    }, 3500, this);
                   

                    this.$forceUpdate();
                }
			}).catch(function(error) {

                //HIDE LOADER HERE
                $(document).find('.modal-footer').find('div.buttons-container').show();
                $(document).find('.modal-footer').find('div.loading-container').hide();
                console.log(error);
            }); 

            event.preventDefault()
        },        
        handleChangeExamType(event) 
        {
            this.examLevel = "";
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
        },
        dateFormatter(date) 
        {
            let fdate = Moment(date).format('YYYY年 MM月 D日');                      
            return fdate;            
        },        
        capitalizeFirstLetter(string) {
            let newString = string.charAt(0).toUpperCase() + string.slice(1);
            newString = newString.replace(/_/g, " ")

            //add space before big letters
            return newString.replace(/([A-Z])/g, ' $1').trim(); 
        },      
        highlightExamElement()  
        {                       
            let examType = document.getElementById('examType').value;
            let examDate = this.examDate;
            let gradeLevel = document.getElementById('gradeLevel').value;

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

            if (examDate == 0) {
                $('#examDate').addClass('border border-danger')
            } else {
                $(document).find('#examDate').removeClass('border border-danger')
            }

            if (examType == "EIKEN") {
            
                if (gradeLevel == 0) {
                    $(document).find('#gradeLevel').addClass('border border-danger')
                } else {
                    $(document).find('#gradeLevel').removeClass('border border-danger')
                }

                let container = $('div#examination-score-'+examType).find('.grade_level_container');

                container.each(function() {
                    if ($(this).css('display') == 'flex') 
                    {
                        let elementID = $(this).find('select').attr('id');

                        let numeric = parseInt($(this).find('select').val())
                        
                        if(!$.isNumeric(numeric)) 
                        {
                            console.log(elementID + "  will be highlighted");
                            $('#'+elementID).addClass('border border-danger')
                        } else {

                            $('#'+elementID).removeClass('border border-danger')
                        }
                    }
                });
            } else {
            
                let selection = $('div#examination-score-'+examType).find('select');


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

            }
        },
        highlightEikenExamElement()
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
                //console.log("Filled Elements " + filled_selection_length)
                //console.log("total :  " + total );
                return parseInt(total);
            } else {
                //console.log("not all filled!")
            }
        },
       
        getMemberLatestExamScore() 
        {        
            axios.post("/api/getMemberLatestScore?api_token=" + this.api_token,
            {
                method       : "POST",
                limit        : 1,
                memberID     : this.memberinfo.user_id,
            }).then(response => {     
                if (response.data.success === true) 
                { 
                    $('.latest-score-message').html("");
                    $('.latest-score').show();

                    this.latestScore.examDate = response.data.examDate;
                    this.latestScore.examType = response.data.examType;                    
                    this.latestScore.examScores = JSON.parse(response.data.examScores);
                } else {
                    $('.latest-score-message').html("<center>No Latest Score</center>");
                    $('.latest-score').hide();
                }
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
            return newString.trim(); 
        },        
        FormatObjectKey(string) {
            let newString = string.charAt(0).toUpperCase() + string.slice(1);
            newString = newString.replace(/_/g, " ")

            //add space before big letters
            return newString.replace(/([A-Z])/g, ' $1').trim(); 
        },

        resetButtons() 
        {
             this.updateType = null;    
             
        },
        resetModal() {
            this.submitted = false;

            clearTimeout(this.messageTimer);
            
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
                    total: "", 
                },
                TOEIC_Writing: {
                    writing: "",
                    total: "",
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

    .memberExamTable td {
        font-size: 11px;
    }

</style>