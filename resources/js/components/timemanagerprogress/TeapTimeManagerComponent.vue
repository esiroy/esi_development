<template>
    <!--[start] TEAP- -->
    <div id="timeManager-TEAP" class="d-none">

        <div class="message"></div> 

        <form name="form-timemanager-TEAP" id="form-timemanager-TEAP">

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
                    <div class="pl-2 small"> <span class="text-danger">&nbsp;</span> Date </div>
                </div>
                <div :class="this.size.rightColumn">
        
                    <datepicker id="startDate" 
                        name="startDate"                                          
                        v-model="date"
                        :value="date"
                        :format="dateFormatter"
                        :placeholder="'Select Date'"
                        :input-class="[this.size.select +' form-control form-control-sm bg-white',  { 'is-invalid' : submitted  && $v.date.$error }] "
                        :disabledDates="disabledDates"
                        :language="ja"
                    ></datepicker>   

                    <div v-if="submitted && !$v.date.required" class="invalid-feedback">
                        date required
                    </div>
                                                
                </div>
            </div>


            <div class="minutes-entry row pt-2">
                <div :class="this.size.leftColumn">                       
                    <div class="pl-2 small  mb-2"> <span class="text-danger">*</span> Speaking  </div>             
                </div>
                <div :class="this.size.rightColumn">            
                    <input v-on:keyup="getTotalMinutes" placeholder="分 minutes" id="TEAP-speaking" name="speaking" v-model="data.speaking" :class="this.size.select +' form-control form-control-sm'">
                      
                </div>
            </div>

            <div class="minutes-entry row pt-2">
                <div :class="this.size.leftColumn">                                       
                    <div class="pl-2 small "> <span class="text-danger">*</span> Writing  </div>                
                </div>
                <div :class="this.size.rightColumn">            
                    <input v-on:keyup="getTotalMinutes" placeholder="分 minutes" id="TEAP-writing" name="writing" v-model="data.writing" :class="this.size.select +' form-control form-control-sm'">
                    
                </div>
            </div>

            <div class="minutes-entry row pt-2">
                <div :class="this.size.leftColumn">                                       
                    <div class="pl-2 small "> <span class="text-danger">*</span> Reading  </div>                
                </div>
                <div :class="this.size.rightColumn">
                    <input v-on:keyup="getTotalMinutes" placeholder="分 minutes" id="TEAP-reading" name="reading" v-model="data.reading" :class="this.size.select +' form-control form-control-sm'">
                       
                </div>
            </div>

            <div class="minutes-entry  row pt-2">
                <div :class="this.size.leftColumn">                       
                    <div class="pl-2 small "> <span class="text-danger">*</span> Listening  </div>                
                </div>
                <div :class="this.size.rightColumn">
                    <input v-on:keyup="getTotalMinutes" placeholder="分 minutes" id="TEAP-listening" name="listening" v-model="data.listening" :class="this.size.select +' form-control form-control-sm'">
                       
                </div>
            </div>


            <div class="row pt-2">
                <div :class="this.size.leftColumn">
                    <div class="pl-2 small "> <span class="text-danger">*</span> Total Minutes  </div>
                </div>
                <div :class="this.size.rightColumn">
                    <input type="text" id="total" disabled name="TEAP-total" v-model="data.total" :class="this.size.select +' form-control form-control-sm '">  
                </div>
            </div>

        </form>

    </div>
    <!--[end]-->
</template>

<script>

import * as Moment from 'moment';
import Datepicker from 'vuejs-datepicker';
import {en, ja} from 'vuejs-datepicker/dist/locale'; 

export default 
{
    name: "TeapTimeManagerComponent",
    data() {
        return {       

            ja: ja,
            en: en,
            submitted: false,
            date: "",
            disabledDates: {
                from: new Date(Date.now() + 8640000)
            },
            data: {        
                speaking: "",
                reading: "",
                writing: "",
                listening: "",
                total: ""
            }  
        };
    },
    components: {    
        Datepicker
    },
    props: {
        content: Object,
        size: Object,
        type: String,        
    },
    methods: {
        getDate() {
            return this.date;
        },
        getMinutesData() {
            return this.data;
        },
        dateFormatter(date) 
        {
            let fdate = Moment(date).format('YYYY年 MM月 D日');  
            return fdate;
        },    
        getTotalMinutes()
        {
            this.data.total = this.$parent.$options.methods.getTotalMinutes(this.content.course);
        }
    },
    computed: {},
    updated: function () {

    },
    mounted: function () 
    {
        if (this.type == 'update') {
            this.date = this.content.date;
            Object.keys(this.content).forEach(key => {          
                this.data[key] = this.content[key];
            });
        }
    },
};

</script>

<style scoped>
    .s-container {
        width: 100%
    }
</style>