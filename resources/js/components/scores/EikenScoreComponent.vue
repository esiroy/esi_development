<template>

  <div id="ScoresComponent" class="ScoresComponent">

    <!--[start] EikenScoreComponent- -->
    <div id="examination-score-EIKEN" class="section examScoreHolder">

        <div class="row pt-2">
            <div class="col-4">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Level of Examination </div>
            </div>                   
            <div class="col-8">
                <select id="gradeLevel" name="gradeLevel" v-model="gradeLevel" @change="handleChangeGradeLevel($event)" class="form-control form-control-sm pl-0  col-md-10">
                    <option value="" class="mx-0 px-0">Select Grade Level</option>
                    <option value="5" class="mx-0 px-0">Grade 5</option>
                    <option value="4" class="mx-0 px-0">Grade 4</option>
                    <option value="3" class="mx-0 px-0">Grade 3</option>
                    <option value="pre_2" class="mx-0 px-0">Grade Pre 2</option>
                    <option value="2" class="mx-0 px-0">Grade 2</option>
                    <option value="pre_1" class="mx-0 px-0">Grade Pre 1</option> 
                    <option value="1" class="mx-0 px-0">Grade 1</option> 
                </select>       
            </div>                     
        </div>


        <div id="grade_5" class="row pt-2 grade_level_container">
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


        <div id="grade_4" class="row pt-2 grade_level_container">
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



        <div id="stage_1_separator" class="row pt-2">
            <div class="col-12">                  
                <div class="strike">
                    <span class="text-secondary small">1st Stage</span>
                </div>                
            </div>
        </div>



        <div id="grade_3_stage_1" class="row pt-2 grade_level_container">
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


        <div id="grade_pre_2_stage_1" class="row pt-2 grade_level_container">
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


        <div id="grade_2_stage_1" class="row pt-2 grade_level_container">
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


        <div id="grade_pre_1_stage_1" class="row pt-2 grade_level_container">
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

        <div id="grade_1_stage_1" class="row pt-2 grade_level_container">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Grade 1 1st Stage </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="EIKEN-grade_1_1st_stage" name="EIKEN-grade_1_1st_stage" v-model="examScore.EIKEN.grade_1_1st_stage" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Grade 1 1st Stage Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 2551">{{ item - 1 }}</option>
                </select>
            </div>
        </div>     

        <div id="stage_2_separator" class="row pt-2">
            <div class="col-12">                  
                <div class="strike">
                    <span class="text-secondary small">2nd Stage</span>
                </div>                
            </div>
        </div>

        <!-- 2nd stage -->
        <div id="grade_3_stage_2" class="row pt-2 grade_level_container">
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

        <div id="grade_pre_2_stage_2" class="row pt-2 grade_level_container">
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


        <div id="grade_2_stage_2" class="row pt-2 grade_level_container">
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


        <div id="grade_pre_1_stage_2" class="row pt-2 grade_level_container">
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

        <div id="grade_1_stage_2" class="row pt-2 grade_level_container">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Grade 1 2nd Stage </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="EIKEN-grade_1_2nd_stage" name="EIKEN-grade_1_2nd_stage" v-model="examScore.EIKEN.grade_1_2nd_stage" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Grade 1 2nd Stage Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 851">{{ item - 1 }}</option>
                </select>
            </div>
        </div>             

        <div class="row pt-2 total">
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

export default 
{
    name: "EikenScoreComponent",
    data() {
        return {                 
            gradeLevel: "",
            total: "",
        }
    },
    props: {
        examScore: Object,
        size: Object,
    },
    methods: 
    {
        hideFieldsContainer() 
        {
            document.querySelectorAll('.grade_level_container').forEach(function(element) {
                element.style.display = 'none';
            });  

        },
        hideField(id) 
        {
            let element =  document.getElementById(id);
            if (element) {
                element.style.display = 'none';
            }
        },    
        showField(id) {         
            let element =  document.getElementById(id);
            if (element) {
                element.style.display = 'flex';
            }
        },
        resetField(id) {           
            this.examScore.EIKEN[id] = "";                  
        },
        getValue(id) {
            let element =  document.getElementById(id);
            if (element) {
                return element.value;
            }
        },
        handleChangeGradeLevel() 
        {
            //change main exam level
            this.$parent.$parent.$parent.examLevel = this.gradeLevel;
            this.total = "";
            this.examScore.EIKEN.total  = this.total;
            this.hideFieldsContainer();
            if (this.gradeLevel === "" || this.gradeLevel === null || this.gradeLevel == null) {
                //hide separators
                this.hideField('stage_1_separator');
                this.hideField('stage_2_separator'); 
            } else {
            
                if (this.gradeLevel > 3) 
                { 
                    this.showField('grade_'+ this.gradeLevel);
                    this.resetField("grade_" + this.gradeLevel);  

                    this.hideField('stage_1_separator');
                    this.hideField('stage_2_separator');                    
                }
                else {
                    this.showField('grade_'+ this.gradeLevel + "_stage_1");
                    this.showField('grade_'+ this.gradeLevel + "_stage_2");

                    this.resetField("grade_" + this.gradeLevel + "_1st_stage");  
                    this.resetField("grade_" + this.gradeLevel + "_2nd_stage");  

                    //show separators
                    this.showField('stage_1_separator');
                    this.showField('stage_2_separator');             
                }

            }
        },
        getTotal() 
        {
            if (this.gradeLevel > 3) 
            {
            
                this.total = this.getValue('EIKEN-grade_'+ this.gradeLevel);        
                this.examScore.EIKEN.total  = this.total;

            } else {
            
                let s1 = this.getValue('EIKEN-grade_'+ this.gradeLevel + "_1st_stage");        
                let s2 = this.getValue('EIKEN-grade_'+ this.gradeLevel + "_2nd_stage");

                if (s1 !== '' && s2 !== '') {
                    this.total = parseInt(s1) + parseInt(s2);
                } else if (s1 !== '' && s2 == '') {
                    this.total = parseInt(s1);
                } else {
                    this.total = parseInt(s1) + parseInt(s2);
                }

                if (parseInt(this.total) >= 1) {
                    this.examScore.EIKEN.total  = parseInt(this.total);
                }
            }
            //let total =  this.$parent.$parent.$parent.$options.methods.getEikenTotalScore('EIKEN');
 
            if (this.$parent.$parent.$parent.submitted === true) {
                // this.$parent.$parent.$parent.$options.methods.highlightExamElement();
            }
                    
        }
    },
    computed: {},
    updated: function () {
    
    },
    mounted: function () 
    {
        // console.log("EIKEN mounted")
        //hide separators
        this.hideField('stage_1_separator');
        this.hideField('stage_2_separator');   

        this.gradeLevel = this.$parent.$parent.$parent.examLevel;
        this.handleChangeGradeLevel();
        this.$nextTick(() => 
        {
            if (this.$parent.$parent.$parent.examType == "EIKEN") {
                this.examScore.EIKEN = this.examScore['EIKEN_Grade_'+ this.$parent.$parent.$parent.examLevel]
            }         
        });       
    }
};
</script>
<style scoped>
    .scores-container {
        width: 100%
    }
    
    .grade_level_container {
       display: none;
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