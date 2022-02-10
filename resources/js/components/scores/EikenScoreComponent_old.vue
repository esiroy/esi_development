<template>

  <div id="ScoresComponent" class="ScoresComponent">

    <!--[start] EikenScoreComponent- -->
    <div id="examination-score-EIKEN" class="section examScoreHolder">


        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Grade 5 Score </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="EIKEN-grade_5" name="EIKEN-grade_5" v-model="examScore.EIKEN.grade_5" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Grade 5 Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 851">{{ item - 1 }}</option>
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Grade 4 Score </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="EIKEN-grade_4" name="EIKEN-grade_4" v-model="examScore.EIKEN.grade_4" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Grade 4 Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 1001">{{ item - 1 }}</option>
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div class="col-5">                  
                <div class="strike">
                    <span class="text-secondary small">1st Stage</span>
                </div>                
            </div>
        </div>

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Grade 3 1st stage </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="EIKEN-grade_3_1st_stage" name="EIKEN-grade_3_1st_stage" v-model="examScore.EIKEN.grade_3_1st_stage" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Grade 3 1st Stage Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 1651">{{ item - 1 }}</option>
                </select>
            </div>
        </div>                

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Grade pre 2 1st Stage </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="EIKEN-grade_pre_2_1st_stage" name="EIKEN-grade_pre_2_1st_stage" v-model="examScore.EIKEN.grade_pre_2_1st_stage" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Grade pre 2 1st Stage Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 1801">{{ item - 1 }}</option>
                </select>
            </div>
        </div>        


        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Grade 2 1st stage </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="EIKEN-grade_2_1st_stage" name="EIKEN-grade_2_1st_stage" v-model="examScore.EIKEN.grade_2_1st_stage" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Grade 2 1st Stage Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 1951">{{ item - 1 }}</option>
                </select>
            </div>
        </div>           


        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Grade pre 1 1st Stage </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="EIKEN-grade_pre_1_1st_stage" name="EIKEN-grade_pre_1_1st_stage" v-model="examScore.EIKEN.grade_pre_1_1st_stage" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Grade pre 1 1st Stage Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 2251">{{ item - 1 }}</option>
                </select>
            </div>
        </div>        

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Grade 1 1st Stage </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="EIKEN-grade_1_1st_stage" name="EIKEN-grade_1_1st_stage" v-model="examScore.EIKEN.grade_1_1st_stage" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Grade 1 1st Stage Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 2251">{{ item - 1 }}</option>
                </select>
            </div>
        </div>     

        <div class="row pt-2">
            <div class="col-5">                  
                <div class="strike">
                    <span class="text-secondary small">2nd Stage</span>
                </div>                
            </div>
        </div>

        <!-- 2nd stage -->
        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Grade 3 2nd stage </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="EIKEN-grade_3_2nd_stage" name="EIKEN-grade_3_2nd_stage" v-model="examScore.EIKEN.grade_3_2nd_stage" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Grade 3 2nd Stage Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 551">{{ item - 1 }}</option>
                </select>
            </div>
        </div>                

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Grade pre 2 2nd Stage </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="EIKEN-grade_pre_2_2nd_stage" name="EIKEN-grade_pre_2_2nd_stage" v-model="examScore.EIKEN.grade_pre_2_2nd_stage" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Grade pre 2 2nd Stage Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 601">{{ item - 1 }}</option>
                </select>
            </div>
        </div>        


        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Grade 2 2nd stage </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="EIKEN-grade_2_2nd_stage" name="EIKEN-grade_2_2nd_stage" v-model="examScore.EIKEN.grade_2_2nd_stage" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Grade 2 2nd Stage Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 651">{{ item - 1 }}</option>
                </select>
            </div>
        </div>           


        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Grade pre 1 2nd Stage </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="EIKEN-grade_pre_1_2nd_stage" name="EIKEN-grade_pre_1_2nd_stage" v-model="examScore.EIKEN.grade_pre_1_2nd_stage" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Grade pre 1 2nd Stage Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 751">{{ item - 1 }}</option>
                </select>
            </div>
        </div>        

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Grade 1 2nd Stage </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="EIKEN-grade_1_2nd_stage" name="EIKEN-grade_1_2nd_stage" v-model="examScore.EIKEN.grade_1_2nd_stage" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Grade 1 2nd Stage Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 851">{{ item - 1 }}</option>
                </select>
            </div>
        </div>             

        <div class="row pt-2">
            <div :class="this.size.leftColumn">
                <div class="pl-2 small "> <span class="text-danger">*</span> Total Score </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="total" disabled name="EIKENtotal" v-model="examScore.EIKEN.total" :class="this.size.select +' form-control form-control-sm '"> 
            </div>
        </div>


    </div>
    <!--[end]-->

    
  </div>
</template>

<script>

export default {
  name: "EikenScoreComponent",
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
        let total =  this.$parent.$parent.$parent.$options.methods.getTotalScore('EIKEN');
        this.examScore.EIKEN.total  = total;

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
   // console.log("EIKEN- junior mounted")
  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }

    .strike {
        display: block;
        text-align: center;
        overflow: hidden;
        white-space: nowrap;
    
    font-size: 20px;
    }

    .strike > span {
        position: relative;
        display: inline-block;
    }
	
    .strike > span:before,
    .strike > span:after {
        content: "";
        position: absolute;
        top: 50%;
        width: 9999px;
        /* Here is the modification */
        border-top: 1px dotted #c9c9c9;
    }

    .strike > span:before {
        right: 100%;
        margin-right: 15px;
    }

    .strike > span:after {
        left: 100%;
        margin-left: 15px;
    }
</style>