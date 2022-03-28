<template>

  <div id="timeManager-TOEIC_Listening_and_Reading" class="ScoresComponent">

  
        <div class="row">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Listening Score </div>                
            </div>
            <div :class="this.size.rightColumn">
                <input v-on:keyup="getTotalMinutes" id="TOEIC_Listening_and_Reading-listeningScore" name="listeningScore" v-model="listening" :class="this.size.input +' form-control form-control-sm pl-0'">
                   
            </div>
            
        </div>



        <div class="row pt-2">
            <div :class="this.size.leftColumn">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Reading Score </div>                
            </div>
            <div :class="this.size.rightColumn">            
                <input v-on:keyup="getTotalMinutes" id="TOEIC_Listening_and_Reading-readingScore" name="vScore" v-model="reading" :class="this.size.select +' form-control form-control-sm pl-0'">
                   
            </div>
        </div>


        <div class="row pt-2">
            <div :class="this.size.leftColumn">
                <div class="pl-2 small "> <span class="text-danger">*</span> Total Score </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="total" disabled name="TOEIC_Listening_and_Readingtotal" v-model="total" :class="this.size.select +' form-control form-control-sm '"> 
            </div>
        </div>
   

    
  </div>
</template>

<script>

export default {
  name: "TOEIC_Listening_and_ReadingScoreComponent",
  data() {
    return {                 
     
    };
  },
  props: {
    examScore: Object,
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
    //console.log("TOEIC_Listening_and_Reading- junior mounted")
  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>