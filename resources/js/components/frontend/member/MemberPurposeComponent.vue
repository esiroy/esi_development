<template>    
    <div id="purposeList">  
    
        <div class="bg-lightBrown pt-0 px-0">
            <div class="col-md-12 bg-brown text-white pt-2 pb-2 text-center">
                <strong>受講目的</strong> 
                <span class="btnUpdatePurpose float-right">
                    <span v-b-modal.modalUpdatePurposeForm>
                        <i class="fas fa-plus"></i>
                    </span>
                </span>
            </div>

            <div class="col-md-12  pt-2 pb-2 ">
                <div id="memberPurposeList" class="text-left small" style="display:none">                  
                                   
                </div>            
            </div>
        </div>

        <b-modal id="modalUpdatePurposeForm" title="受講目的--->最大3目的" size="xl">
            <PurposeComponent :purposeList="this.purposeList"></PurposeComponent>

            <template #modal-footer>

                <div class="buttons-container w-100">
                    <p class="float-left"></p>
                    <b-button variant="primary" size="sm" class="float-right mr" id="savePurpose" v-on:click="savePurpose" @click="show=false">Save</b-button>
                    <b-button variant="danger" size="sm" class="float-right mr-2" @click="$bvModal.hide('modalUpdatePurposeForm')">Cancel</b-button>                            
                </div>

                <div class="loading-container">
                    <b-button variant="primary" size="sm" class="float-right mr">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </b-button>
                </div>

            </template> 

        </b-modal>


    </div>
</template>

<script>
import PurposeComponent from "../../purpose/PurposeComponent.vue";

export default {
    name: "MemberPurposeComponent",
    components: { PurposeComponent },
    props: { memberinfo: Object, userinfo: Object, purpose: Array, csrf_token: String, api_token: String },
    data() {
        return {
            
            //size select dropdown menu
            size: { leftColumn  : "col-2", rightColumn : "col-10", select      : "col-3" },  

            //purpose List
            purposeList: 
            {
                IELTS:  "",
                IELTS_option:{ Speaking: "", Writing: "", Reading: "", Listening: "" },
                IELTS_targetScore:{ Speaking: 3, Writing: 3, Reading: 3, Listening: 3 },

                TOEFL: "",
                TOEFL_option: { Speaking: "", Writing: "", Reading: "", Listening: ""},
                TOEFL_targetScore: { Speaking: 0, Writing: 0, Reading: 0, Listening: 0 },

                TOEFL_Primary: "",

                TOEIC: "",
                TOEIC_option: { Speaking: "", Writing: "", Reading: "", Listening: ""},
                TOEIC_targetScore: { Speaking: 0, Writing: 0, Reading: 0, Listening: 0 },                 

                EIKEN: "",
                EIKEN_option: {
                    EIKEN_Grade_5: "",
                    EIKEN_Grade_4: "",
                    EIKEN_Grade_3: "",
                    EIKEN_Grade_pre_2: "",
                    EIKEN_Grade_2: "",
                    EIKEN_Grade_pre_1: "",
                    EIKEN_Grade_1: "",
                },
                EIKEN_targetScore:
                {
                    Grade_5: 0,
                    Grade_4: 0,
                    Grade_3: 0,
                    Grade_pre_2: 0,
                    Grade_2: 0,
                    Grade_pre_1: 0,
                    Grade_1: 0,                      
                }, 

                TEAP: "",
                TEAP_option: { Speaking: "", Writing: "", Reading: "", Listening: "" },
                TEAP_targetScore: { Speaking: 0, Writing: 0, Reading: 0, Listening: 0 },                    

                BUSINES: "",
                BUSINESS_option: { Basic: "", Intermediate: "", Advance: "" },                  
                BUSINESS_targetScore:{ Basic: "Beginner", Intermediate: "Beginner", Advance: "Beginner"}, 

                BUSINESS_CAREERS: "",
                BUSINESS_CAREERS_option:
                {
                    Medicine: "",
                    Nursing: "",
                    Pharmaceutical: "",          
                    Accounting: "",
                    Legal_Professionals: "",
                    Finance: "",       
                    Technology: "",
                    Commerce: "",
                    Tourism: "",       
                    Cabin_Crew: "",
                    Marketing_and_Advertising: "",                                                                                                                        
                },  
                BUSINESS_CAREERS_targetScore:
                {
                    Medicine: "Beginner",
                    Nursing: "Beginner",
                    Pharmaceutical: "Beginner",          
                    Accounting: "Beginner",
                    Legal_professionals: "Beginner",
                    Finance: "Beginner",       
                    Technology: "Beginner",
                    Commerce: "Beginner",
                    Tourism: "Beginner",       
                    Cabin_crew: "Beginner",
                    Marketing_and_advertising: "Beginner",                                                                                                                        
                },

                DAILY_CONVERSATION: "",
                DAILY_CONVERSATION_option: { Basic: "", Intermediate: "", Advance: "" },
                DAILY_CONVERSATION_targetScore:{ Basic: "Beginner", Intermediate: "Beginner", Advance: "Beginner" },

                OTHERS: "",
                OTHERS_value: "",
            }
        };
    },      
    mounted: function () 
	{
        this.getPurposeList();

        let purposeItem  = [];
        let purposeOptionItem = [];
        let purposeOptionItems = [];

        let purposeTargetScoreItem = [];
        let purposeTargetScores = [];

		for (purposeItem of this.purpose) 
        {
            let mainItemStr = purposeItem.purpose.replace(/\s+/g, '_');
            this.purposeList[mainItemStr] = mainItemStr;

            if (purposeItem.purpose.toLowerCase() == "others" ) 
            {
                this.purposeList.OTHERS_value = purposeItem.purpose_options;
            } else {

                purposeOptionItems = JSON.parse( purposeItem.purpose_options);
                if (purposeOptionItems === null || purposeOptionItems === "null" || purposeOptionItems === "") 
                {
                   //no option
                } else {                   
                    for (purposeOptionItem of purposeOptionItems) 
                    {	                      
                        let purposeOptionItemStr = purposeOptionItem.replace(/\s+/g, '_');
                        this.purposeList[mainItemStr +"_option"][purposeOptionItemStr] = purposeOptionItemStr;
                    }
                }

                purposeTargetScores = JSON.parse( purposeItem.target_scores);

                if (purposeTargetScores === null || purposeTargetScores === "null" || purposeTargetScores === "") 
                {
                   //no option
                } else {      

                    Object.entries(purposeTargetScores).forEach(entry => {
                        const [key, value] = entry;
                        const mykeyArray = key.split("_");

                        let keyword = "";

                        mykeyArray.forEach(function(string) 
                        {
                            let keyStr = string.charAt(0).toUpperCase() + string.slice(1);
                            if (string == "and") {
                                keyStr = "and";
                            }
                            //document.getElementById("demo").innerHTML += " "+ keyStr; 
                            keyword += " "+  keyStr;
                          
                        });
                        let keyStrCleaned = keyword.trim();
                        let purposeOptionItemStr = keyStrCleaned.replace(/\s+/g, '_') + "";
                        this.purposeList[mainItemStr +"_targetScore"][purposeOptionItemStr] = value;
                        
                    });
                }

            }
        }
    },
    methods: {    
        getPurposeList() 
        {
            axios.post("/api/getMemberPurposeList?api_token=" + this.api_token,
            {
                method       : "POST",
                memberID     : this.memberinfo.user_id,
            }).then(response => {

                if (response.data.success) {
                    $('#memberPurposeList').html(response.data.content);
                    $('#memberPurposeList').show();
                } else {
                    $('#memberPurposeList').html("<div class='text-center'>" + response.data.message + "</div>");
                    $('#memberPurposeList').show();
                }

            }).catch(function(error) { 
                alert("Error " + error);                
            });
        },
        savePurpose() 
        {

            //SHOW LOADER HERE
            $(document).find('.modal-footer').find('div.buttons-container').hide();
            $(document).find('.modal-footer').find('div.loading-container').show();


            axios.post("/api/updateMemberPurpose?api_token=" + this.api_token, 
            {
                method          : "POST",
                purposeList     : JSON.stringify(this.purposeList)
            })
            .then(response => 
            {
                console.log(response.data.success);
                if (response.data.success === true) 
                {

                    //HIDE LOADER HERE
                    $(document).find('.modal-footer').find('div.buttons-container').show();
                    $(document).find('.modal-footer').find('div.loading-container').hide();


                    this.$nextTick(function()
                    {  
                        this.getPurposeList();
                        

                        $(document).find('.modal-footer').hide();

                        $(document).find('#PurposeComponent').find('.message').html('<div class="alert alert-success text-center" role="alert">Thank you! your purpose has been submitted</div>'); 
                        $(document).find('#purpose-section').slideUp(500);             

                        setTimeout(function(scope) {
                            scope.$bvModal.hide('modalUpdatePurposeForm');
                        }, 1500, this);

                        this.$forceUpdate();
                        

                    });

                } else {

                   alert(response.data.message);

                   $bvModal.hide('modalUpdatePurposeForm')
                } 

			}).catch(function(error) {
                alert("Error " + error);
            });
        }
    } 
};
</script>
