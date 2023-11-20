<template>
    <b-modal id="modal-consecutive-lessons" :title="'Consecutive Lessons'" 
        content-class="esi-modal" :header-bg-variant="'lightblue'" 
        no-close-on-esc no-close-on-backdrop hide-header-close>


            <div id="consecutive-lessons" v-if="consecutiveSchedules.duration" >
                <div class="card esi-card mt-2">    
                    <div class="card-header esi-card-header">Lesson Duration</div>
                    <div class="card-body">
                        <div>Start Time: {{ consecutiveSchedules.duration.startTime }}</div>
                        <div>End Time: {{ consecutiveSchedules.duration.endTime }}</div>
                        <div>Total Duration: {{ consecutiveSchedules.duration.length }} Minutes</div>
                    </div>
                </div>

                <div id="consecutive-lesson-list" class="table-container mt-2" >
                    <table class="table table-sm table-fixed">
                        <thead class="thead thead-dark">
                            <tr>
                            <th scope="col">#</th>
                                <th scope="col">Start Time</th>
                                <th scope="col">End Time</th>
                                <th scope="col">Duration</th>
                            </tr>
                        </thead>
                        <tbody c>
                            <tr v-for="(lesson, index) in consecutiveSchedules.lessons" :key="'lesson_'+index">
                                <th scope="row"> {{ index + 1 }}</th>
                                <td>{{ lesson.startTime }}</td>
                                <td>{{ lesson.endTime }}</td>
                                <td>25 Minutes</td>
                            </tr>
                                                                                                                                                                                                
                        </tbody>
                    </table>
                </div>
            </div>
            <template #modal-footer>
                <b-button variant="secondary" @click="cancelLesson">Cancel</b-button>
                <b-button variant="primary" @click="triggerStartTimer">Confirm and Start Lesson</b-button>
            </template>
    </b-modal>
</template>

<script>
export default { 
    name: "MemberConsecutiveLessons",
    props: {
        csrf_token: String,		
        api_token: String     
    },   
    data() 
    {
        return {           
            consecutiveSchedules: [],
        }
    },
    mounted() {

    },
    methods: {
    
        cancelLesson() {
           this.hideConsecutiveLessonModal();
        },
        hideConsecutiveLessonModal() {
            this.$bvModal.hide('modal-consecutive-lessons');
        },
        setConsecutiveLessons(consecutivelessons) {  
            this.consecutiveSchedules = consecutivelessons;
            this.$bvModal.show('modal-consecutive-lessons');  
            this.$forceUpdate();           
        },
        triggerStartTimer() 
        {
            //update this 
            let duration = {
                'startTime' : this.consecutiveSchedules.duration.startTime,
                'endTime'   : this.consecutiveSchedules.duration.endTime,
                'length'    : this.consecutiveSchedules.duration.length,
                'isLessonStarted': true,
            };

            this.$root.$emit('tiggerStartLesson', duration)   
        }

    }
}
</script>

<style lang="scss" scoped>
     #consecutive-lessons {
        table td {
            font-size: 11pt;
        }
        .table-container {
            height: 225px;
            overflow-y: scroll;
        }

        .table thead th {
            position: sticky;
            top: 0;
       
        }    
    }  

    
</style>

<style scoped>

    
    #consecutive-lessons {
        padding: 5px;
    }


  

</style>