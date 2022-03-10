<template>

    <div id="timeManager-TOEFL_Primary_Step_2" class="d-none">

        <div class="row pt-2">
            <div :class="this.size.leftColumn">
                <div class="pl-2 small"> <span class="text-danger">&nbsp;</span> Course </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="course" :value="this.content.courseTextValue" disabled name="course"  :class="this.size.select +' form-control form-control-sm '">  
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



        <div class="minutes-entry row pt-2">
            <div :class="this.size.leftColumn">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Reading  </div>                
            </div>
            <div :class="this.size.rightColumn">            
                <input v-on:keyup="getTotalMinutes" id="writing" name="writing" v-model="data.reading" :class="this.size.select +' form-control form-control-sm'">
                  
            </div>
        </div>


        <div class="row pt-2">
            <div :class="this.size.leftColumn">
                <div class="pl-2 small "> <span class="text-danger">*</span> Total  </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="total" disabled name="total" v-model="data.total" :class="this.size.select +' form-control form-control-sm '">                              
            </div>
        </div>
    
    
  </div>
</template>

<script>

export default {
  name: "TOEFL_Primary_Step_2ScoreComponent",
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
    size: Object,
    content: Object,
  },
  methods: {
    getTotalMinutes() {

        
        let total = parseFloat(this.data.reading) + parseFloat(this.data.listening)

        if (Number.isNaN(total)) 
        {            
            this.data.total = "";
            
        } else {
            this.data.total = total;
        }

        
        
    }
  },
  computed: {},
  updated: function () {
    
  },
  mounted: function () 
  {
   // console.log("TOEFL_Primary_Step_2 mounted")
  },
};

</script>

