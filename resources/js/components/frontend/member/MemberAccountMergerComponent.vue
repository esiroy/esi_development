<template>
    <div class="account-merger-container float-right">  

      
        <span class="btnUpdatePurpose"  v-b-modal.modalAccountMerger v-if="accountType =='main'">
            <i class="fas fa-plus pt-2"></i>
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


        <b-modal id="modalAccountMerger" title="Account Merger" size="md" @show="resetData">
            <div class="container">
                <div class="row">
                    <div class="col-4">                      
                        <span class="text-danger">*</span> 
                        <span class="small">Enter Member ID : </span>               
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
                                <a href="#" @click.prevent="deleteMergedAccount(user.id)"><b-icon icon=" trash" aria-hidden="true"></b-icon></a>
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
                ///alert (this.user.password + "  : " + md5(this.user.password));
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

                } else {
                    
                    alert (response.data.message)
                }
            }).finally(() => {          

                 this.getMergedAccounts();
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

                } else {
                    
                    alert (response.data.message)
                }
            }).finally(() => {     

                ///alert (this.user.password + "  : " + md5(this.user.password));
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
                if (response.data.success == true) 
                {
                    alert (response.data.message);
                    this.getMergedAccounts();
                } else {                    
                    alert (response.data.message)
                }

            }).finally(() => {                            
                ///alert (this.user.password + "  : " + md5(this.user.password));
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