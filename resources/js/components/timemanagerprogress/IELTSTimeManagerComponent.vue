<template>

    <div id="timeManager-IELTS" class="d-none">       
        
        <div class="row">
            <div :class="this.size.leftColumn">
                <div class="pl-2 small"> <span class="text-danger">&nbsp;</span> Course </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="course" :value="this.content.course "
                    disabled name="course"  :class="this.size.select +' form-control form-control-sm '">  
            </div>
        </div>

        <div class="minutes-entry row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Speaking</div>
            </div>
            <div :class="this.size.rightColumn">            
            <input @change="getTotalMinutes" id="speaking" name="speaking" :class="this.size.select +' form-control form-control-sm'" placeholder="分 minutes">

            </div>
        </div>

        <div class="minutes-entry row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Writing</div>
            </div>
            <div :class="this.size.rightColumn">
                <input @change="getTotalMinutes" id="writing" v-model="data.writing" :class="this.size.select +' form-control form-control-sm'" placeholder="分 minutes">
            </div>
        </div>

        <div class="minutes-entry row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Reading</div>
            </div>
            <div :class="this.size.rightColumn">
                <input @change="getTotalMinutes" id="reading" v-model="data.reading" :class="this.size.select +' form-control form-control-sm'" placeholder="分 minutes">                  
            </div>
        </div>

        <div class="minutes-entry row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Listening</div>
            </div>
            <div :class="this.size.rightColumn">
                <input @change="getTotalMinutes" id="listening" v-model="data.listening"  :class="this.size.select +' form-control form-control-sm'" placeholder="分 minutes">                  
            </div>
        </div>

        <div class="minutes-entry-total row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Total </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="total" disabled v-model="data.total" :class="this.size.select +' form-control form-control-sm '" placeholder="分 minutes">         
            </div>
        </div>   
        
    </div>

</template>

<script>

export default {
  name: "IELTScoreComponent",
 
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
    getTotalMinutes()
    {    
        
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