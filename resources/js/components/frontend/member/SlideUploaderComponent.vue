<template>
    <div class="slideUploader">
        

        <b-modal ref="modalCreateNewSlide" title="NewSlide">
            <p id="create" class="my-4">Create Empty Canvas</p>
            <p class="upload">Upload Image to your Canvas</p>
                <file-upload
                    name="file"
                    input-id="audio-file"
                    class="btn btn-primary"
                    extensions="mp3, wav"
                    accept="audio/mpeg3,audio/x-mpeg-3"
                    v-model="files"
                    post-action="/uploader/uploadAudio"
                    :headers="{'X-CSRF-TOKEN': this.csrf_token }"
                    :multiple="true"
                    :drop="true"
                    :drop-directory="true"
                    @input="updatetValue"
                    @input-file="inputFile"
                    @input-filter="inputFilter"
                    :data="{
                        file_id: this.file_id,

                        folder_id: this.folder_id
                    }"
                    ref="audioUploader">
                    
                    <i class="fa fa-plus"></i>
                    Select files
                </file-upload>
        </b-modal>


    </div>
</template>


<script>
    import FileUpload from 'vue-upload-component'


 export default {

    name: "slideUploaderComponent",
    props: {
        isBroadcaster: {
            type: [Boolean],
            required: true        
        },       
    },
    components: {
        FileUpload,
    },    
    data() {
        return {           
       
        }
    },
    mounted() { 
    },
    methods: {      
        prepareSlider() {
            this.$refs['modalCreateNewSlide'].show();

            this.$parent.createNewSlide();
        }
         
    }
 }
 </script>



