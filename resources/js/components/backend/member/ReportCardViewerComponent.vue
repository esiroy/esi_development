<template>  
    <div id="memberMultiAccountContainer">
        <div id="member-multaccount-ui" class="border pt-0 px-0">
            <div class="col-md-12 bg-darkblue text-white pt-2 pb-2 text-center">
                <strong>Recent Lesson</strong> 
              
                <!--
                <span class=" float-right">
                    <span v-b-modal.recentlessons>
                        <i class="fas fa-plus"></i>
                    </span>
                </span>
                -->
            </div>

            <div class="col-md-12  pt-2" v-if="isAliasAccount == true">

                <div id="memberAccount" class="text-center">                   
                    <div v-if="this.$props.selected_account_id">
                        <select name="accounts" id="accounts" class="form-control form-control-sm" @change="onChangeAccountViewer($event)">
                            <option :value="accounts.member_multi_account_id" v-for="(accounts,i) in this.accountLists" 
                                :key="'account-'+i" class="small" :selected="(accounts.member_multi_account_id == selected_account_id) ? true: ''">
                                {{ accounts.name }} <span v-if="(accounts.is_default == true)">(default)</span>
                            </option>
                        </select>
                    </div>
                    <div v-else>                    
                        <select name="accounts" id="accounts" class="form-control form-control-sm" @change="onChangeAccountViewer($event)">
                            <option :value="accounts.member_multi_account_id" v-for="(accounts,i) in this.accountLists" 
                                :key="'account-'+i" class="small" 
                                :selected="(accounts.is_default) ? true: ''">
                                {{ accounts.name }} <span v-if="(accounts.is_default == true)">(default)</span>
                            </option>
                        </select>
                    </div>
                </div>
            </div>    

            <div id="recentlessonContainer" v-if="latestReportCard">

                <div class="col-12 pt-2 pb-2">

                   

                    <p class="small">
                        <span class="font-weight-bold small">Course :</span>
                        {{ latestReportCard.lesson_course }}
                    </p>
                    <p class="small">
                        <span class="font-weight-bold small">Material :</span>
                        {{ latestReportCard.lesson_material }}
                    </p>
                    <p class="small">
                        <span class="font-weight-bold small">Subject :</span>
                        {{ latestReportCard.lesson_subject }}
                    </p>

                    <p class="small">
                        <span class="font-weight-bold small">lesson Time :</span>
                        {{ format_date(latestReportCard.lesson_time) }}
                    </p>

                    <div id="homework-container" class="mt-2">
                        <table class="table esi-table table-bordered table-striped">
                            <thead >
								<tr>
									<td>Home Work </td> 
								</tr>
							</thead>                                                        
                            <tr>
                                <td class="px-2 text-left">  

                                    <div v-if="this.homework !== null">
                                        <div class="small" >
                                            File: <a :href="this.homework" 
                                            :download="this.homework" >{{ this.homeworkFilename }}</a>
                                        </div>
                                        <div class="small">
                                            Instruction : {{ this.instruction }}
                                        </div>
                                    </div>
                                
                                    <div v-else class="text-center">
                                        <span class="small text-secondary">No homework found!</span>
                                    </div>
                                </td>
                            </tr>
                        </table> 
                    </div>

                </div>

                <div class="col-12 d-flex justify-content-center pb-2" @click="">
                    <b-button size="sm" variant="dark" pill @click="showReportsModal(latestReportCard.member_multi_account_id, 1)">
                        <b-icon-card-list></b-icon-card-list> <span class="small"> View All </span> 
                    </b-button>                   
                </div>

            </div>  
            <div v-else>
                <div class="col-12">
                    <div class="text-center small py-3">
                        No Result for this account
                    </div>
                </div>
            </div>
        </div>     

        <div id="multiaccount-modal-container">

            <!--start recent lesson modal-->
            <b-modal id="reportcard" size="lg" title="Recent Lessons"> 

                <div class="row" v-show="this.loading">
                    <div class="col-12 text-center">
                        <b-spinner variant="primary" label="Spinning"></b-spinner>
                    </div>
                </div> 

                <div class="row" v-show="!this.loading">  

                    
                    <div class="col-12 text-center" v-if="reportCards.total >=1">
                        <table class="table esi-table table-bordered table-striped" >
                            <tbody :id="item.id" v-for="(item, itemIndexKey) in reportCards.data" :key="itemIndexKey">
                                <tr>
                                    <td> ID </td>
                                    <td>
                                        {{  item.id }}
                                    </td>
                                </tr>                                
                                <tr>
                                    <td> Lesson Date </td>
                                    <td>
                                       {{ dateFormatter(item.lesson_time) }}
                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td> Course </td>
                                    <td>
                                        {{  (item.lesson_course) }}
                                    </td>                                    
                                </tr>    
                                <tr>
                                    <td> Material </td>
                                    <td>
                                        {{  (item.lesson_material) }}
                                    </td>                                    
                                </tr>  
                                <tr>
                                    <td> Subject </td>
                                    <td>
                                        {{  (item.lesson_subject) }}
                                    </td>                                    
                                </tr>   
                            </tbody>
                        </table>



                        <!--
                        current page: {{reportCards.current_page}},
                        total: {{reportCards.total}} ,                        
                        per page : {{reportCards.per_page}}
                        -->
                       


                        <b-pagination
                            v-model="reportCards.current_page"
                            @input="changePage(reportCards.current_page)"                       
                            :total-rows="reportCards.total"
                            :per-page="reportCards.per_page"
                            first-text="<<"
                            prev-text="<"
                            next-text=">"
                            last-text=">>"
                            size="sm"
                            align="center"
                            class="mt-2">
                        </b-pagination>

                    </div>     
                    <div class="text-center" v-else>
                        No Data Found
                    </div>                                   
                </div>
               

                <template #modal-footer>

                    <div class="buttons-container w-100" v-if="!loading">
                        <b-button variant="primary" size="sm" class="float-right mr-2" @click="$bvModal.hide('reportcard')">Close</b-button>                            
                    </div>

                    <div v-if="loading">
                        <b-button variant="primary" size="sm" class="float-right mr">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                        </b-button>
                    </div>  

                </template>

            </b-modal>
        </div>

    </div>
    
</template>

<script>

import * as Moment from 'moment';


export default {   
	name: "memberMultiAccountComponent",
    props: {
        memberinfo: Object,
        csrf_token: String,		
        api_token: String,
        selected_account_id: Number,
    },	
	data() {
		return {
            loading: false,
            isAliasAccount: false,

            //current selected account
            selectedAccountID: null,

			// Initialize reactive data properties here if needed
            accounts: null,
            accountActivated: null,

            //Error Handler
            hasError: null,
            errorMessage: null,

            //Success Handler
            isSuccess: null,
            successMessage: null,

            latestReportCard: null,    

             //Account Listings
             accountLists: null,
             
             //reportcards Listings
             reportCards: [],

             //homework
            homework: null,
            homeworkFilename: null,
            instruction: null,
		};
	},
    mounted: function () 
	{          
        this.listAccounts();

        this.getRecentLessonScoreByMultiID(this.selected_account_id);
    },        
	methods: {
        refresh() {
            //Entry Accounts
            this.accounts = null;
            //this.isAliasAccount = false;

            this.hasError =  null;
            this.errorMessage =  null;

            this.isSuccess =  null;
            this.successMessage = null;           
            
            clearTimeout(this.hideTimeOut);
        },
        changePage(page) {
       
          this.showAllResultByPage(this.selectedAccountID, page)
        },
        showReportsModal(selectedAccountID, page) { 
            this.$bvModal.show('reportcard');

            //OPEN MODAL AND SAVE THE ACCOUNT ID 
            this.selectedAccountID = selectedAccountID;            
            this.showAllResultByPage(selectedAccountID, page)
        },        
        showAllResultByPage(selectedAccountID, page) {

            //axios.post("/api/getRecentAllLessonByMultiID?api_token=" + this.api_token,
            axios.post("/api/getRecentAllLessonByMultiID?page="+ page +"&api_token=" + this.api_token,                      
            {
                method       : "POST",
                memberID     : this.memberinfo.user_id,  
                accountID    : selectedAccountID           
            }).then(response => {
                
                if (response.data.success == true) 
                {
                    this.reportCards = response.data.reportCards;
                    console.log(this.reportCards);
                    
                    this.$forceUpdate();    
                }                
            });         
        },
        getRecentLessonScoreByMultiID(selectedAccountID) 
        {
            axios.post("/api/getRecentLessonScoreByMultiID?api_token=" + this.api_token,
            {
                method       : "POST",
                memberID     : this.memberinfo.user_id,  
                accountID    : selectedAccountID           
            }).then(response => {
                
                if (response.data.success == true) 
                {
                    this.latestReportCard = response.data.latestReportCard;

                    //homework
                    this.homework = response.data.homework;
                    this.homeworkFilename = response.data.homeworkFilename;
                    this.instruction = response.data.instruction;

                    this.$forceUpdate();    
                }                
            });
        },
        listAccounts() {

            axios.post("/api/listMemberMultiAccount?api_token=" + this.api_token,
            {
                method       : "POST",
                memberID     : this.memberinfo.user_id,             
            }).then(response => {
                
                if (response.data.success == true) 
                {
                    if (response.data.isAliasAccount == true) {
                        
                        this.isAliasAccount = true;
                        this.accountLists = response.data.accounts; 

                        this.$forceUpdate();  
                    } else {
                        this.isAliasAccount = false;
                        this.$forceUpdate();                          
                    }
                }                
            });            

        },
        format_date(value){
         if (value) {
            let fdate = this.dateFormatter(value)
            return fdate;  
          } else {
            return null;
          }
        },
        updateDefaultAccount(num) {
            for (let i = 0; i < this.accounts.length; i++) {
                if (i !== num) {
                    this.accounts[i].is_default = false;
                }                
            }
        },
        onChangeAccountViewer(event) {
            let selectedAccountID = event.target.value
            this.getRecentLessonScoreByMultiID(selectedAccountID);            
        },
        dateFormatter(date) 
        {

            let fdate = Moment(date, "YYYY-MM-DD HH:mm:ss").format("YYYY年 MM月 D日 HH:mm");
            
            if (Moment(date).hour() === 0) {                
                fdate = Moment(date, "YYYY-MM-DD HH:mm:ss").subtract(1, 'days').format("YYYY年 MM月 D日 24:mm");
            }

            return fdate;            
        }
    
	},

}
</script>