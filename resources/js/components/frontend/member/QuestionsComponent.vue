<template>

    <div class="container" v-if="questions.length >= 1">      

        <div v-if="this.categoryLoading == true" class="text-center">  
            <div class="pt-4 text-secondary">
                {{ "Loading, Please wait " }}
            </div>

            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
       

        <div v-if="this.categoryLoading == false"  class="intro py-4">
            <h4 class="text-primary">{{ this.category.name }}</h4>
            <div class="text-success">Instructions  : {{ this.category.instructions }}</div>
            <p class="text-info">Time Limit : {{ this.category.time_limit + " Minutes " }}</p>

            <!-- ADD A MEMBER POINT INFORMATION -->
            <div id="point-information" class="border p-4 mb-4" v-show="this.started == false">
                <span class="font-weight-bold">
                  
                    <span v-if="this.freeMiniTest >= 1">  Note:  You have  {{ this.freeMiniTest }}  Free Mini Test Left,  You will be deducted 1 free point if you proceed </span>
                    <span class="text-danger" v-else>  
                        <div v-if="memberinfo['membership'] == 'Monthly'">
                            Note:  You have {{ "No" }}  Free Mini Test Left, You will be deducted 1 monthly credit if you proceed 
                        </div>
                        <div v-else>  
                            Note:  You have {{ "No" }}  Free Mini Test Left, You will be deducted 1 point if you proceed 
                        </div>
                    </span>
                </span>                
                
                <div class="mt-2 text-secondary">
                    You have {{ this.miniTestSubmittedCount }} submitted Minitest in last 7 days: 
                </div>                
            </div>

            <!-- BUTTON TO START -->
            <div v-show="this.started == false" class="my-4">
                <button v-on:click="start()" class="btn btn-success" >
                    <i class="fa fa-list-alt" aria-hidden="true"></i>
                    Start Test 
                </button>
            </div>

        </div>

        <div class="mini-test" v-show="this.started == true && this.loading == true">

            <div class="pt-4 text-secondary">
                {{ "Please wait.." }}
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
         
            <div id="multipleViewQuestions" v-if="multiple == true && submitted == false">

                <div class="questions row" v-for="(question, qIndex) in this.questions" :key="qIndex"> 

                    <div class="question container col-12">
                        <span class="font-weight-bold">
                            {{ (qIndex + 1)  +"." }} {{ question.question }}
                        </span>
                        <b-form-group>
                            <b-form-radio :name="'question_'+ question.id +''"
                            v-for="(choice, choiceIndex) in question.choices" :key="choiceIndex"
                            v-bind:value="choice.id"
                            class="ml-3 pt-2"> {{ choice.choice }} </b-form-radio>
                        </b-form-group>
                    </div>

                     <div class="my-3 small lh-sm border-bottom w-100"></div>          

                </div>

                <div class="py-4">
                    <button class="btn btn-success" v-on:click="checkSubmittedAnswers()"> Submit Answers </button>   
                </div>
            </div>


            <!-- START SINGLE VIEWER -->
            <div id="singleViewQuestions" v-if="multiple == false && submitted == false">

                <div class="questions row" v-for="(question, qIndex) in this.questions" :key="qIndex"> 
                    
                    <div class="question container col-12" v-show="qIndex == questionIndex">

                        <span class="font-weight-bold mb-3">
                            {{ (qIndex + 1)  +"." }} {{ question.question }}
                        </span>
                        <b-form-group>
                            <b-form-radio :name="'question_'+ question.id +''" v-for="(choice, choiceIndex) in question.choices" :key="choiceIndex"
                            v-bind:value="choice.id"
                            class="ml-3 pt-2"> {{ choice.choice }} </b-form-radio>

                            <div class="pb-3 mb-0 small lh-sm border-bottom w-100"></div>          

                        </b-form-group>
                    </div>

                </div>

                <div class="py-4">
                    <button class="btn btn-primary" v-on:click="getPrevQuestion()" v-show="(count - 1) >= 1"> Previous {{ count - 1 }} </button>     
                    <button class="btn btn-primary" v-on:click="getNextQuestion()" v-show="count < this.questionsLength"> Next {{ count }} </button>
                    <button class="btn btn-success" v-on:click="checkSubmittedAnswers()" v-show="count >= this.questionsLength"> Submit Answers </button>   
                </div>          
                
            </div>

            
            <div id="result" v-if="submitted == true && loading == false">

                <h4 class="text-primary mb-1"> 
                    <strong> Your Test Result </strong>
                </h4>

                <h5 class="mb-4 text-dark">                       
                    Your have {{ totalCorrectAnswers }} correct answers out of {{ totalQuestions}}                  
                </h5>                


                <h4 class="text-primary mb-1"> <strong> Test Summary </strong></h4>

                <div class="summary">

                    <div v-for="(result, resultKey) in results" :key="'result_'+resultKey" class="mb-3">

                        <div class="font-weight-bold">
                           {{ resultKey + 1 }}{{"."}} {{ result.question }} 
                        </div>

                        <div class="answer-container ml-3 mt-2">

                            <div class="font-weight-bold">
                                Correct Answer: 
                                <span class="text-orange">
                                    {{ result.correct_answer }}
                                </span>
                            </div>

                            
                            <div v-if="result.your_answer === null" class="pt-2 text-secondary">
                                <i class="fa fa-question " aria-hidden="true"></i>  {{ "No Answer" }}
                            </div>

                            <div v-else class="pt-2">
                                <div class="font-weight-bold">
                                    Your Answer: 
                                    <span class="text-primary">
                                        {{ result.your_answer }} 
                                    </span>
                                </div>                                       
                                <div v-if="result.is_correct == true" class="text-success font-weight-bold"> <i class="fa fa-check" aria-hidden="true"></i> Correct </div>
                                <div v-else-if="result.is_correct == false" class="text-danger font-weight-bold"> <i class="fa fa-times" aria-hidden="true"></i> Incorrect </div>
                            </div>

                        </div>
                    
                    </div>
                </div>



                <h4 class="text-primary mb-1"> <strong> Your Test Result </strong></h4>

                <h5 class="mb-4 text-dark">                       
                    Your have {{ totalCorrectAnswers }} correct answers out of {{ totalQuestions}}                  
                </h5>      


                <button class="btn btn-primary mb-4" v-on:click="redirectHomePage()"> Finish </button>   
                

            </div>
        </div>   
    </div>

    <div v-else>
        <div class="py-4 text-center small text-danger">
            Sorry, we still don't have any question for this test category, please check back later.
        </div>
    </div>

</template>

<script>
    import {Helpers} from "../../helpers/helpers.js";
    import HelpersComponents from "../../helpers/helper_components.js";
    
    export default {   
        name: "questions-component",
        components: {    
            HelpersComponents,
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
                miniTestSubmittedCount: 0,
                freeMiniTest: 0,
                limit: 2,

                totalQuestions: 0,
                totalCorrectAnswers: 0,


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
         


                //member answers list
                answeredQuestionCount: 0,
                answers: [],       
                results: [],            
            }
        },
         methods: 
         {

            start() {

                this.recordStartTime();
            },

            countFreeMiniTest() 
            {            
                this.freeMiniTest = this.limit - this.miniTestSubmittedCount;
            },
        
            async getQuestions() 
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

                        this.miniTestSubmittedCount = response.data.miniTestSubmittedCount;
                        this.countFreeMiniTest();     

                    } else {
                        alert (response.data.message)
                    }

                }).finally((url) => {      
                                
                    this.categoryLoading = false;  
                });
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
                    'answers' : this.answers,               
                }

                await this.getURL(url, data).then(response => 
                {
                    if (response.data.success == true)
                    {
                        //RECORD START TIME, AND get the id for minitest to update the TABLE END TIME WHEN SUBMITTED
                        this.miniTestID = response.data.id;
                        this.startTimer();


                        if (response.data.membershipType == "Monthly")  
                        {
                        
                            //update mini test count
                            this.miniTestSubmittedCount = response.data.miniTestSubmittedCount;
                            this.countFreeMiniTest(); 
                            
                            //total monthly credits
                            document.getElementById("monthlyLessonsLeft").innerHTML = response.data.totalMonthlyCredits;
                        
                        } else if (response.data.membershipType == "Point Balance" || response.data.membershipType  == "Both") {
                        

                            //update mini test count
                            this.miniTestSubmittedCount = response.data.miniTestSubmittedCount;
                            this.countFreeMiniTest(); 

                            //total credits
                            document.getElementById("total_credits").innerHTML = response.data.totalCreditsFormatted;
                        
                        }

                        
                    } else {
                    
                    
                        alert (response.data.message);


                    }

                }).finally(() => {  

                    this.loading = false;

                });  


            },
            startTimer() {
                this.seconds = 60;
                this.secondsHand = 60;
                this.myIntervalTimer = setInterval(this.checkMinute, this.timerSpeed);
                this.started = true;
                this.loading = false;
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
                if ( parseFloat(this.timerValue) <= 0) 
                {
                    clearInterval(this.myIntervalTimer);                
                    this.submitAnswers();
                }
            },
            getPrevQuestion() {
                this.count--;
                this.questionIndex--;
            },
            getNextQuestion() 
            {   
                let question = this.questions[this.questionIndex];
                let selected_element = document.querySelector('input[name=question_'+ question.id +']:checked');  

                if (selected_element) 
                {
                    this.questionIndex++;
                    this.count++;

                } else {                

                    this.$bvModal.msgBoxOk('Please select your answer', {
                        title: 'Notification',
                        size: 'md',
                        buttonSize: 'sm',
                        okVariant: 'primary',
                        headerClass: 'p-2 border-bottom-0',
                        footerClass: 'p-2 border-top-0',
                        centered: true
                    })
                    .then(value => {
                        //this.value = value
                    })
                    .catch(err => {
                        // An error occurred
                    })
                    
                }
            },

            async getURL(url, data) {          
                return Helpers.getURL(url, data);
            },
           
            getAnswers() 
            {
                this.answeredQuestionCount = 0; //reset

                this.answers = [];

                this.questions.forEach((question, index) => 
                {
                    let selected_element = document.querySelector('input[name=question_'+question.id +']:checked');  
                    
                    if (selected_element) 
                    {

                        this.answeredQuestionCount++ 

                        let choices     = question.choices;
                        let choiceText  = "";

                        choices.forEach((choice, choicesIndex) => 
                        {

                            if (choice.id == selected_element.value) {
                                choiceText = choice.choice;
                            }                            
                        });

                        this.answers.push({
                            question_id             : question.id,
                            question_text           : question.question,
                            choices                 : question.choices,
                            selected_choice_id      : selected_element.value,
                            selected_choice_text    : choiceText,
                        });

                    } else {

                        this.answers.push({
                            question_id         : question.id,
                            question_text      : question.question,
                            choices             : question.choices,
                            selected_choice_id  : null,
                            selected_choice_text: null,
                        });

                     
                    }

                });
            },
            checkSubmittedAnswers() 
            {
                if (this.multiple == true) 
                {
                    this.getAnswers();

                    console.log( this.questionsLength + "  ==  " +  this.answeredQuestionCount )

                    if (this.questionsLength == this.answeredQuestionCount) 
                    {
                        this.loading = true;
                        this.submitAnswers();                    
                    } else {
                     
                        this.$bvModal.msgBoxOk('You still have time left, please answer all questions', {
                            title: 'Notification',
                            size: 'md',
                            buttonSize: 'sm',
                            okVariant: 'primary',
                            headerClass: 'p-2 border-bottom-0',
                            footerClass: 'p-2 border-top-0',
                            centered: true
                        })
                        .then(value => {
                            //this.value = value
                        })
                        .catch(err => {
                            // An error occurred
                        })

                    }                
                
                } else {
                
                    let question = this.questions[this.questionIndex];
                    let selected_element = document.querySelector('input[name=question_'+ question.id +']:checked');  

                    if (selected_element) {

                        this.loading = true;
                        this.getAnswers();
                        this.submitAnswers();

                    } else {                
                    

                       this.$bvModal.msgBoxOk('Please select your answer', {
                            title: 'Notification',
                            size: 'md',
                            buttonSize: 'sm',
                            okVariant: 'primary',
                            headerClass: 'p-2 border-bottom-0',
                            footerClass: 'p-2 border-top-0',
                            centered: true
                        })
                        .then(value => {
                            //this.value = value
                        })
                        .catch(err => {
                            // An error occurred
                        })

                    }

                }

            },

            async submitAnswers() 
            {

                this.loading = true;

                let url = "/api/postAnswers?api_token=" + this.api_token;
                let data  = {

                    'miniTestID': this.miniTestID, //we need to just update

                    'member_id': this.memberinfo.id,
                    'category_id': this.category.id,
                    'answers' : this.answers,
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
            redirectHomePage() {
            
                 window.location.replace('/home');
            }
        },        
        mounted () 
        {          
            this.getQuestions();

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