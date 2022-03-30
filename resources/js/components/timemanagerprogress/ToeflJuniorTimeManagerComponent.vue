<template>

    <!--[start] TOEFL_Junior_ -->
    <div id="timeManager-TOEFL_Junior" class="d-none">


        <div class="row">
            <div :class="this.size.leftColumn">
                <div class="pl-2 small"> <span class="text-danger">&nbsp;</span> Course </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="course" :value="this.content.courseTextValue "
                    disabled name="course"  :class="this.size.select +' form-control form-control-sm '">  
            </div>
        </div>


        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Listening</div>                
            </div>
            <div :class="this.size.rightColumn">
                <input @change="getTotalMinutes" id="TOEFL_Junior_listening" name="listening" v-model="data.listening" :class="this.size.select +' form-control form-control-sm pl-0'">                    
            </div>
        </div>


        <div class="row pt-2">
            <div :class="this.size.leftColumn">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Language Form & Meaning</div>                
            </div>
            <div :class="this.size.rightColumn">
                <input @change="getTotalMinutes" id="TOEFL_Junior_languageFormAndMeaning" name="languageFormAndMeaning" v-model="data.languageFormAndMeaning" :class="this.size.select +' form-control form-control-sm pl-0'">                  
            </div>
        </div>

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Reading  </div>                
            </div>
            <div :class="this.size.rightColumn">            
                <input @change="getTotalMinutes" id="TOEFL_Junior_reading" name="reading" v-model="data.reading" :class="this.size.select +' form-control form-control-sm pl-0'">                   
            </div>
        </div>

        <div class="row pt-2">
            <div :class="this.size.leftColumn">
                <div class="pl-2 small "> <span class="text-danger">*</span> Total  </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="TOEFL_Junior_total" disabled name="total" v-model="data.total" :class="this.size.select +' form-control form-control-sm '"> 
            </div>
        </div>

    </div>
    <!--[end]-->

    
  
</template>

<script>

export default {
  name: "TOEFL_Junior_JuniorScoreComponent",
  data() {
    return {                 
        data: {        
            speaking: "",
            reading: "",
            writing: "",
            listening: "",
            total: ""
        }       
    };
  },
  props: {
    content: Object,
    size: Object,
  },
  methods: {
    getTotalMinutes() {

    
    }
  },
  computed: {},
  updated: function () {
    
  },
  mounted: function () 
  {
    //.log("TOEFL_Junior_ junior mounted")
  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>