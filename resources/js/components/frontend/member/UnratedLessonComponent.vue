<template>
  <div id="unrated-lessons-list">

    <b-modal ref="unratedLessonModal" header-bg-variant="primary" header-text-variant="white" 
        size="lg" no-close-on-backdrop hide-footer no-close-on-esc  hide-header-close
        v-model="showPopup" @hidden="closePopup">
    
        <template #modal-header>
            <div class="mx-auto text-center text-white">
                You Have ({{ unratedLessons.length }}) Unrated Tutor Survey
            </div>           
        </template>

  
        <template #default>

            <div class="info-container">
                <h5 class="text-maroon">Lesson Satisfaction Survey</h5>        
                <h6 class="font-weight-bold"> 必ず満足度（★）を入力してください </h6>
                <hr/>
            </div>


            <ul class="esi-listings">

                <div v-if="showMessage" class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ message }}
                </div>
                
                <transition-group name="fade">
                    <li v-for="lesson in unratedLessons" :key="lesson.schedule_id" class="hover-list-item">
                        <div class="row">

                            <div class="col-md-3 small">
                                <div class="col-12 text-right">
                                    <div class="rounded pt-4 pb-2">
                                        <img :src="lesson.tutorProfileImage"
                                        alt="Thumbnail Image" class="img-thumbnail">
                                    </div> 
                                </div>                   
                            </div>  
                            <div class="col-md-9">

                                <div class="hover-info">
                                    <div class="small text-success text-left">
                                        <span class="info-text small font-weight-bold">★を左から右に移動すると評価が上がります  (評価は受講歴から変更可能です）</span>
                                    </div>
                                </div>

                                <star-rating
                                    v-bind:show-rating="false"
                                    v-bind:star-size="30"
                                    v-bind:animate="true"
                                    v-bind:padding="5"
                                    v-model="lesson.rating"
                                    @rating-selected ="setRating(lesson.rating, lesson.schedule_id)"
                                ></star-rating>

                                <div class="info pt-2 small">
                                    <span class="font-weight-bold">Tutor:</span>                        
                                    <span>{{ lesson.tutorName }}</span>
                                </div>

                                <div class="info small">
                                    <span class="font-weight-bold">Lesson Date:</span>                        
                                    <span>{{ lesson.jp_lesson_date }}</span>
                                </div>

                                <div class="info pt-0 small">
                                    <span class="font-weight-bold">Duration:</span>                        
                                    <span>{{ formattedTime(lesson.duration.jp_startTime) }}</span>
                                    <span>-</span>
                                    <span>{{ formattedTime(lesson.duration.jp_endTime) }}</span>
                                </div>                    
                            </div>
                        </div>                    
                    </li>
                </transition-group>

            </ul>

        </template>
        <template #modal-footer>
            <b-button @click="closePopup">Close</b-button>
        </template>
    </b-modal>

  </div>
</template>

<script>
import axios from 'axios';
import StarRating from 'vue-star-rating'

export default {
    name: "UnratedLessonComponent",
    components: { StarRating, axios},   
    props: {
        user_id: Number,
        api_token: String,
        csrf_token: String
    },
    data() {
        return {
            unratedLessons: [],
            showPopup: false,
            userID: null,
            teacherPerformanceRating: null,
            message: "Survey Successfully Added",
            showMessage: false
        };
    },
    mounted() {

        this.userID = this.$props.user_id;

        axios.post("/api/getUnratedLessons?api_token=" + this.api_token, 
        {
            method                  : "POST",
            userID                : this.userID,
        }).then(response => {
            this.unratedLessons = response.data;        
            if (this.unratedLessons.length >= 1) {
                console.log(this.unratedLessons)
                this.openPopup();
            }        
        }).catch(error => {
            console.error('Failed to fetch unrated lessons:', error);
        });
    },

    methods: {
        setRating: function(rating, scheduleId) {
            this.teacherPerformanceRating = rating;
            axios.post("/api/setLessonRating?api_token=" + this.api_token, 
            {
                method                  : "POST",
                userID              : this.userID,
                scheduleId          : scheduleId,
                rating              : rating,
            }).then(response => {

                this.removeLesson(scheduleId);

                if (this.unratedLessons.length >= 1) {                
                    this.showMessage = true;
                    setTimeout(() => {
                        this.showMessage = false;
                    }, 3000);
                } else {           
                    setTimeout(() => {
                        this.closePopup()
                    }, 1000);
                }

            }).catch(error => {
                console.error('Failed to fetch unrated lessons:', error);
            });     
        },
        removeLesson(scheduleId) {

            const lessonIndex = this.unratedLessons.findIndex(lesson => lesson.schedule_id === scheduleId);
            if (lessonIndex !== -1) {
                const lesson = this.unratedLessons[lessonIndex];
                this.unratedLessons.splice(lessonIndex, 1);
            }

          
        },
        quickRemoveLesson(scheduleId) {
            this.unratedLessons = this.unratedLessons.filter(lesson => lesson.schedule_id !== scheduleId);
        },
        openPopup() {
            this.showPopup = true;
        },
        closePopup() {
            this.showPopup = false;
        },
        formattedTime(timestamp) {
            var time = timestamp.split(' ')[3];
            return time.substring(0, 5); // Extract the time portion
        }
    }
};
</script>

<style scoped>
h1 { color: #333; }
ul { list-style-type: none; }
li { margin-bottom: 10px;}

.esi-listings {
    height: 480px; 
    overflow-y: auto; 
    overflow-x: hidden;
    list-style: none;
    padding: 0;    
}

.hover-list-item {
    background-color: rgb(150 222 253 / 10%);
    border-radius: 12px;
    border: 3px solid #cccccc2b;
    padding: 3px 0px 5px;   
    margin: 0px 10px 10px;     
}

.hover-list-item        .hover-info .info-text{ visibility: hidden; }
.hover-list-item:hover  .hover-info .info-text{ visibility: visible }

.fade-enter-active,
.fade-leave-active {
    background-color: rgba(0, 255, 51, 0.150);
    transition: opacity 0.5s ease-out;
}

.fade-enter,
.fade-leave-to {
    background-color: rgba(0, 255, 51, 0.400);
    opacity: 0;
}

</style>