<template>
    <div class="slideUploader">
        <b-modal ref="modalCreateNewSlide" title="Create New Slide" hide-footer>


            <div class="accordion" role="tablist">

                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button block v-b-toggle.accordion-1 variant="primary">Upload Slide Image</b-button>
                    </b-card-header>
                    <b-collapse id="accordion-1" visible accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <div class="card">

                                <div class="card-header">Upload Slide</div>

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
                                            <h4> Drop files anywhere to upload<br />or</h4>
                                            <label for="file" class="btn btn-lg btn-primary">Select Files</label>
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
                                                    <a :class="{'dropdown-item small': true, disabled: !file.active}" href="#" @click.prevent="file.active ? $refs.slideUploader.update(file, {error: 'cancel'}) : false">Cancel</a>
                                                    <a class="dropdown-item small" href="#" v-if="file.active" @click.prevent="$refs.slideUploader.update(file, {active: false})">Abort</a>
                                                    <a class="dropdown-item small" href="#" v-else-if="file.error && file.error !== 'compressing' && $refs.slideUploader.features.html5" @click.prevent="$refs.slideUploader.update(file, {active: true, error: '', progress: '0.00'})">Retry upload</a>
                                                    <a :class="{'dropdown-item small': true, disabled: file.success || file.error === 'compressing'}" href="#" v-else @click.prevent="file.success || file.error === 'compressing' ? false : $refs.slideUploader.update(file, {active: true})">Upload</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item small" href="#" @click.prevent="$refs.slideUploader.remove(file)">Remove</a>
                                                </div>
                                            </div>

                                        </td>
                                        </tr>
                                    </tbody>
                                    </table>

                                    <div class="upload">
                                        <div v-show="$refs.slideUploader && $refs.slideUploader.dropActive" class="drop-active">
                                            <h3>Drop an image file to upload</h3>
                                        </div>

                                        <div class="btn">

                                            <file-upload
                                                name="file"
                                                input-id="file"
                                                class="btn btn-primary"
                                                extensions="jpeg,jpg,gif,png"
                                                accept="image/png,image/gif,image/jpeg"
                                                v-model="files"
                                                post-action="/uploader/uploadTutorLessonSlides"
                                                :headers="{'X-CSRF-TOKEN': this.csrf_token }"
                                                :multiple="false"
                                                :drop="true"
                                                :drop-directory="false"
                                                @input="this.updateValue"
                                                @input-file="this.inputFile"
                                                @input-filter="this.inputFilter"
                                                :data="{
                                                    folder_id: this.folder_id
                                                }"
                                                ref="slideUploader">
                                                
                                                <i class="fa fa-plus"></i>
                                                Select files
                                            </file-upload>

                                            <button type="button" class="btn btn-success" v-if="!$refs.slideUploader || !$refs.slideUploader.active" @click.prevent="$refs.slideUploader.active = true">
                                            <i class="fa fa-arrow-up" aria-hidden="true"></i>Start Upload
                                            </button>

                                            <button type="button" class="btn btn-danger" v-else @click.prevent="$refs.slideUploader.active = false">
                                            <i class="fa fa-stop" aria-hidden="true"></i>Stop Upload
                                            </button>
                                    
                                    </div>

                                    </div>
                                </div>
                            </div>
                        </b-card-body>
                    </b-collapse>
                </b-card>
            
                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button block v-b-toggle.accordion-2 variant="primary">Create Empty Slide</b-button>
                    </b-card-header>
                    <b-collapse id="accordion-2"  accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text>
                                <div class="text-center">
                                    <span class="text-secondary small">
                                        {{ "This will create an empty slide without any background" }}
                                    </span>
                                </div>
                            </b-card-text>
                            <div class="text-center my-4">
                                <button class="btn btn-success" @click="createEmptySlide()">Create Empty Slide</button>
                            </div>                            
                        </b-card-body>
                    </b-collapse>
                </b-card>




          
            </div>


        </b-modal>
    </div>
</template>

<script scoped>

import FileUpload from 'vue-upload-component'

export default {
  components: {
    FileUpload,
  },
  props: {
    csrf_token: {
      type: String
    },
    file_id: {
      type: Number
    },
    folder_id: {
        type: [String, Number],
        required: true        
    },
  },
  data() {
    return {
      files: [],
      lesson_schedule_id: null,
    };
  },
  methods: {
    updateValue(value) 
    {     
      this.files = value;
    },
    /**
     * Has changed
     * @param  Object|undefined   newFile   Read only
     * @param  Object|undefined   oldFile   Read only
     * @return undefined
     */
    inputFile: function(newFile, oldFile) 
    {
      if (newFile && oldFile && !newFile.active && oldFile.active) {

        if (newFile.xhr) {

            if ( newFile.xhr.status === 200) 
            {

                //Add to the $ref='folderComponent' - uploader/show.blade.php
                let file = {
                                'id'        : newFile.response.id,
                                'file_name' : newFile.response.file,
                                'path'      : newFile.response.path,
                                'fullpath'  : newFile.response.fullpath,
                                'size'      : newFile.response.size,
                                'owner'     : newFile.response.owner,
                            };

                this.$parent.userUploadedImage(file);      

                this.$refs['modalCreateNewSlide'].hide();                      

                //let files = this.$root.$refs.folderComponent.files.push(...file);
                //let files = this.$root.$refs.treeListComponent.$refs.folderFilesComponent.audioFiles.push(...file);

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
    inputFilter: function(newFile, oldFile, prevent) {
        if (newFile && !oldFile) {
        // Filter non-image file
        if (!/\.(jpeg|jpg|gif|png)$/i.test(newFile.name)) {
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
    createEmptySlide() {
        this.$parent.createNewSlide();
        this.$refs['modalCreateNewSlide'].hide();
    },
    prepareSlider(reservation, slide_index) {
        this.lesson_schedule_id = reservation.schedule_id;
        this.slide_index = slide_index;        
        this.$refs['modalCreateNewSlide'].show();
    }    
  }
};
</script>




