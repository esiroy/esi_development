<template>

  <div id="timeManager-TOEFL_Primary_Step_1"  class="d-none">


    <div class="row">
        <div :class="this.size.leftColumn">
            <div class="pl-2 small"> <span class="text-danger">&nbsp;</span> Course </div>
        </div>
        <div :class="this.size.rightColumn">
            <input type="text" id="course" :value="this.content.courseTextValue"
                disabled name="course"  :class="this.size.select +' form-control form-control-sm '">  
        </div>
    </div>


    <div class="row pt-2">
        <div :class="this.size.leftColumn">                       
            <div class="pl-2 small "> <span class="text-danger">*</span> Listening Score </div>                
        </div>
        <div :class="this.size.rightColumn">
            <input @change="getTotal" id="TOEFL_Primary_Step_1-listeningScore" name="listeningScore" v-model="data.listening" :class="this.size.select +' form-control form-control-sm'">
        </div>
    </div>

    <div class="row pt-2">
        <div :class="this.size.leftColumn">                                       
            <div class="pl-2 small "> <span class="text-danger">*</span> Reading Score </div>                
        </div>
        <div :class="this.size.rightColumn">            
            <input @change="getTotal" id="TOEFL_Primary_Step_1-readingScore" name="readingScore" v-model="data.reading" :class="this.size.select +' form-control form-control-sm'">
               
        </div>
    </div>


    <div class="row pt-2">
        <div :class="this.size.leftColumn">
            <div class="pl-2 small "> <span class="text-danger">*</span> Total Score </div>
        </div>
        <div :class="this.size.rightColumn">
            <input type="text" id="total" disabled name="TOEFL_Primary_Step_1-total" v-model="data.total" :class="this.size.select +' form-control form-control-sm '">               
        </div>
    </div>


    
  </div>
</template>

<script>

export default {
  name: "TOEFL_Primary_Step_1_TimeManagerComponent",
  data() {
    return {                 
        data: {       
            reading: "",
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
    getTotal() {
       
    }
  },
  computed: {},
  updated: function () {
    
  },
  mounted: function () 
  {
    //console.log("TOEFL_Primary_Step_1- junior mounted")
  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>