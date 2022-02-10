<template>

  <div id="ScoresComponent" class="ScoresComponent">

    <!--[start] TEAP- -->
    <div id="examination-score-TEAP" class="section examScoreHolder">

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small  mb-2"> <span class="text-danger">*</span> Speaking Score </div>             
            </div>
            <div :class="this.size.rightColumn">            
                <select @change="getTotal" id="TEAP-speakingScore" name="speakingScore" v-model="examScore.TEAP.speakingScore" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Speaking Score</option>
                    <option :value="item -1" :key="item -1" class="mx-0 px-0" v-for="item in 31">{{ item -1 }}</option>                     
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Writing Score </div>                
            </div>
            <div :class="this.size.rightColumn">            
                <select @change="getTotal" id="TEAP-writingScore" name="writingScore" v-model="examScore.TEAP.writingScore" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Writing Score</option>
                    <option :value="item -1" :key="item -1" class="mx-0 px-0" v-for="item in 31">{{ item -1 }}</option>                     
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Reading Score </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="TEAP-readingScore" name="readingScore" v-model="examScore.TEAP.readingScore" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Reading Score</option>
                    <option :value="item -1" :key="item -1" class="mx-0 px-0" v-for="item in 31">{{ item -1 }}</option>                     
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Listening Score </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="TEAP-listeningScore" name="listeningScore" v-model="examScore.TEAP.listeningScore" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Listening Score</option>
                    <option :value="item -1" :key="item -1" class="mx-0 px-0" v-for="item in 31">{{ item -1 }}</option>                     
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div :class="this.size.leftColumn">
                <div class="pl-2 small "> <span class="text-danger">*</span> Total Score </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="total" disabled name="TEAP-total" v-model="examScore.TEAP.total" :class="this.size.select +' form-control form-control-sm '">  
            </div>
        </div>
    </div>
    <!--[end]-->

    
  </div>
</template>

<script>

export default {
  name: "TeapScoreComponent",
  data() {
    return {                 
     
    };
  },
  props: {
    examScore: Object,
    size: Object,
  },
  methods: {
    getTotal() {
        let total =  this.$parent.$parent.$parent.$options.methods.getTotalScore('TEAP');
        this.examScore.TEAP.total  = total;

        if (this.$parent.$parent.$parent.submitted === true) {
            this.$parent.$parent.$parent.$options.methods.highlightExamElement();
        }        
    }
  },
  computed: {},
  updated: function () {
    
  },
  mounted: function () 
  {
    //console.log("TEAP added")
  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>