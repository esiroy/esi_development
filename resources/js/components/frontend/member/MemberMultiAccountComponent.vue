<template>    


    <div id="memberMultiAccountContainer">
      
        
        <div id="member-multaccount-ui" class="bg-lightgreen pt-0 px-0">
            <div class="col-md-12 bg-green text-white pt-2 pb-2 text-center">
                <strong>Multi Account</strong> 
                <span class="float-right">
                    <span v-b-modal.memberMultiAccountModal>
                        <i class="fas fa-plus"></i>
                    </span>
                </span>
            </div>
            <div class="col-md-12  pt-2 pb-2 ">

                <div id="memberAccount" class="text-center small" v-if="!this.isAliasAccount"> 
                    <b-button variant="primary" size="sm" v-b-modal.memberMultiAccountModal>Activate Multiple Account</b-button>                 
                </div>            

                <div id="memberAccount" class="text-center" v-if="this.isAliasAccount"> 
                    <span class="small">Current Account:</span>
                    
                    <div v-if="this.$props.selected_account_id">
                        <select name="accounts" id="accounts" class="form-control form-control-sm" @change="onChangeAccount($event)">
                            <option :value="accounts.member_multi_account_id" v-for="(accounts,i) in this.accountLists" 
                                :key="'account-'+i" class="small" :selected="(accounts.member_multi_account_id == selected_account_id) ? true: ''">
                                {{ accounts.name }} <span v-if="(accounts.is_default)">(default)</span>
                            </option>
                        </select>
                    </div>
                    <div v-else>
                        <select name="accounts" id="accounts" class="form-control form-control-sm" @change="onChangeAccount($event)">
                            <option :value="accounts.member_multi_account_id" v-for="(accounts,i) in this.accountLists" :key="'account-'+i" class="small" :selected="(accounts.is_default) ? true: ''">
                                {{ accounts.name }} <span v-if="(accounts.is_default)">(default)</span>
                            </option>
                        </select>
                    </div>

                </div>

            </div>            
        </div>     

        <div id="multiaccount-modal-container">
            <b-modal id="memberMultiAccountModal" size="lg" title="Add Multiple Account" @show="showAddMultiAccountsModal">                  

           

                <div class="row" v-show="this.loading">
                    <div class="col-12 text-center">
                        <b-spinner variant="primary" label="Spinning"></b-spinner>
                    </div>
                </div> 

                <div class="row" v-show="!this.loading">                   
                    <div class="col-12">
                        <div v-show="isSuccess" class="alert alert-success text-center" role="alert">
                            {{  successMessage }}
                        </div>
                    </div>
                               
                    <div id="multiAccountWrapper" class="col-12">
                        <div class="row">
                            <div class="col-3" v-for="(account,i) in accounts" :key="i">

                                <input type="checkbox" name="memberMultiAccount" 
                                    :value="account.id" v-model="account.selected"
                                    :disabled="(i==0)? true: false"
                                >
                                <span class="font-weight-bold">{{ account.name }} </span>
                            

                                <div :id="'alias_container_'+i" v-show="account.selected">
                                    <span class="small">Name your account </span>
                                    <input type="text" :id="'accountAlias-'+i" maxlength="30" name="accountAlias" v-model="account.name" 
                                        placeholder="Account Name" class="form-control form-control-sm">                           
                                    <span :id="'accountAlias-error-'+i" class="text-danger small" style="display: none;"></span>

                                    <div class="row small mt-2">
                                        <div class="col-12 tex-left">
                                            {{ account.is_default }}

                                            <input type="checkbox" name="defaultAccount" 
                                                :value="account.id" v-model="account.is_default"                                              
                                                @click="updateDefaultAccount(i)"
                                                >
                                            <span class="small">Set default account</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <template #modal-footer>

                    <div class="buttons-container w-100" v-if="!loading">
                        <b-button variant="primary" size="sm" class="float-right mr" id="saveAccount" v-on:click="saveAccount">Save</b-button>
                        <b-button variant="danger" size="sm" class="float-right mr-2" @click="$bvModal.hide('memberMultiAccountModal')">Cancel</b-button>                            
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

			// Initialize reactive data properties here if needed
            accounts: null,
            accountActivated: null,

            //Error Handler
            hasError: null,
            errorMessage: null,

            //Success Handler
            isSuccess: null,
            successMessage: null,

             //Listings
            accountLists: null
		};
	},
    mounted: function () 
	{       
        this.listAccounts()
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
        updateDefaultAccount(num) {
            for (let i = 0; i < this.accounts.length; i++) {
                if (i !== num) {
                    this.accounts[i].is_default = false;
                }                
            }
        },
        onChangeAccount(event) {
            console.log(event.target.value)
            window.location.href = "?accountID="+ event.target.value;
        },
        saveAccount(a) {
            let accountMemberSelectedCtr = 0;
            
            let isDefaultCtr = 0;
            let isAliasError = 0;

            for (let i = 0; i < this.accounts.length; i++) 
            {
                if (this.accounts[i].name.trim() == '' && this.accounts[i].selected == true) {
                    isAliasError++;
                    $('#accountAlias-'+ i).addClass("border-danger");
                    $('#accountAlias-error-'+ i).text("Please Input account name")
                    $('#accountAlias-error-'+ i).show()                    
                }

                if (this.accounts[i].selected == true) {
                    accountMemberSelectedCtr++;
                }

                if (this.accounts[i].is_default == true && this.accounts[i].selected == true) {
                    isDefaultCtr++;
                }
            }

            if (isAliasError >= 1) {
                this.hasError = true;
                this.errorMessage = "Please select account name";
                return false;
            }

            if (isDefaultCtr >= 1) {
                this.saveMultipleAccounts();
            } else {
                if (accountMemberSelectedCtr >= 1) {
                    alert("You need to select default account")
                } else {
                    this.saveMultipleAccounts();
                }               
            }
        },
        saveMultipleAccounts() 
        {
            axios.post("/api/saveMemberMultiAccount?api_token=" + this.api_token,
            {
                method       : "POST",
                memberID     : this.memberinfo.user_id,
                accounts     : this.accounts,
                isAliasAccount: this.isAliasAccount,
            }).then(response => {

                if (response.data.success === true) 
                {
                    this.showAddMultiAccountsModal();
                    this.isSuccess = true;
                    this.successMessage = response.data.message;

                    this.listAccounts();

                   this.hideTimeOut = setTimeout(this.hideMultiAccountModal, 3000);

                } else {
                   //show alert
                }
            });
        },
        hideMultiAccountModal() {
            this.refresh();
            this.$bvModal.hide('memberMultiAccountModal');
            window.location.href = "home";
        },
		showAddMultiAccountsModal() {

            this.refresh();
            this.loading = true;
			
            axios.post("/api/getMemberMultiAccount?api_token=" + this.api_token,
            {
                method       : "POST",
                memberID     : this.memberinfo.user_id

            }).then(response => {     

                this.loading = false;

                if (response.data.success === true) 
                { 
                    this.accounts = response.data.accounts; 
                    this.isAliasAccount = response.data.isAliasAccount;

                } else {
                    //set null since it has not submitted
                    this.accounts = null;
                }
			});            
		},
	
	},

}
</script>