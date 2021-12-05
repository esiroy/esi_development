<template>

  <div id="ScoresComponent" class="ScoresComponent">
        
        
    <!--[start] IELTS -->
    <div id="examination-score-IELTS" class="section examScoreHolder">
        <div class="row pt-2">
            <div class="col-2">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Overall Band Score </div>
            </div>
            <div class="col-10">
                <select id="overallBandScore" name="overall Band Score" v-model="examScore.IELTS.overallBandScore" class="form-control form-control-sm pl-0  col-md-3">
                    <option value="" class="mx-0 px-0">Select Overall Band Score</option>
                    <option value="3.0" class="mx-0 px-0">3.0</option>
                    <option value="3.5" class="mx-0 px-0">3.5</option>
                    <option value="4.0" class="mx-0 px-0">4.0</option>
                    <option value="4.5" class="mx-0 px-0">4.5</option>
                    <option value="5.0" class="mx-0 px-0">5.0</option>
                    <option value="5.5" class="mx-0 px-0">5.5</option>
                    <option value="6.0" class="mx-0 px-0">6.0</option>
                    <option value="6.5" class="mx-0 px-0">6.5</option>
                    <option value="7.0" class="mx-0 px-0">7.0</option>
                    <option value="7.5" class="mx-0 px-0">7.5</option>
                    <option value="8.0" class="mx-0 px-0">8.0</option>
                    <option value="8.5" class="mx-0 px-0">8.5</option>
                    <option value="9.0" class="mx-0 px-0">9.0</option>
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div class="col-2">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Speaking Band Score </div>
            </div>
            <div class="col-10">
                <select id="speakingBandScore" name="speakingBandScore" v-model="examScore.IELTS.speakingBandScore" class="form-control form-control-sm pl-0  col-md-3">
                    <option value="" class="mx-0 px-0">Select Speaking Band Score</option>
                    <option value="3.0" class="mx-0 px-0">3.0</option>
                    <option value="3.5" class="mx-0 px-0">3.5</option>
                    <option value="4.0" class="mx-0 px-0">4.0</option>
                    <option value="4.5" class="mx-0 px-0">4.5</option>
                    <option value="5.0" class="mx-0 px-0">5.0</option>
                    <option value="5.5" class="mx-0 px-0">5.5</option>
                    <option value="6.0" class="mx-0 px-0">6.0</option>
                    <option value="6.5" class="mx-0 px-0">6.5</option>
                    <option value="7.0" class="mx-0 px-0">7.0</option>
                    <option value="7.5" class="mx-0 px-0">7.5</option>
                    <option value="8.0" class="mx-0 px-0">8.0</option>
                    <option value="8.5" class="mx-0 px-0">8.5</option>
                    <option value="9.0" class="mx-0 px-0">9.0</option>
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div class="col-2">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Writing Band Score </div>
            </div>
            <div class="col-10">
                <select id="writingBandScore" name="writingBandScore" v-model="examScore.IELTS.writingBandScore" class="form-control form-control-sm pl-0  col-md-3">
                    <option value="" class="mx-0 px-0">Select Writing Band Score</option>
                    <option value="3.0" class="mx-0 px-0">3.0</option>
                    <option value="3.5" class="mx-0 px-0">3.5</option>
                    <option value="4.0" class="mx-0 px-0">4.0</option>
                    <option value="4.5" class="mx-0 px-0">4.5</option>
                    <option value="5.0" class="mx-0 px-0">5.0</option>
                    <option value="5.5" class="mx-0 px-0">5.5</option>
                    <option value="6.0" class="mx-0 px-0">6.0</option>
                    <option value="6.5" class="mx-0 px-0">6.5</option>
                    <option value="7.0" class="mx-0 px-0">7.0</option>
                    <option value="7.5" class="mx-0 px-0">7.5</option>
                    <option value="8.0" class="mx-0 px-0">8.0</option>
                    <option value="8.5" class="mx-0 px-0">8.5</option>
                    <option value="9.0" class="mx-0 px-0">9.0</option>
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div class="col-2">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Reading Band Score </div>
            </div>
            <div class="col-10">
                <select id="readingBandScore" name="readingBandScore" v-model="examScore.IELTS.readingBandScore" class="form-control form-control-sm pl-0  col-md-3">
                    <option value="" class="mx-0 px-0">Select Reading Band Score</option>
                    <option value="3.0" class="mx-0 px-0">3.0</option>
                    <option value="3.5" class="mx-0 px-0">3.5</option>
                    <option value="4.0" class="mx-0 px-0">4.0</option>
                    <option value="4.5" class="mx-0 px-0">4.5</option>
                    <option value="5.0" class="mx-0 px-0">5.0</option>
                    <option value="5.5" class="mx-0 px-0">5.5</option>
                    <option value="6.0" class="mx-0 px-0">6.0</option>
                    <option value="6.5" class="mx-0 px-0">6.5</option>
                    <option value="7.0" class="mx-0 px-0">7.0</option>
                    <option value="7.5" class="mx-0 px-0">7.5</option>
                    <option value="8.0" class="mx-0 px-0">8.0</option>
                    <option value="8.5" class="mx-0 px-0">8.5</option>
                    <option value="9.0" class="mx-0 px-0">9.0</option>
                </select>
            </div>
        </div>
        <div class="row pt-2">
            <div class="col-2">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Listening Band Score </div>
            </div>
            <div class="col-10">
                <select id="listeningBandScore" name="listeningBandScore" v-model="examScore.IELTS.listeningBandScore" class="form-control form-control-sm pl-0  col-md-3">
                    <option value="" class="mx-0 px-0">Select Listening Band Score</option>
                    <option value="3.0" class="mx-0 px-0">3.0</option>
                    <option value="3.5" class="mx-0 px-0">3.5</option>
                    <option value="4.0" class="mx-0 px-0">4.0</option>
                    <option value="4.5" class="mx-0 px-0">4.5</option>
                    <option value="5.0" class="mx-0 px-0">5.0</option>
                    <option value="5.5" class="mx-0 px-0">5.5</option>
                    <option value="6.0" class="mx-0 px-0">6.0</option>
                    <option value="6.5" class="mx-0 px-0">6.5</option>
                    <option value="7.0" class="mx-0 px-0">7.0</option>
                    <option value="7.5" class="mx-0 px-0">7.5</option>
                    <option value="8.0" class="mx-0 px-0">8.0</option>
                    <option value="8.5" class="mx-0 px-0">8.5</option>
                    <option value="9.0" class="mx-0 px-0">9.0</option>
                </select>
            </div>
        </div> 
    </div>
    <!--[end]-->

   
    
  </div>
</template>

<script>

export default {
  name: "IELTScoreComponent",
 
  data() {
    return {                 
     
    };
  },
  props: {
    examScore: Object
  },
  methods: {
    getScores: function(index) {

    }
  },
  computed: {},
  updated: function () {
    
  },
  mounted: function () 
  {
    //console.log("IELTS scores component mounted");

  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>