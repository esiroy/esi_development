<template>     
    <div id="memberPurposeList" class="text-left">                  
        {{ this.purposeList }}                   
    </div>
</template>

<script>


export default {
    name: "MemberPurposeViewerComponent",
    props: { memberinfo: Object, userinfo: Object, purpose: Array, csrf_token: String, api_token: String },
    data() {
        return {
            purposeList: "",
        };
    },      
    mounted: function () 
	{
        this.getPurposeList();
    },
    methods:{    
        getPurposeList() 
        {
            axios.post("/api/getMemberPurposeList?api_token=" + this.api_token,
            {
                method       : "POST",
                memberID     : this.memberinfo.user_id,

            }).then(response => {

                if (response.data.success) {
                    $('#memberPurposeList').html(response.data.content);
                } else {
                    $('#memberPurposeList').html("<div>" + response.data.message + "</div>");
                }

            }).catch(function(error) { 
                alert("Error " + error);                
            });
        }
    }
};
</script>
