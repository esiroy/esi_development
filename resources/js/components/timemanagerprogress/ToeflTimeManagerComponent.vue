<template>
    <!--[start] TOEFL-iBT -->
    <div id="timeManager-TOEFL" class="d-none">

        <div class="row">
            <div :class="this.size.leftColumn">
                <div class="pl-2 small"> <span class="text-danger">&nbsp;</span> Course </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="course" :value="this.content.course "
                    disabled name="course"  :class="this.size.select +' form-control form-control-sm '">  
            </div>
        </div>



        <div class="minutes-entry pt-2 row">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small  mb-2"> <span class="text-danger">*</span> Speaking</div>             
            </div>
            <div :class="this.size.rightColumn">            
                <input v-on:keyup="getTotalMinutes" id="speaking" name="speaking" v-model="data.speaking" :class="this.size.select +' form-control form-control-sm'">
            </div>
        </div>

        <div class="minutes-entry row pt-2">
            <div :class="this.size.leftColumn">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Writing</div>                
            </div>
            <div :class="this.size.rightColumn">            
                <input v-on:keyup="getTotalMinutes" id="writing" name="writing" v-model="data.writing" :class="this.size.select +' form-control form-control-sm'">
                   
            </div>
        </div>

        <div class="minutes-entry row pt-2">
            <div :class="this.size.leftColumn">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Reading</div>                
            </div>
            <div :class="this.size.rightColumn">
                <input v-on:keyup="getTotalMinutes" id="reading" name="reading" v-model="data.reading" :class="this.size.select +' form-control form-control-sm'">                 
            </div>
        </div>

        <div class="minutes-entry row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Listening</div>                
            </div>
            <div :class="this.size.rightColumn">
                <input v-on:keyup="getTotalMinutes" id="listening" name="listening" v-model="data.listening" :class="this.size.select +' form-control form-control-sm'">
            </div>
        </div>


        <div class="minutes-entry-total row pt-2">
            <div :class="this.size.leftColumn">
                <div class="pl-2 small "> <span class="text-danger">*</span> Total Minutes </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="total" disabled name="TOEFLtotal" v-model="data.total" :class="this.size.select +' form-control form-control-sm '"> 
            </div>
        </div>


    </div>
    <!--[end]-->
 
</template>

<script>

export default {
  name: "ToeflScoreComponent",
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
    size: Object,    
    content: Object,
  },
  methods: {
    getTotalMinutes() {
        data.total = data.speaking + data.reading + data.writing + data.listening;
    }
  },
  computed: {},
  updated: function () {
    
  },
  mounted: function () 
  {
    //console.log("toefl added")
  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>