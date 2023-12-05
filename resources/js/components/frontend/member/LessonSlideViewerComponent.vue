<template>

  <div>
      <div id="slides-viewer-holder" class="row">  

          <div class="col-12">

              <AudioPlayerComponent ref="audioPlayer" :is-broadcaster="true"></AudioPlayerComponent>

          </div>

          <div class="col-12">

              <div> 
                
                  Slide {{ currentSlide }} of {{ slideCount }} 
              
              </div>

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
                          <b-dropdown-item v-for="lesson in lessons" :key="'lesson-'+lesson.id" @click="loadLesson(lesson.batch)">{{ lesson.folder_name }}</b-dropdown-item>                        
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
      lesson_batch: Object,
      slide_images: Object,
  },
  data() {
      return {

          currentBatch:  1, //first batch

          slideCount: null,

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
      this.currentSlide = 1;    
      this.audioFiles = this.$props.audio_files;
      this.loadAudio();

      this.loadLesson(this.currentBatch) 

      console.log("audio files " , this.$props.audio_files)
      
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
          if (this.currentSlide < this.$props.slide_images[this.currentBatch].length) {
              this.currentSlide++;
              this.loadAudio();
              this.$forceUpdate();
          } 
      },
      loadAudio() {          
          if (this.$refs['audioPlayer']) {
              this.$refs['audioPlayer'].loadAudioList(this.audioFiles[this.currentBatch], this.currentSlide); 
          } else {            
              console.log("audio list hidden")
          }
      },
      loadLesson(batch) 
      {
          this.currentBatch = batch; //update batch
          this.currentSlide = 1;

          this.current_folder_name =  this.$props.lesson_batch[batch].folder_name;
          this.slides = this.$props.slide_images[batch];

          this.slideCount = this.$props.slide_images[batch].length;      
         
          this.$refs['audioPlayer'].stopAudio();
          this.$refs['audioPlayer'].resetAudioIndex()

          this.loadAudio()
      }
 
      
  }
};
</script>

<style lang="scss" scoped>

  .image-border {
      border: 7px solid #0072ba;
      margin: 3px;
      
  }

</style>


