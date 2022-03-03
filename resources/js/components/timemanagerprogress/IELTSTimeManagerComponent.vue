<template>

  <div id="timeManager-IELTS" class="d-none">       
        
    <div class="row">
        <div :class="this.size.leftColumn">
            <div class="pl-2 small"> <span class="text-danger">&nbsp;</span> Course </div>
        </div>
        <div :class="this.size.rightColumn">

            <input type="text" id="course" :value="this.content.timeManagerCourse "
                disabled name="course"  :class="this.size.select +' form-control form-control-sm '">  
        </div>
    </div>


    <!--[start] IELTS -->
    <div class="section timeManagerHolder">

        <div class="row  pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Speaking</div>
            </div>
            <div :class="this.size.rightColumn">            
               <input @change="getTotal" id="speaking" name="speaking" :class="this.size.select +' form-control form-control-sm'" placeholder="分 minutes">

            </div>
        </div>

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Writing</div>
            </div>
            <div :class="this.size.rightColumn">
                <input @change="getTotal" id="writing" name="writing" :class="this.size.select +' form-control form-control-sm'" placeholder="分 minutes">
            </div>
        </div>

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Reading</div>
            </div>
            <div :class="this.size.rightColumn">
                <input @change="getTotal" id="reading" name="reading" :class="this.size.select +' form-control form-control-sm'" placeholder="分 minutes">                  
            </div>
        </div>

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Listening</div>
            </div>
            <div :class="this.size.rightColumn">
                <input @change="getTotal" id="listening" name="listening"  :class="this.size.select +' form-control form-control-sm'" placeholder="分 minutes">                  
            </div>
        </div>

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Total </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="total" disabled name="total"  :class="this.size.select +' form-control form-control-sm '" placeholder="分 minutes">         
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
    content: Object,
    size: Object,
  },
  methods: {
    getTotal: function(total) 
    {

        let speakingBandScore = document.getElementById("speakingBandScore").value ;
        let writingBandScore = document.getElementById("writingBandScore").value ;
        let readingBandScore = document.getElementById("readingBandScore").value ;
        let listeningBandScore = document.getElementById("listeningBandScore").value ;
        if (speakingBandScore !== '' &&  writingBandScore !== '' && readingBandScore !== '' && listeningBandScore !== '') 
        {
            let sum =  parseFloat(speakingBandScore) + parseFloat(writingBandScore) +  parseFloat(readingBandScore) + parseFloat(listeningBandScore);
            let overall = sum / 4;
            this.examScore.IELTS.overallBandScore = parseFloat(overall);
        }

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
    //console.log("IELTS scores component mounted");
    //console.log(this.size)
  },
};

</script>

<style scoped>
    
</style>