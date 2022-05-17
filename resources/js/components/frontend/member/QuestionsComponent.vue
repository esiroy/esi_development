<template>

    <div class="container bg-light">
       

        <div v-if="this.categoryLoading == true" class="text-center">  

            <div class="pt-4 text-secondary">
                {{ "Loading, Please wait " }}
            </div>

            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>


        <div class="intro pt-4">
            <h4 class="text-primary">{{ this.category.name }}</h4>
            <div class="text-success">Instructions  : {{ this.category.instructions }}</div>
            <p class="text-info">Time Limit : {{ this.category.time_limit + " Minutes " }}</p>

            <div v-show="this.started == false">
                <button v-on:click="start()" class="btn btn-success" >
                    <i class="fa fa-list-alt" aria-hidden="true"></i>
                    Start Test 
                </button>
            </div>
        </div>


        <div class="mini-test" v-show="this.started == true && this.loading == true">

            <div class="pt-4 text-secondary">
                {{ "Please wait while we load your test" }}
            </div>

            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>

        </div>


       <div class="mini-test" v-show="this.started == true && this.loading == false">

            <div id="progress" class="mb-4" v-if="this.started == true && submitted == false">
                <b-progress :max="timerMax">
                <b-progress-bar :value="timerValue">
                    <span> <strong>{{ timerValue.toFixed(2) }} / {{ timerMax }} Minute(s)</strong></span>
                </b-progress-bar>
                </b-progress>
            </div>
         
            <div v-if="multiple == true && submitted == false">

                <div id="questions" 
                    class="row" 
                    v-for="(question, questionIndex) in this.questions"
                    :key="questionIndex"> 

                    <div class="col-12">
                        <span class="font-weight-bold mb-3">{{ (questionIndex + 1)  +"." }} {{ question.question }}</span>

                        <b-form-group>
                            <b-form-radio 
                                :name="'question_'+ question.id +''"
                                v-for="(choice, choiceIndex) in question.choices" :key="choiceIndex"
                                v-on:change="updateSelected(question, choice)"
                                v-bind:value="choice.id"

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

                        <div :class="'choice_container'" 
                        v-for="(choice, choiceIndex) in questionViewer.choices" 
                        :key="choiceIndex">

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


            <div class="answer" v-if="submitted == true && loading == false">  

                <h4 class="text-primary mb-1"> <strong> Your Test Result </strong></h4>

                <h5 class="mb-4 text-dark">                       
                    Your have {{ totalCorrectAnswers }} correct answers out of {{ totalQuestions}}                  
                </h5>                


                <h4 class="text-primary mb-1"> <strong> Test Summary </strong></h4>

                <div class="summary">


                    <div v-for="(result, resultIndex) in results" :key="'result_'+resultIndex" class="mb-3">
                        <div class="font-weight-bold">
                            {{ resultIndex +"." }}  {{ result.question }} 
                        </div>
                        <div>Correct Answer: {{ result.correct_answer }} </div>
                        <div v-if="result.your_answer === null">
                            <i class="fa fa-question text-secondary" aria-hidden="true"></i>  {{ "No Answer" }}
                        </div>
                        <div v-else>
                            <div class="text-primary">Your Answer: {{ result.your_answer }} </div>                                       
                            <div v-if="result.is_correct == true" class="text-success font-weight-bold"> <i class="fa fa-check" aria-hidden="true"></i> Correct </div>
                            <div v-else-if="result.is_correct == false" class="text-danger font-weight-bold"> <i class="fa fa-times" aria-hidden="true"></i> Incorrect </div>
                        </div>
                    </div>
                </div>



                <h4 class="text-primary mb-1"> <strong> Your Test Result </strong></h4>

                <h5 class="mb-4 text-dark">                       
                    Your have {{ totalCorrectAnswers }} correct answers out of {{ totalQuestions}}                  
                </h5>                
              

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

                miniTestID: null,


                categoryLoading: true,
                loading: true,

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

                choiceAnswers: [],

                results: [],            
            }
        },
         methods: 
         {

            start() {
                this.started = true;
                this.loading = false;

                this.startTimer();
                this.recordStartTime();
            },

        
            async getList() 
            {    

                this.categoryLoading = true;        

                let url = "/api/getQuestions?api_token=" + this.api_token;
                let data = { 
                    'category_id': this.category.id
                };


                await this.getURL(url, data).then(response => {

                    if (response.data.success == true) 
                    {
                        this.questions = response.data.questions;
                        this.choices = response.data.choices;
                        this.questionsLength = Object.keys(this.questions).length    
                        this.questionViewer = this.questions[this.questionIndex];

                    } else {
                        alert (response.data.message)
                    }

                }).finally((url) => {      
                                
                    this.categoryLoading = false;        

                });
            },

            startTimer() {
                this.seconds = 60;
                this.secondsHand = 60;
                this.myIntervalTimer = setInterval(this.checkMinute, this.timerSpeed);
            },
            checkMinute() 
            {

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
                //if (this.selected_choice == null) {
                   // alert ("no choice selected");
                    //return null;
                //} else {
                    this.submitAnswers();
                //}          
            },
            getAnswers() 
            {
                this.choiceAnswers = [];

                this.questions.forEach((question, index) => 
                {
                    let selected_element = document.querySelector('input[name=question_'+question.id +']:checked');  
                    
                    if (selected_element) 
                    {

                        let choices     = question.choices;
                        let choiceText  = "";

                        choices.forEach((choice, choicesIndex) => 
                        {

                            if (choice.id == selected_element.value) {
                                choiceText = choice.choice;
                            }                            
                        });

                        this.choiceAnswers.push({
                            question_id             : question.id,
                            question_text           : question.question,
                            choices                 : question.choices,
                            selected_choice_id      : selected_element.value,
                            selected_choice_text    : choiceText,
                        });

                    } else {

                        this.choiceAnswers.push({
                            question_id         : question.id,
                            question_text      : question.question,
                            choices             : question.choices,
                            selected_choice_id  : null,
                            selected_choice_text: null,
                        });

                     
                    }

                });


            },
            checkMultiSubmitAnswers() 
            {

                this.loading = true;
                this.getAnswers();
                
               // if (this.questionsLength == this.answers.length) {

                this.submitAnswers();
                  
               // } else {
                  //  alert ("please answer all");
              //  }
            },
            async recordStartTime() 
            {
                //add this with null values to record exams
                this.getAnswers(); 
                this.loading = true;
            
                let url = "/api/addAnswerStartTime?api_token=" + this.api_token;
                let data  = {
                    'member_id': this.memberinfo.id,
                    'category_id': this.category.id,    
                    'answers' : this.choiceAnswers,               
                }

                await this.getURL(url, data).then(response => 
                {
                    if (response.data.success == true)
                    {

                        this.miniTestID = response.data.id;
                    }

                }).finally(() => {                  

         

                    this.loading = false;
                                
                });  


            },
            async submitAnswers() 
            {

                this.loading = true;

                let url = "/api/postAnswers?api_token=" + this.api_token;
                let data  = {

                    'miniTestID': this.miniTestID, //we need to just update

                    'member_id': this.memberinfo.id,
                    'category_id': this.category.id,
                    'answers' : this.choiceAnswers,
                }
                
                await this.getURL(url, data).then(response => 
                {

                    if (response.data.success == true)
                     {
                        this.submitted = true;
                        this.results = response.data.results;

                        this.totalCorrectAnswers  = response.data.total_correct_answers;
                        this.totalQuestions         = response.data.total_questions;


                    } else {
                    


                        alert (response.data.message)
                
                    
                    }

                }).finally(() => {                  

                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    this.loading = false;
                                
                });            
            },            
              
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