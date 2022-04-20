<template>
    <div>   
        <b-modal id="modalAccountMerger" title="Account Merger" size="xl" @hide="resetData">
            <template #modal-title>     
                Account Merger          
            </template>

            <template #modal-footer>
                <div class="buttons-container w-100">                    
                    <div v-if="updateType == 'update' || updateType == 'edit'">
                        <b-button variant="primary" size="sm" class="float-right mr" id="updateTimeManager" v-on:click="updateTimeManager">Update Time Manager Data</b-button>
                    </div>
                    <b-button variant="danger" size="sm" class="float-right mr-2" @click="$bvModal.hide('modalTimeManager')">Cancel</b-button>                         
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
export default {   
    name: "member-account-merger-component",
    components: {    

    },     
    data() {
        return {         
            submitted: false
        }
    },
    mounted: function () 
	{
        this.getMergedAccounts()  
    },

    props: {
        memberinfo: Object,
        csrf_token: String,		
        api_token: String,
    },

    methods: {
        resetData() {
        
        
        }
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