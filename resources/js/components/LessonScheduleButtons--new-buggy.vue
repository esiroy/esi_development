<template>

	<div id="scheduleButtons">

        <div v-show="lessonStatus( { tutorID:  tutorData.tutorID, startTime: tutorData.startTime, endTime: tutorData.endTime, status: 'Tutor Scheduled' })" class="tutor_scheduled">                                
            <div class="actionButtons">
                <div class="iEdit">                   
                    <a href="javascript:void(0);" @click="$emit('editSchedule', tutorData)"><img src="/images/iEdit.gif"></a>
                </div>
                <div class="iDelete">
                    <a href="javascript:void(0);" @click="$emit('deleteSchedule', {tutorID: tutorData.tutorID, 'startTime': tutorData.startTime, })"><img src="/images/iDelete.gif"></a>
                </div>
            </div>
        </div>
        
      
        <!--
        <div v-show="lessonStatus({ tutorID: tutorData.tutorID, startTime: tutorData.startTime, endTime: tutorData.endTime, status: 'Client Reserved' })" class="client_reserved">
            
            <div class="actionButtons">
                <div class="iEdit">
                    <a href="javascript:void(0);" @click="$emit('editSchedule',{tutorID: tutorData.tutorID, 'startTime': tutorData.startTime, })"><img src="/images/iEdit.gif"></a>
                </div>
                <div class="iDelete">
                    <a href="javascript:void(0);" @click="$emit('deleteSchedule',{tutorID: tutorData.tutorID, 'startTime': tutorData.startTime, })"><img src="/images/iDelete.gif"></a>
                </div>
            </div>
        </div>

        <div v-show="lessonStatus({ tutorID: tutorData.tutorID, startTime: tutorData.startTime, endTime: tutorData.endTime, status: 'Client Reserved B' })" class="client_reserved_b">
            
            <div class="actionButtons">
                <div class="iEdit">
                    <a href="javascript:void(0);" @click="$emit('editSchedule',{tutorID: tutorData.tutorID, 'startTime': tutorData.startTime, })"><img src="/images/iEdit.gif"></a>
                </div>
                <div class="iDelete">
                    <a href="javascript:void(0);" @click="$emit('deleteSchedule',{tutorID: tutorData.tutorID, 'startTime': tutorData.startTime, })"><img src="/images/iDelete.gif"></a>
                </div>
            </div>                            
        </div> 


        <div v-show="lessonStatus({ tutorID: tutorData.tutorID, startTime: tutorData.startTime, endTime: tutorData.endTime, status: 'Tutor Cancelled' })" class="tutor_cancelled">            
            <div class="actionButtons">
                <div class="iEdit">
                    <a href="javascript:void(0);" @click="$emit('editSchedule',{tutorID: tutorData.tutorID, 'startTime': tutorData.startTime, })"><img src="/images/iEdit.gif"></a>
                </div>
                <div class="iDelete">
                    <a href="javascript:void(0);" @click="$emit('deleteSchedule',{tutorID: tutorData.tutorID, 'startTime': tutorData.startTime, })"><img src="/images/iDelete.gif"></a>
                </div>
            </div>                            
        </div> 

        <div v-show="lessonStatus({ tutorID: tutorData.tutorID, startTime: tutorData.startTime, endTime: tutorData.endTime, status: 'Nothing' })" class="nothing"></div> 


        <div v-show="lessonStatus({ tutorID: tutorData.tutorID, startTime: tutorData.startTime, endTime: tutorData.endTime, status: 'Client Not Available' })" class="client_not_available">                                
            <div class="actionButtons">
                <div class="iEdit">
                    <a href="javascript:void(0);" @click="$emit('editSchedule',{tutorID: tutorData.tutorID, 'startTime': tutorData.startTime, })"><img src="/images/iEdit.gif"></a>
                </div>
                <div class="iDelete">
                    <a href="javascript:void(0);" @click="$emit('deleteSchedule',{tutorID: tutorData.tutorID, 'startTime': tutorData.startTime, })"><img src="/images/iDelete.gif"></a>
                </div>
            </div>                            
        </div> 

        <div v-show="lessonStatus({ tutorID: tutorData.tutorID, startTime: tutorData.startTime, endTime: tutorData.endTime, status: 'Suppressed Schedule' })" class="suppressed_schedule">                               
            <div class="actionButtons">
                <div class="iEdit">
                    <a href="javascript:void(0);" @click="$emit('editSchedule',{tutorID: tutorData.tutorID, 'startTime': tutorData.startTime, })"><img src="/images/iEdit.gif"></a>
                </div>
                <div class="iDelete">
                    <a href="javascript:void(0);" @click="$emit('deleteSchedule',{tutorID: tutorData.tutorID, 'startTime': tutorData.startTime, })"><img src="/images/iDelete.gif"></a>
                </div>
            </div>                            
        </div> 

        <div v-show="lessonStatus({ tutorID: tutorData.tutorID, startTime: tutorData.startTime, endTime: tutorData.endTime, status: 'Completed' })" class="completed">
            <div class="actionButtons">
                <div class="iEdit">
                    <a href="javascript:void(0);" @click="$emit('editSchedule',{tutorID: tutorData.tutorID, 'startTime': tutorData.startTime, })"><img src="/images/iEdit.gif"></a>
                </div>
                <div class="iDelete">
                    <a href="javascript:void(0);" @click="$emit('deleteSchedule',{tutorID: tutorData.tutorID, 'startTime': tutorData.startTime, })"><img src="/images/iDelete.gif"></a>
                </div>
            </div>                               
        </div>
        -->
     
    

	</div>
</template>
 
<script>
export default {
	props: {
        lessons: {
            type: Array
        },
        tutorData: {
            type: Object
        },
    },   
    methods: {               
        lessonStatus(data) 
        {  
            let isFound = false;
            $.each(this.lessons, function(key, value) 
            {               
                if (value.status === data.status && value.startTime === data.startTime && value.endTime === data.endTime) 
                {
                    isFound = true;
                    console.log("found")
                }
            });          
            return isFound;
        },
    },
    mounted() {
        console.log(this.lessons)

    },	
};
</script> 
 
<style lang="scss" scoped>

</style> 
 
