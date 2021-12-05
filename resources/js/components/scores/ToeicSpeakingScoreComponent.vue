<template>

  <div id="ScoresComponent" class="ScoresComponent">

    <!--[start] TOEIC-Speaking- -->
    <div id="examination-score-TOEIC_Speaking" class="section examScoreHolder">
        <div class="row pt-2">
            <div class="col-2">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Listening Score </div>                
            </div>
            <div class="col-10">
                <select id="TOEIC_Speaking-listeningScore" name="listeningScore" v-model="examScore.TOEIC_Speaking.speaking" class="form-control form-control-sm pl-0 col-md-3">
                    <option value="" class="mx-0 px-0">Select Listening Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 201">{{ item - 1 }}</option>
                </select>
            </div>
        </div>

    </div>
    <!--[end]-->

    
  </div>
</template>

<script>

export default {
  name: "ToeicSpeakingScoreComponent",
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
    //console.log("TOEIC_Speaking- junior mounted")
  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>