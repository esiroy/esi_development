<template>

    <div class="container bg-light">


        <div class="row">

            <div id="question_container" class="col-7">
                <div id="questions">
                    <div class="py-2">
                        {{ count }}.  {{ questionViewer.question }}
                        <br>                
                        selected choice: {{ selected_choice }}
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
                        <button v-on:click="getPrevQuestion()" v-show="(count - 1) >= 1"> Previous {{ count - 1 }} </button>     
                        <button v-on:click="getNextQuestion()" v-show="count < this.questionsLength"> Next {{ count }} </button>
                        <button v-on:click="submitAnswers()" v-show="count >= this.questionsLength"> Submit Answers </button>   
                    </div>
                </div>
            </div>

            <!--
            <div class="5">
                Answers List:
                <div v-for="(answer, answerIndex) in answers" :key="answerIndex" class="mb-2">
                   <div>Question {{ answer.question }} </div>
                   <div>Answer: {{ answer.choice }} </div>
                </div>
            </div>
            -->


            <div class="answer">

                <div v-for="(result, resultIndex) in results" :key="resultIndex" class="mb-2">
                   <div>Question {{ result.question }} </div>
                   <div>Your Answer: {{ result.your_answer }} </div>
                   <div>Correct Answer: {{ result.correct_answer }} </div>
                   
                   <div v-if="result.is_correct == true"> Correct </div>
                   <div v-else-if="result.is_correct == false"> Incorrect </div>
                </div>

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
            category_id: Number,
            memberinfo: Object,
            csrf_token: String,		
            api_token: String,
        },        
        data() {
            return {
                count: 1,
                counter: 1,
                questionViewer: "",

                //query
                questionsLength: 0,
                questions: [],
                choices: [],

                selected_choice: null,

                //member answers list
                answers: [],

                results: [],            
            }
        },
         methods: {
            getChoices(questionID) 
            {
                this.choices = this.choices[questionID]
            },
            updateSelected(questionViewer, choice) {

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
                this.count--                
                //show the current question
                this.questionViewer = this.questions[this.count];
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
                this.count++
                this.questionViewer = this.questions[this.count];
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
            async submitAnswers() 
            {
                let url = "/api/postAnswers?api_token=" + this.api_token;
                let data  = {
                    'category_id': this.category_id,
                    'answers' : this.answers,
                    'member_id': this.memberinfo.id,
                }
                
                await this.getURL(url, data).then(response => {

                    if (response.data.success == true) {
                        this.results = response.data.results;
                        
                    }

                }).finally(() => {                  

                                
                });            
            },            
            async getList() 
            {            

                let url = "/api/getQuestions?api_token=" + this.api_token;
                let data = { 
                    'category_id': this.category_id
                };


                await this.getURL(url, data).then(response => {

                    if (response.data.success == true) {
                        this.questions = response.data.questions;
                        this.choices = response.data.choices;
                        this.questionsLength = Object.keys(this.questions).length                    
                        console.log(this.questionsLength);
                        //display question 
                        this.questionViewer = this.questions[this.count];                    
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
        }
    }
</script>

<style scoped>

    html {
        scroll-behavior: smooth;
    }
</style>