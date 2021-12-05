<template>

  <div id="ScoresComponent" class="ScoresComponent">

    <!--[start] TOEFL_Primary_Step_1- -->
    <div id="examination-score-TOEFL_Primary_Step_1" class="section examScoreHolder">
        <div class="row pt-2">
            <div class="col-2">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Listening Score </div>                
            </div>
            <div class="col-10">
                <select id="TOEFL_Primary_Step_1-listeningScore" name="listeningScore" v-model="examScore.TOEFL_Primary_Step_1.listening" class="form-control form-control-sm pl-0 col-md-3">
                    <option value="" class="mx-0 px-0">Select Listening Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 110">{{ item -1 }}</option>
                </select>
            </div>
        </div>



        <div class="row pt-2">
            <div class="col-2">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Reading Score </div>                
            </div>
            <div class="col-10">            
                <select id="TOEFL_Primary_Step_1-writingScore" name="writingScore" v-model="examScore.TOEFL_Primary_Step_1.reading" class="form-control form-control-sm pl-0 col-md-3">
                    <option value="" class="mx-0 px-0">Select Reading Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 110">{{ item -1 }}</option>
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div class="col-2">
                <div class="pl-2 small "> <span class="text-danger">*</span> Total Score </div>
            </div>
            <div class="col-10">
                <select id="TOEFL_Primary_Step_1-total" name="total" v-model="examScore.TOEFL_Primary_Step_1.total" class="form-control form-control-sm pl-0 col-md-3 mb-2">
                    <option value="" class="mx-0 px-0">Select Total Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 219">{{ item -1 }}</option>
                </select>
            </div>
        </div>
    </div>
    <!--[end]-->

    
  </div>
</template>

<script>

export default {
  name: "TOEFL_Primary_Step_1ScoreComponent",
  data() {
    return {                 
     
    };
  },
  props: {
    examScore: Object
  },
  methods: {
    getTotalScore: function(index) {

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