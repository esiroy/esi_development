<template>
    <div class="account-merger-container float-right">  

      
        <span class="btnUpdatePurpose"  v-b-modal.modalAccountMerger v-if="accountType =='unlinked'">
            <i class="fas fa-plus pt-2" aria-hidden="true"></i>
        </span>
        
        <span id="linked-account"  v-b-modal.modalAccountMerger v-if="accountType =='main'">
            <i class="fa fa-link pt-2" aria-hidden="true"></i>
        </span>

        <span class="btnUpdatePurpose"  v-b-modal.modalShowMainAccount v-if="accountType =='secondary'">
            <i class="fas fa-cog pt-2"></i>
        </span>

        <b-modal id="modalShowMainAccount" title="Main Account" size="md" v-if="accountType =='secondary'">
            <div class="container">
                <div class="card">
                    <table class="esi-table table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td>Main Account Member ID</td>
                                <td>Email</td>
                                
                            </tr>
                            <tr>
                                <td>1{{ mainAccount.id }}</td>
                                <td>{{ mainAccount.email }}</td>
                            </tr>                        
                        </tbody>
                    </table>
                </div>
            </div>
             <template #modal-footer>
                <b-button variant="primary" size="sm" class="float-right" @click="$bvModal.hide('modalShowMainAccount')">Close</b-button>
             </template>
        </b-modal>


        <b-modal id="modalAccountMerger" title="Account Merger" size="lg" @show="resetData">
            <div class="container">
                <div class="row">
                    <div class="col-4">                      
                        <span class="text-danger">*</span> 
                        <span class="small">Enter Member ID or Email Address  : </span>               
                    </div>                
                    <div class="col-6">
                        <input type="text" v-model="user.memberID" class="form-control form-control-sm"/>
                    </div>
                </div>
           
                <div class="row pt-2">
                    <div class="col-4">                      
                        <span class="text-danger">*</span> 
                        <span class="small">Enter Password: </span>               
                    </div>                
                    <div class="col-6">
                        <input type="password" v-model="user.password" class="form-control form-control-sm"/>
                    </div>
                </div>  
            </div>

            <div class="container pt-2" v-if="this.users.length >= 1">
                <table class="esi-table table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <td>Member ID</td>
                            <td>Email</td>
                            <td>Action</td>
                        </tr>
                        <tr v-for="(user, userIndex) in users" :key="userIndex">
                            <td>1{{ user.id }}</td>
                            <td>{{ user.email }}</td>
                            <td>
                                <a href="#" @click.prevent="confirmDeleteMergedAccount(user.id)"><b-icon icon=" trash" aria-hidden="true"></b-icon></a>
                            </td>
                        </tr>                        
                    </tbody>
                </table>
            </div>

            <div v-else>
                <div class="container mt-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center small">You have no merged account</div>
                        </div>
                    </div>
                </div>
             </div>


            <template #modal-footer>
                <div class="buttons-container w-100"> 
                    <b-button variant="primary" size="sm" class="float-right mr" v-on:click="mergeAccount">Merge Account</b-button>                  
                    <b-button variant="danger" size="sm" class="float-right mr-2" @click="$bvModal.hide('modalAccountMerger')">Cancel</b-button>                         
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

import {Helpers} from "../../helpers/helpers.js";


export default {   
    name: "member-account-merger-component",
    components: {    
        Helpers
    },     
    data() {
        return {         
            submitted: false,
            accountType: null,

            //main account list

            mainAccount: null,

            //merged account list
            user: {
                memberID: null,
                password: null,
            },
            users: []
        }
    },
    mounted: function () 
	{
        
        this.getMergedAccountType();
    },

    props: {
        icon: String,
        memberinfo: Object,
        csrf_token: String,		
        api_token: String,
    },

    methods: {
        resetData() {        
                    
            this.user.memberID = "";
            this.user.password = "";
            
            this.getMergedAccounts();
        },
        async getURL(url, data) {          
            return Helpers.getURL(url, data);
        },

        async getMergedAccountType() {
        
            let url = "/api/getMergedAccountType?api_token=" + this.api_token;
            let data  = {
                'member_id': this.memberinfo.user_id,
            }

            await this.getURL(url, data).then(response => 
            {
                if (response.data.success == true) 
                {  
                    this.accountType = response.data.type;
                    this.mainAccount = response.data.main_account;
                } else {                    
                    this.accountType = response.data.type;                  
                }

            }).finally(() => {     

                let linkedAccountText  = document.getElementById('linked-account-text');          
                let mergedAccountsList  = document.getElementById('merged-accounts'); 

                if (this.accountType === 'main') {

                    linkedAccountText.classList.remove("d-none");                    
                    mergedAccountsList.classList.remove("d-none");

                } else if (this.accountType === 'secondary') {

                    linkedAccountText.classList.remove("d-none");                    
                    mergedAccountsList.classList.remove("d-none");
                    linkedAccountText.innerHTML = "(merged account)";

                } else {
                    linkedAccountText.classList.add("d-none");
                    mergedAccountsList.classList.add("d-none");
                }
            });  
        },
        confirmDeleteMergedAccount(memberID) 
        {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to delete this merged account.', {
                title: 'Please Confirm',
                size: 'md',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: false
            })
            .then(value => {
                if (value == true) {
                    this.deleteMergedAccount(memberID);
                }
              
            }).catch(err => {
                // An error occurred
            });

        },        
        async deleteMergedAccount(memberID) {

            let url = "/api/deleteMergedAccount?api_token=" + this.api_token;
            let data  = {
                'member_id':memberID
            }

            await this.getURL(url, data).then(response => 
            {
                if (response.data.success == true) 
                {
                    this.users = response.data.merged_accounts;
                    this.getMergedAccounts();
                   
                } else {
                    alert (response.data.message)
                }
            }).finally(() => {          


            });  
        
        },
        async getMergedAccounts() 
        {

            let url = "/api/getMergedAccounts?api_token=" + this.api_token;
            let data  = {
                'member_id': this.memberinfo.user_id
            }

            await this.getURL(url, data).then(response => 
            {
                if (response.data.success == true) 
                {
                   this.users = response.data.merged_accounts;

                    if (this.users.length >= 1) {
                        let accountIds = this.users.map(({id})=> "1"+id).join(", ");

                        document.getElementById('mergeAccountsContainer').classList.remove("d-none");
                        document.getElementById('mergeAccountIDs').innerHTML = accountIds;

                    } else {
                    
                        document.getElementById('mergeAccountsContainer').classList.add("d-none");
                        document.getElementById('mergeAccountIDs').innerHTML = "";
                    }

                   this.getMergedAccountType();

                } else {

                    alert (response.data.message)
                }

            }).finally(() => {    

            });            
        },         
        async mergeSecondaryToMainAccount() {
        
            let url = "/api/mergeSecondaryToMain?api_token=" + this.api_token;
            let data  = {
                'member_id': this.user.memberID,
                'password' : this.user.password,
            }

            await this.getURL(url, data).then(response => 
            {
                if (response.data.success === true) {
                    alert (response.data.message);
                    location.reload()
                } else {
                    alert (response.data.message);
                }

            }).finally(() => {    

            }); 


        },
        async mergeAccount() 
        {
            let url = "/api/createMergedAccount?api_token=" + this.api_token;
            let data  = {
                'member_id': this.user.memberID,
                'password' : this.user.password,
            }

            await this.getURL(url, data).then(response => 
            {

                if (response.data.success == false && response.data.type == 'main') 
                {
                    //CONFIRM MERGE SECONDARY TO MAIN ACCCOUNT
                    this.$bvModal.msgBoxConfirm(response.data.message, 
                    {
                        title: 'Please confirm merging to main account',
                        size: 'md',
                        buttonSize: 'sm',
                        okVariant: 'primary',
                        okTitle: 'YES, LINK ME AS A MERGED ACCOUNT',

                        cancelTitle: 'NO',
                        cancelVariant: 'danger',
                        footerClass: 'p-2',
                        hideHeaderClose: false,
                        centered: false
                    })
                    .then(value => {
                        if (value == true) 
                        {
                            this.mergeSecondaryToMainAccount();
                        }
                    }).catch(err => {
                        // An error occurred
                    });

                }
                else if (response.data.success == true) 
                {
                    alert (response.data.message);
                    this.user.memberID = "";

                    this.getMergedAccounts();

                } else {                    
                    alert (response.data.message)
                }

            }).finally(() => {                            
                
            });            
        },  
    },
    updated: function() {
       
    }
};
</script>

<style scoped>

html {
  scroll-behavior: smooth;
}

</style>