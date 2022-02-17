<template>
    <div class="col-md-12 pb-2">

        <!-- RECENT SCORES -->
        <div class="row">                   
            <div class="col-12">

                <div class="latest-score-message"></div>

                <div class="latest-score">
                    <div class="label">
                        <span class="font-weight-bold small">Exam Date:</span> 
                        <span class="small">{{ this.latestScore.examDate }}</span>
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


        <!-- SCORE MODAL Button-->
        <div class="row mt-2">
            <!-- View Scores -->
            <span v-b-modal.modalMemberExamScoreList class="pr-1">
                <b-button size="sm" variant="dark"  pill>
                    <b-icon-calculator></b-icon-calculator> <span class="small"> View Scores </span> 
                </b-button>                   
            </span>
        
            <!--Score Graphs Button -->
            <span v-b-modal.modalMemberExamScoreGraph>
                <b-button size="sm" variant="primary" pill>
                    <b-icon-bar-chart-fill></b-icon-bar-chart-fill> <span class="small">Score Graph </span>
                </b-button>                   
            </span>
            
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
                                  
                                    </div>

                                    <div v-for="(values, index) in examScoreList[examScoreType]" :key="index">
                                        <div :id="examScoreType" :class="examScoreType" v-if="index == 'rows'">
                                        
                                            <div v-if="examScoreList[examScoreType].rows >= 1">

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
                                                                {{ ucwords(FormatObjectKey(field)) }}
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
            
                submitted: false,
                ja: ja,

                slide: 0,
                sliding: null,
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
                examScoreLink: [],

                //Exam Date (Form Entry)
                examDate: "",
                uExamDate: "",
                examType: "",
                examLevel: "",
                

                examScorePage: {
                    IELTS: {                    
                        perPage : 1,
                        rows: 1,
                        currentPage : 1,
                        items : 1,
                    }, 
                    TOEFL: {                    
                        perPage : 1,
                        rows: 1,
                        currentPage : 1,
                        items : 1,
                    },
                    TOEFL_Junior: {                    
                        perPage : 1,
                        rows: 1,
                        currentPage : 1,
                        items : 1,
                    },
                    TOEFL_Primary_Step_1: {                    
                        perPage : 1,
                        rows: 1,
                        currentPage : 1,
                        items : 1,          
                    },
                    TOEFL_Primary_Step_2: {                    
                        perPage : 1,
                        rows: 1,
                        currentPage : 1,
                        items : 1,             
                    },
                    TOEIC_Listening_and_Reading: {                    
                        perPage : 1,
                        rows: 1,
                        currentPage : 1,
                        items : 1, 
                    },
                    TOEIC_Speaking: {
                        perPage : 1,
                        rows: 1,
                        currentPage : 1,
                        items : 1,
                    },
                    TOEIC_Writing: {
                        perPage : 1,
                        rows: 1,
                        currentPage : 1,
                        items : 1,
                    },                    
                    EIKEN: {
                        perPage : 1,
                        rows: 1,
                        currentPage : 1,
                        items : 1,
                    },
                    TEAP: {                    
                        perPage : 1,
                        rows: 1,
                        currentPage : 1,
                        items : 1,
                    },
                    Other_Test: {
                        perPage : 1,
                        rows: 1,
                        currentPage : 1,
                        items : 1,
                    }
                }, 


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
                if (response.data.success === true) 
                { 
                    $('.latest-score-message').html("");
                    $('.latest-score').show();

                    this.latestScore.examDate = response.data.examDate;
                    this.latestScore.examType = response.data.examType;                    
                    this.latestScore.examScores = JSON.parse(response.data.examScores);
                } else {
                    $('.latest-score-message').html("No Latest Score");
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
        dateFormatter(date) 
        {
            let fdate = Moment(date).format('YYYY年 MM月 D日');                      
            return fdate;            
        },    
        ucwords(string) {
            return string.toLowerCase().replace(/(?<= )[^\s]|^./g, a=>a.toUpperCase())  
        },             
        capitalizeFirstLetter(string) {
            let newString = string.charAt(0).toUpperCase() + string.slice(1);
            newString = newString.replace(/_/g, " ")

            //add space before big letters
            return newString.replace(/([A-Z])/g, ' $1').trim(); 
        },         
        FormatObjectKey(string) {
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
                     total: "", 
                },
                TOEIC_Speaking: {
                    writing: "",
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