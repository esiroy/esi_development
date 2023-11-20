<template>    
    <div id="memberLevelContainer">  

        <div class="level bg-lightPurple pt-0 px-0">
            <div class="col-md-12 bg-purple text-white pt-2 pb-2 text-center">
                <strong>英語レベル（CEFR)</strong> 
                <span class=" float-right">
                    <span v-b-modal.modalUpdateMemberLevelForm>
                        <i class="fas fa-plus"></i>
                    </span>
                </span>
            </div>

          <div class="col-md-12  pt-2 pb-2 ">
                <div id="memberLevel" class="text-left small"> Current Level: 
                    <span v-if=" this.info[ this.updated ].value == null" class="small text-left">{{  this.info[ this.updated ].content}}</span>
                    <span v-else>
                        {{  this.info[ this.updated ].value }} ({{  this.info[ this.updated ].text }})
                    </span>
                </div>            
            </div>

        </div>

        <b-modal id="modalUpdateMemberLevelForm" title="Member Level" >

            <div id="memberLevelWrapper">
                <div class="row">
                    <div class="col-12">
                        <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/0209160659.html','英語レベル（CEFR)自己評価　参照表',900,820);">
                            英語レベル（CEFR)自己評価 参照表
                        </a>
                    </div>
                </div>


                <div class="row my-3">
                    <div class="col-3 pt-2"> 
                        <span>CEFR Level </span>
                    </div>
                    <div class="col-6"> 

                        <div class="control-group">
                        
                            <div id="level-control" class="controls">
                                <b-form-select id="level" v-model="selected" :options="options" ></b-form-select> 
                                <span class="invalid-feedback">Please select a CEFR Level</span>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div class="row my-3">
                    <div class="col-12">
                        <div v-if="this.info[ this.selected ].value !== null" class="small text-left">
                           
                            {{ "*" + this.info[ this.selected ].content }}
                        </div>
                    </div>
                </div>
            </div>

            <template #modal-footer>
                <div class="buttons-container w-100">
                    <p class="float-left"></p>
                    <b-button variant="primary" size="sm" class="float-right mr" id="saveMemberLevel" v-on:click="saveMemberLevel" @click="show=false">Save</b-button>
                    <b-button variant="danger" size="sm" class="float-right mr-2" @click="$bvModal.hide('modalUpdateMemberLevelForm')">Cancel</b-button>                            
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
 name: "memberLevelComponent",
 data() {
      return {
        updated: null,
        selected: null,
        options: [
          { value: null, text: 'Please select' },
          { value: 'C2', text: 'C2 (Mastery)' },
          { value: 'C1', text: 'C1 (Expert)' },
          { value: 'B2', text: 'B2 (Upper Intermediate)' },
          { value: 'B1', text: 'B1 (Intermediate)' },
          { value: 'A2', text: 'A2 (Elementary)' },
          { value: 'A1', text: 'A1 (Starter)' },
          { value: 'A 0', text: 'A 0 (Beginner)' }
        ],
        info:
            { 
                null:  {
                        value: null,
                        content: 'レベルを入力してください'
                    },
                'C2': { 
                        value: 'C2',
                        text: "Mastery",
                        content: "IELTS 8.5 ～ 9.0 / Cambridge English 200 ～ 230"
                    }, 
                'C1': { 
                        value: 'C1',
                        text: "Expert",
                        content: "IELTS 7.0～8.0 / TOEFL iBT 95～120 / 英検 G-1(2600～3299) / TEAP 375～400 / Cambridge English 200 ～ 230"
                    },
                'B2': {
                        value:  'B2',
                        text:   "Upper Intermediate",
                        content: 'IELTS 5.5～6.5 / TOEFL iBT 72～94 / 英検 G-Pre1(2300～2599) / TEAP 309～374 /TOEFL Junior Standard 851 ～ 900 / Cambridge English 160 ～ 179' 
                    },  
                'B1': 
                    {
                        value: 'B1',
                        text: "Intermediate",
                        content: 'IELTS 4.0～5.0 / TOEFL iBT 42～71 / 英検 G-2(1950～2299) / TEAP 225～308 / TOEFL Junior Standard 745 ～ 849 / TOEFL Primary Step 2 (227～ 230) / Cambridge English 140 ～ 159' 
                    },
                
                'A2': {
                        value: 'A2',
                         text: "Elementary",
                        content: 'IELTS 3.5 / TOEFL iBT 31～41 / 英検 G-Pre2(1700～1949) / TEAP 135～224 / TOEFL Junior Standard 645 ～ 744 / TOEFL Primary Step 2 (212～ 227) Step1 (212～ 218) / Cambridge English 120 ～ 139' 
                    },
                'A1': {

                        value: 'A1',
                        text: 'Starter',
                        content: 'IELTS 3.0 / TOEFL iBT 21～30 / 英検 G-3(1400～1699) / TEAP 110～134 /TOEFL Junior Standard 600 ～ 644 / TOEFL Primary Step 2 (204～ 211) Step1 (204～ 211) / Cambridge English 100 ～ 119' 
                    },
                'A 0': {

                        value: 'A 0',
                        text: 'Beginner',
                        content: 'CEFR A1 ～ C2 に該当しない 英語初心者 注意： A0 のレベルはCEFRにはございませんが、便宜上作りました。' 
                    }, 
             }
      }
    },

mounted: function () 
	{
         this.getMemberLevel();
    },
    props: {
        memberinfo: Object,
        csrf_token: String,		
        api_token: String,
    },
            
    methods: {  

        getMemberLevel() 
        {
            if (this.selected == null ) {
                $("#level").addClass('is-invalid');
            } 

            axios.post("/api/getMemberLevel?api_token=" + this.api_token,
            {
                method       : "POST",
                memberID     : this.memberinfo.user_id

            }).then(response => {     
                if (response.data.success === true) 
                { 
                    this.updated = response.data.level.level; 
                    this.selected = response.data.level.level; 

                } else {
                    //set null since it has not submitted
                    this.updated = null;
                }
			});

        },
        saveMemberLevel() 
        {

            if (this.selected == null ) {
                $("#level").addClass('is-invalid');
                return false;
            } 

            //SHOW LOADER HERE
            $(document).find('.modal-footer').find('div.buttons-container').hide();
            $(document).find('.modal-footer').find('div.loading-container').show();


            axios.post("/api/saveMemberLevel?api_token=" + this.api_token,
            {
                method       : "POST",
                memberID     : this.memberinfo.user_id,
                level        : this.selected,
                description  : this.info[ this.selected ].text,

            }).then(response => {     
                if (response.data.success === true) 
                { 
                    this.updated = response.data.level

                    //HIDE LOADER HERE
                    $(document).find('.modal-footer').find('div.buttons-container').show();
                    $(document).find('.modal-footer').find('div.loading-container').hide();

                
                    $(document).find('.modal-footer').hide();

                    $(document).find('#memberLevelWrapper').slideUp(500, function() {
                        $(document).find('#memberLevelWrapper').html('<div class="alert alert-success text-center" role="alert">Thank you! your level has been submitted</div>');
                        $(document).find('#memberLevelWrapper').slideDown(500, function() {
                             $(document).find('#memberLevelWrapper').show();
                        });
                    }); 

                    setTimeout(function(scope) {
                         scope.$bvModal.hide('memberLevelWrapper', 500);
                    }, 3500, this);

                    this.$forceUpdate();

                } else {
                
                }
			});

        }        
    },
    updated: function() {
        if (this.selected != null ) {            
            $("#level").removeClass('is-invalid');
        }
    }
};
</script>
