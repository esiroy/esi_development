<template>
    <div class="homeworkUploader">
        <div class="card">

            <div class="card-body table-responsive ">
                <table class="table table-borderless table-hover">
                <thead v-if="files.length">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Size</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="!files.length">
                    <td colspan="7" align="center">
                        <!--<div class="small"> Drop files anywhere to upload<br />or</div-->
                        <label for="homework-uploader-file" class="btn btn-sm btn-primary">Select Files</label>
                    </td>
                    </tr>
                    <tr v-for="(file, index) in files" :key="file.id">
                    <td>{{index + 1}}</td>
                    <td>
                        <div class="filename">{{file.name}}</div>
                        <div class="progress" v-if="file.active || file.progress !== '0.00'">
                        <div
                            :class="{'progress-bar': true, 'progress-bar-striped': true, 'bg-danger': file.error, 'progress-bar-animated': file.active}"
                            role="progressbar"
                            :style="{width: file.progress + '%'}"
                        >{{file.progress}}%</div>
                        </div>
                    </td>
                    <td>{{file.size | formatSize}}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a :class="{'dropdown-item small': true, disabled: !file.active}" href="#" @click.prevent="file.active ? $refs.homeworkUploader.update(file, {error: 'cancel'}) : false">Cancel</a>
                                <a class="dropdown-item small" href="#" v-if="file.active" @click.prevent="$refs.homeworkUploader.update(file, {active: false})">Abort</a>
                                <a class="dropdown-item small" href="#" v-else-if="file.error && file.error !== 'compressing' && $refs.homeworkUploader.features.html5" @click.prevent="$refs.homeworkUploader.update(file, {active: true, error: '', progress: '0.00'})">Retry upload</a>
                                <a :class="{'dropdown-item small': true, disabled: file.success || file.error === 'compressing'}" href="#" v-else @click.prevent="file.success || file.error === 'compressing' ? false : $refs.homeworkUploader.update(file, {active: true})">Upload</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item small" href="#" @click.prevent="$refs.homeworkUploader.remove(file)">Remove</a>
                            </div>
                        </div>

                    </td>
                    </tr>
                </tbody>
                </table>

                <div class="upload">

                    <!--
                    <div v-show="$refs.homeworkUploader && $refs.homeworkUploader.dropActive" class="drop-active">
                        <div class="small">Drop an image file to upload</div>
                    </div>
                    -->
                
                    <file-upload
                        name="file"
                        input-id="homework-uploader-file"
                        class=""
                        extensions="jpeg,jpg,gif,png,pdf "
                        accept="image/png,image/gif,image/jpeg,application/pdf "
                        v-model="files"
                        :post-action="'/api/uploader/uploadHomework?api_token='+ this.api_token"
                        :headers="{'X-CSRF-TOKEN': this.csrf_token }"
                        :multiple="false"
                        :drop="true"
                        :drop-directory="false"
                        @input="this.updateValue"
                        @input-file="this.inputFile"
                        @input-filter="this.inputFilter"
                        :data="{
                            //order_id: this.homework_index,
                            'lesson_schedule_id': this.reservation.schedule_id,
                            'reservation': JSON.stringify(this.reservation),
                            'instruction': this.instructions
                        }"
                        ref="homeworkUploader">
                        

                        <div>Drop an file or image file to upload</div>

                        <!--<i class="fa fa-plus"></i>Select files-->
                    </file-upload>


                   
                    <button type="button" class="btn btn-success"
                        @click.prevent="triggerPostFeedback()">
                        <i class="fa fa-arrow-up" aria-hidden="true"></i>Test Feedback
                    </button>
                  


                    <!--

                    <button type="button" class="btn btn-success"
                        @click.prevent="startUpload()">
                        <i class="fa fa-arrow-up" aria-hidden="true"></i>Test Upload
                    </button>

                    <button type="button" class="btn btn-success" v-if="!$refs.homeworkUploader || !$refs.homeworkUploader.active" 
                        @click.prevent="$refs.homeworkUploader.active = true">
                        <i class="fa fa-arrow-up" aria-hidden="true"></i>Start Upload
                    </button>

                    <button type="button" class="btn btn-danger" v-else @click.prevent="$refs.homeworkUploader.active = false">
                    <i class="fa fa-stop" aria-hidden="true"></i>Stop Upload
                    </button>
                    -->

                </div>

                <hr>

                <span class="small border-1 text-secondary">Add homework instructions below</span>

                <div class="mt-2">
                    <vue-ckeditor v-model="instructions" :config="config" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" />
                </div>

            </div>

        </div>

       
    </div>
</template>

<style scoped>

    .upload {
        border:1px dashed #ccc; 
        text-align: center;
        padding: 12px;
    }
</style>
    

<script scoped>
import FileUpload from 'vue-upload-component'
import VueCkeditor from 'vue-ckeditor2'
export default {
    components: {
        FileUpload, VueCkeditor
    },
    props: {
        user_info: {
            type: [Object, String],
            required: true
        },
        reservation: Object,         
        csrf_token: String,
        api_token: String                 
    },
    data() {
        return {
            files: [],
            instructions: null,
            lesson_schedule_id: null,
            //homework_index: null,
            config: {
                toolbar: [
                    [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'NumberedList' ],
                    
                ],
                removePlugins: ['easyimage', 'cloudservices', 'exportpdf'],
                height: 150
            },            
        };
  },
  methods: {

    triggerPostFeedback() {   
      
        this.$root.$refs['lessonSliderComponent'].$refs['memberFeedback'].postFeedback();
        
    },
    getFileCount() {
        return this.files.length;
    },
    startUpload()
    {     
        this.$refs.homeworkUploader.active = true;        
    },
    updateValue(value) {     
      this.files = value;
    },
    /**
     * Has changed
     * @param  Object|undefined   newFile   Read only
     * @param  Object|undefined   oldFile   Read only
     * @return undefined
     */
    inputFile(newFile, oldFile) {
      if (newFile && oldFile && !newFile.active && oldFile.active) {

        if (newFile.xhr) {

            if ( newFile.xhr.status === 200) 
            {

                
                let file = {
                                'id'        : newFile.response.id,
                                'file_name' : newFile.response.file,
                                'path'      : newFile.response.path,
                                'fullpath'  : newFile.response.fullpath,
                                'size'      : newFile.response.size,
                                'owner'     : newFile.response.owner,
                            };

                this.triggerPostFeedback();

                //remove the files
                this.files.splice(this.files.findIndex(function(i){
                    return i.id === newFile.id;
                }), 1);
            }
        }
      }
    },
    /**
     * Pretreatment
     * @param  Object|undefined   newFile   Read and write
     * @param  Object|undefined   oldFile   Read only
     * @param  Function           prevent   Prevent changing
     * @return undefined
     */
    inputFilter(newFile, oldFile, prevent) {
        if (newFile && !oldFile) {
            // Filter non-image file
            if (!/\.(jpeg|jpg|gif|png|pdf)$/i.test(newFile.name)) {
                alert ("Please upload an image file with (.jpg, .jpeg, png extension)")
                return prevent();
            }        
        }
        if (newFile && (!oldFile || newFile.file !== oldFile.file)) {
            // Create a blob field
            newFile.blob = ''
            let URL = window.URL || window.webkitURL
            if (URL && URL.createObjectURL) {
                newFile.blob = URL.createObjectURL(newFile.file)
            }
            // Thumbnails
            newFile.thumb = ''
            if (newFile.blob && newFile.type.substr(0, 6) === 'image/') {
                newFile.thumb = newFile.blob
            }        
        }
    },
    onEditorBlur (editor) {
        console.log(editor)
    },
    onEditorFocus (editor) {
        console.log(editor)
    },      
  }
};
</script>




