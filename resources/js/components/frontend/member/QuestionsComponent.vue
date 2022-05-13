<template>

    <div class="container bg-light">

        <div class="intro p-4" v-show="this.started == false">
            <p>Test Category : {{ this.category.name }}</p>
            <p>Instructions  : {{ this.category.instructions }}</p>    
            <p>Time Limit : {{ this.category.time_limit + " Minutes " }}  </p>
            <button v-on:click="start()" class="btn btn-success"> Start Test </button>   
        </div>

       <div class="mini-test pt-3" v-show="this.started == true">

            <div id="progress" v-if="this.started == true && submitted == false">
                <b-progress :max="timerMax">
                <b-progress-bar :value="timerValue">
                    <span> <strong>{{ timerValue.toFixed(2) }} / {{ timerMax }} Minute(s)</strong></span>
                </b-progress-bar>
                </b-progress>

            </div>
         
            <div v-if="multiple == true && submitted == false">

                <div id="questions" class="row" v-for="(question, questionIndex) in this.questions" :key="questionIndex">                            
                    <div class="col-12">

                        <span class="font-weight-bold mb-3">{{ (questionIndex+1)  +"." }} {{ question.question }}</span>

                        <b-form-group>
                            <b-form-radio 
                                :name="''+ question.id +''"
                                v-for="(choice, choiceIndex) in question.choices" :key="choiceIndex"
                                v-on:change="updateSelected(question, choice)"
                                v-bind:value="{ id: choice.id, choice: choice.choice }"

                                >{{ choice.choice }}</b-form-radio>                     
                        </b-form-group>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-success" v-on:click="checkMultiSubmitAnswers()"> Submit Answers </button>   
                </div>            
                
            </div>


            <div class="row" v-if="multiple == false && submitted == false">

                <div id="question_container" class="col-7">
                    <div id="questions">
                        <div class="py-2">                           
                            <span class="font-weight-bold mb-3">{{ (count)  +"." }} {{ questionViewer.question }}</span>                          
                        </div>

                        <div :class="'choice_container'" v-for="(choice, choiceIndex) in questionViewer.choices" :key="choiceIndex">
                            <input type="radio"
                                v-model="selected_choice" 
                                :name="choice.id" 
                                :id="choice.id"
                                @click="updateSelected(questionViewer, choice)"                            
                                v-bind:value="{ id: choice.id, choice: choice.choice }">

                                <label :for="choice.choice">{{ choice.choice }}</label><br>
                        </div>   

                        <div class="mt-4">
                            <button class="btn btn-primary" v-on:click="getPrevQuestion()" v-show="(count - 1) >= 1"> 
                                Previous <!--{{ count - 1 }}--> 
                            </button>     
                            <button class="btn btn-primary" v-on:click="getNextQuestion()" v-show="count < this.questionsLength">
                                Next <!--{{ count }}--> 
                             </button>

                            <button class="btn btn-success" v-on:click="checkSubmitAnswers()" v-show="count >= this.questionsLength"> Submit Answers </button>   
                        </div>
                    </div>
                </div>
            </div>


            <div class="answer p-4" v-if="submitted == true">  

                <div class="text-primary mb-2"> 
                    <strong> Your Test Result </strong>
                </div>

                 <div v-for="(question, questionIndex) in questions" :key="questionIndex" class="mb-3">
                    <div class="font-weight-bold">{{ (questionIndex+ 1) + "." }} {{ question.question }}</div>
                    <div v-if="results[question.id]">                       
                        <div>Your Answer: {{ results[question.id].your_answer }} </div>
                        <div>Correct Answer: {{ results[question.id].correct_answer }} </div>
                        <div v-if="results[question.id].is_correct == true" class="text-success font-weight-bold"> <i class="fa fa-check" aria-hidden="true"></i> Correct </div>
                        <div v-else-if="results[question.id].is_correct == false" class="text-danger font-weight-bold"> <i class="fa fa-times" aria-hidden="true"></i> Incorrect </div>
                    </div>
                    <div v-else>
                        No answer
                    </div>

                 </div>


                <!--
                <div v-for="(result, resultIndex) in results" :key="resultIndex" class="mb-3">
                    <div class="font-weight-bold">
                        {{ resultIndex +"." }} 
                        Question {{ result.question }} 
                    </div>
                    <div>Your Answer: {{ result.your_answer }} </div>
                    <div>Correct Answer: {{ result.correct_answer }} </div>
                    
                    <div v-if="result.is_correct == true" class="text-success font-weight-bold"> <i class="fa fa-check" aria-hidden="true"></i> Correct </div>
                    <div v-else-if="result.is_correct == false" class="text-danger font-weight-bold"> <i class="fa fa-times" aria-hidden="true"></i> Incorrect </div>
                </div>
                -->

            </div>

        </div>

    </div>

</template>

<script>
    import {Helpers} from "../../helpers/helpers.js";
    import HelpersComponents from "../../helpers/helper_components.js";

    name: "questions-component";
    export default {   
        name: "member-time-manager-component",
        components: {    
            HelpersComponents
        }, 
        props: {
            category: Object,
            memberinfo: Object,
            csrf_token: String,		
            api_token: String,
            multiple: Boolean,
        },        
        data() {
            return {

                started: false,
                submitted: false,

                myIntervalTimer: null,

                timerSpeed: 1000,

                timer: 0,
                timerValue: 0,
                timerMax: 0,
                seconds: 0,
                secondsHand: 60,


                count: 1,
                questionIndex: 0,
                questionViewer: "",

                //query
                questionsLength: 0,
                questions: [],
                choices: [],

                selected_choice: null,
                //
                multiple_selected_choice: [],


                //member answers list
                answers: [],

                orderedAnswers: [],

                results: [],            
            }
        },
         methods: {

            start() {
                this.started = true;

                this.startTimer();
            },
            getChoices(questionID) 
            {
                this.choices = this.choices[questionID]
            },
            startTimer() {
                this.seconds = 60;
                this.secondsHand = 60;
                this.myIntervalTimer = setInterval(this.checkMinute, this.timerSpeed)
            },
            checkMinute() {

                this.seconds++;
                this.secondsHand--;           

                let minutesTaken = parseInt(this.seconds / 60);
                let remainingMinutes = this.timerMax - minutesTaken;

                if (minutesTaken == 0 && this.secondsHand == 60) {
                    remainingMinutes --;
                }
                else if (this.secondsHand == 0) 
                {      
                    this.secondsHand = 60;                            
                }


                //Flash Time logic
                if (this.secondsHand == 60) 
                {                
                    //reached minimum minute with zero seconds
                    let minuteMin = remainingMinutes + 1;
                    let timer = parseFloat(minuteMin + ".00");
                    this.flashTimer(timer);
                    this.$forceUpdate();  
                }
                else if (this.secondsHand < 10) 
                {
                    let timer = parseFloat(remainingMinutes + ".0" + this.secondsHand);
                    this.flashTimer(timer);
                    this.$forceUpdate();    

                }  else {
                    let timer = parseFloat(remainingMinutes + "." + this.secondsHand);
                    this.flashTimer(timer);
                    this.$forceUpdate();    
                }


                            
            },
            flashTimer(timer) 
            {

                this.timerValue = timer;

                console.log(this.timerValue);

                if ( parseFloat(this.timerValue) <= 0) {
                    clearInterval(this.myIntervalTimer);                
                    this.submitAnswers();
                }
            
            },
            updateSelected(questionViewer, choice) 
            {
                const index = this.answers.findIndex((obj) => obj.question_id === questionViewer.id);

                if (index === -1) {
                    this.answers.push({
                        question_id: questionViewer.id,
                        question:    questionViewer.question,
                        choice_id:  choice.id,                            
                        choice:    choice.choice,    
                    });
                } else {

                    this.answers[index] = {
                        question_id: questionViewer.id,
                        question:    questionViewer.question,
                        choice_id:  choice.id,                            
                        choice:    choice.choice,                            
                    }
                }


                let QuestionIndex = this.questions.findIndex((obj) => obj.id === questionViewer.id);

                this.orderedAnswers[QuestionIndex] = {                
                    question_id: questionViewer.id,
                    question:    questionViewer.question,
                    choice_id:  choice.id,                            
                    choice:    choice.choice,                    
                }

                this.$forceUpdate();
            },
            autoSelect() {            
                const index = this.answers.findIndex((obj) => {
                    if (obj.question_id === this.questionViewer.id) {
                        document.getElementById(obj.choice_id).checked = true;
                        this.selected_choice = { id: obj.choice_id, choice: obj.choice }
                    }
                });
                return index;
            },
            getPrevQuestion() {
                this.count--;
                this.questionIndex--;

                //show the current question
                this.questionViewer = this.questions[this.questionIndex];

                this.$nextTick(() => {
                    let isElementFound = document.getElementById(this.questionViewer.id);
                    this.autoSelect();
                    if (isElementFound) {
                        document.getElementById(this.questionViewer.id).checked = false;
                    }                    
                });
            },
            getNextQuestion() 
            {               
                if (this.selected_choice == null) {
                    alert ("no choice selected");
                    return null;
                } else {

                    const index = this.answers.findIndex((obj) => obj.question_id === this.questionViewer.id);
                    if (index === -1) {
                        this.answers.push({
                            question_id: this.questionViewer.id,
                            question:    this.questionViewer.question,
                            choice_id:  this.selected_choice.id,
                            choice:    this.selected_choice.choice
                        });
                    } else {
                        this.answers[index] = {
                            question_id: this.questionViewer.id,
                            question:    this.questionViewer.question,
                            choice_id:  this.selected_choice.id,                            
                            choice:    this.selected_choice.choice,                            
                        }
                    }
                }

                //increment show the next question
                this.count++;
                this.questionIndex++;

                this.questionViewer = this.questions[this.questionIndex];

                this.$nextTick(() => {
                     this.selected_choice = null;
                     this.autoSelect();
                        let isElementFound = document.getElementById(this.questionViewer.id);
                        if (isElementFound) {
                            document.getElementById(this.questionViewer.id).checked = false;
                        }
                });                
            },
            async getURL(url, data) {          
                return Helpers.getURL(url, data);
            },
            checkSubmitAnswers() {
                if (this.selected_choice == null) {
                    alert ("no choice selected");
                    return null;
                } else {
                    this.submitAnswers();
                }          
            },
            checkMultiSubmitAnswers() {

                if (this.questionsLength == this.answers.length) {

                    this.submitAnswers();
                  
                } else {
                    alert ("please answer all");
                }

            },
            async submitAnswers() 
            {
                let url = "/api/postAnswers?api_token=" + this.api_token;
                let data  = {
                    'category_id': this.category.id,
                    //'answers' : this.answers,
                    'answers' : this.orderedAnswers,
                    'member_id': this.memberinfo.id,
                }
                
                await this.getURL(url, data).then(response => {

                    if (response.data.success == true) {

                        this.submitted = true;

                        this.results = response.data.results;
                    }

                }).finally(() => {                  

                                
                });            
            },            
            async getList() 
            {            

                let url = "/api/getQuestions?api_token=" + this.api_token;
                let data = { 
                    'category_id': this.category.id
                };


                await this.getURL(url, data).then(response => {

                    if (response.data.success == true) {
                        this.questions = response.data.questions;
                        this.choices = response.data.choices;
                        this.questionsLength = Object.keys(this.questions).length                    
                        

                        this.questionViewer = this.questions[this.questionIndex];

                    } else {
                        alert (response.data.message)
                    }

                }).finally((url) => {      
                                
                });
            }        
        },        
        mounted () 
        {          
            this.getList();

            this.timerMax = this.category.time_limit;
            this.timerValue = this.category.time_limit;
          
        }
    }
</script>

<style scoped>

    html {
        scroll-behavior: smooth;
    }
</style>