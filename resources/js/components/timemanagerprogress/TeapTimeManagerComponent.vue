<template>
    <!--[start] TEAP- -->
    <div id="timeManager-TEAP" class="d-none">

        <div class="row">
            <div :class="this.size.leftColumn">
                <div class="pl-2 small"> <span class="text-danger">&nbsp;</span> Course </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="course" :value="this.content.courseTextValue "
                    disabled name="course"  :class="this.size.select +' form-control form-control-sm '">  
            </div>
        </div>


        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small  mb-2"> <span class="text-danger">*</span> Speaking  </div>             
            </div>
            <div :class="this.size.rightColumn">            
                <select @change="getTotal" id="TEAP-speaking" name="speaking" v-model="data.speaking" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Speaking </option>
                    <option :value="item -1" :key="item -1" class="mx-0 px-0" v-for="item in 31">{{ item -1 }}</option>                     
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Writing  </div>                
            </div>
            <div :class="this.size.rightColumn">            
                <select @change="getTotal" id="TEAP-writing" name="writing" v-model="data.writing" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Writing </option>
                    <option :value="item -1" :key="item -1" class="mx-0 px-0" v-for="item in 31">{{ item -1 }}</option>                     
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Reading  </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="TEAP-reading" name="reading" v-model="data.reading" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Reading </option>
                    <option :value="item -1" :key="item -1" class="mx-0 px-0" v-for="item in 31">{{ item -1 }}</option>                     
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Listening  </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="TEAP-listening" name="listening" v-model="data.listening" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Listening </option>
                    <option :value="item -1" :key="item -1" class="mx-0 px-0" v-for="item in 31">{{ item -1 }}</option>                     
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div :class="this.size.leftColumn">
                <div class="pl-2 small "> <span class="text-danger">*</span> Total  </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="total" disabled name="TEAP-total" v-model="data.total" :class="this.size.select +' form-control form-control-sm '">  
            </div>
        </div>
    </div>
    <!--[end]-->
</template>

<script>

export default 
{
    name: "TeapTimeManagerComponent",
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
        getTotal() {
            let total =  this.$parent.$parent.$parent.$options.methods.getTotal('TEAP');
            this.exam.TEAP.total  = total;

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
    .s-container {
        width: 100%
    }
</style>