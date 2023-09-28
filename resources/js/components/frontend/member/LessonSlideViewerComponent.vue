<template>

    <div>
        <div id="slides-viewer-holder" class="row">  

            <div class="col-12">

                <AudioPlayerComponent ref="audioPlayer" :is-broadcaster="true"></AudioPlayerComponent>

            </div>

            <div class="col-12">

                <div> Slide {{ currentSlide }} of {{ this.$props.slide_history.length }} </div>

                <div id="slides">
                    <div class="image-border">
                        <div v-for="(slideHistoryItem, slideIndex) in slides" :key="slideIndex">
                            <img :src="JSON.parse(slideHistoryItem.data)"  class="img-fluid" v-if="(slideIndex + 1) == currentSlide" />
                        </div>
                    </div>

                    <!--[START] SLIDE HISTORY CONTROLLER -->
                  

                    <button class="btn btn-primary" @click="prev()">Previous</button>
                    <span id="member-lesson-history">

                        <b-dropdown id="lessons-dropdown" :text="current_folder_name" class="m-md-2" variant="primary">                          
                            <b-dropdown-item v-for="lesson in lessons" :key="'lesson-'+lesson.id" @click="loadLesson(lesson.batch)">{{ lesson.batch + " - " + lesson.folder_name }}</b-dropdown-item>                        
                        </b-dropdown>
                    </span>                    
                    <button class="btn btn-primary" @click="next()">Next</button>
                    <!--[END] SLIDE HISTORY CONTROLLER -->

                </div>

            </div>

            <div class="col-12 mt-3">

                <div class="card esi-card">
                    <div class="card-header esi-card-header-title">
                        <span class="small">Tutor Feeback</span>
                    </div>
                    <div class="card-body">


                        <div class="px-2" v-for="(feedback, feedbackIndex) in member_feedback" :key="feedbackIndex">
                            <div :id="feedbackItem['name']" class="row px-2" v-for="(feedbackItem, dIndex) in feedback['details']" :key="dIndex"> 
                                <div class="col-4">
                                    <div class="font-weight-bold small mt-4">{{ feedbackItem['description'] }}</div>
                                </div>
                                <div class="col-8">
                                    <b-form-rating v-model="feedbackItem['value']" variant="warning" class="mb-2" no-border readonly size="lg"></b-form-rating>                        
                                </div>
                            </div>

                            <hr/>

                            <div id="tutor-message" class="row px-2"> 
                                <div class="col-5">                            
                                    <div class="font-weight-bold small mt-4">Tutor Feedback</div>
                                </div>
                                <div class="col-7">
                                    <div class="small my-4">
                                        <span v-html="feedback.feedback || noFeedBackNotification "></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
        

        <!--<div id="slide-container"></div>-->

    </div>



</template>

<script>
import AudioPlayerComponent from './AudioPlayerComponent.vue'


export default {
    name: "lessonSlideViewerComponent",
    components: { AudioPlayerComponent },
    
    props: {
        csrf_token: String,		
        api_token: String,
        reservation: Object,        
        slide_history: Array,
        member_feedback: Array,
        audio_files: Object,
        lessons: Array,
        slide_images: Object,
    },
    data() {
        return {
            slides: this.$props.slide_history,
            currentSlide: 1,
            noFeedBackNotification: "<span class='text-danger small'>No Tutor Feedback Found</span>",
            audioFiles: [],

            //lessons
            current_folder_name: this.$props.lessons[0].folder_name,

            //canvas
            canvas  : [],
            canvas_width: 1920,
            canvas_height: 1080,
        };
    },
    mounted() {
        console.log(this.$props.lessons);
        this.currentSlide = 1;    

        this.audioFiles = this.$props.audio_files;

        console.log(this.audioFiles)    
        //this.loadAudio();

        this.$forceUpdate();
    },
    methods: {
   
        prev() {
            if (this.currentSlide > 1) {
                this.currentSlide--;
                this.loadAudio();
                this.$forceUpdate();
            }
        },    
        next() {
            if (this.currentSlide < this.$props.slide_history.length) {
                this.currentSlide++;
                this.loadAudio();
                this.$forceUpdate();
            } 
        },
        loadAudio() {
            if (this.$refs['audioPlayer']) {
                this.$refs['audioPlayer'].loadAudioList(this.audioFiles, this.currentSlide); 
            } else {            
                console.log("audio list hidden")
            }
        },
        loadLesson(batch) {
            this.currentSlide = 1;
            this.current_folder_name =  this.$props.lessons[batch - 1].folder_name,

            console.log(this.$props.slide_images);
            this.slides = this.$props.slide_images[batch];
            this.$forceUpdate();
        }
        /*
        convertToData(slideIndex) {
            return this.canvas[slideIndex].toDataURL('image/jpeg', 0.5)
        },
        createCanvas(index) {
            let slideContainer = document.getElementById('slide-container');


            //Editor Container Element
            var elementExists = document.getElementById("editor" + index);

            if (!elementExists) {            
                //console.log("++++++++ CREATING CANVAS ++++++++");
                let editorElement = document.createElement("div");
                editorElement.setAttribute("id",    "editor" + index);
                //editorElement.setAttribute("style", "overflow: hidden; clear: both; visibility: " + visibility + "; display:"+ display +";");

                let slideContainer = document.getElementById('slide-container');
                slideContainer.append(editorElement);            

                //Add canvas to the editor
                let canvasElement = document.createElement("canvas");
                canvasElement = document.createElement('canvas');
                canvasElement.setAttribute("id",    "canvas" + index);
                canvasElement.setAttribute("width", this.canvas_width);
                canvasElement.setAttribute("height", this.canvas_height);
                canvasElement.setAttribute("style", "border: 7px solid rgb(0, 118, 190); height: 100%;");
                let mediaContainer = document.getElementById('editor'+ index);
                mediaContainer.append(canvasElement);

            } 

        },        
        updateCanvas(canvas, canvasData){
            if (canvas) {
                canvas.loadFromJSON(canvasData, this.disableCanvas, (o, object) => {            
                    if(this.$props.is_broadcaster == false) {
                        this.deactivateSelector()                
                    }                
                }); 
            }            
        },
        getSlidesFromHistory(slideHistory) {
            this.slides  =  slideHistory.length;
            for (var i = 0; i < this.slides; i++) {
                let slideIndex = i + 1;
                this.createCanvas(slideIndex);
                this.canvas[slideIndex]  = new fabric.Canvas('canvas'+slideIndex, { backgroundColor : "#fff" });
                this.updateCanvas(this.canvas[slideIndex], JSON.parse(slideHistory[i].content)); 
                this.canvas[slideIndex]['scale'] = 1;
            }         
        },           
        */  
   
        
    }
};
</script>

<style lang="scss" scoped>

    .image-border {
        border: 7px solid #0072ba;
        margin: 3px;
        
    }

</style>


