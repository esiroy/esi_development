<template>
  <div class="example-drag">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <!--<th>Thumb</th>-->
          <th>Name</th>
          <th>Size</th>
          <!--<th>Speed</th>-->
          <!--<th>Status</th>-->
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="!files.length">
          <td colspan="7" align="center">
            <h4>
              Drop files anywhere to upload
              <br />or
            </h4>
            <label for="file" class="btn btn-lg btn-primary">Select Files</label>
          </td>
        </tr>
        <tr v-for="(file, index) in files" :key="file.id">
          <td>{{index + 1}}</td>
          <!--
          <td>
            <img v-if="file.blob" :src="file.blob" width="40" height="auto" />
            <span v-else>No Image</span>
          </td>
          -->
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
          <!--<td>{{file.speed | formatSize}}</td>-->

          <!--
          <td v-if="file.error">{{file.error}}</td>
          <td v-else-if="file.success">success</td>
          <td v-else-if="file.active">active</td>
          <td v-else></td>
          -->

          <td>

            
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    
                    <!-- [EDIT]
                        <a :class="{'dropdown-item': true, disabled: file.active || file.success || file.error === 'compressing'}" href="#" @click.prevent="file.active || file.success || file.error === 'compressing' ? false :  onEditFileShow(file)">Edit</a>
                    -->
                    
                    <a :class="{'dropdown-item': true, disabled: !file.active}" href="#" @click.prevent="file.active ? $refs.upload.update(file, {error: 'cancel'}) : false">Cancel</a>

                    <a class="dropdown-item" href="#" v-if="file.active" @click.prevent="$refs.upload.update(file, {active: false})">Abort</a>
                    <a class="dropdown-item" href="#" v-else-if="file.error && file.error !== 'compressing' && $refs.upload.features.html5" @click.prevent="$refs.upload.update(file, {active: true, error: '', progress: '0.00'})">Retry upload</a>
                    <a :class="{'dropdown-item': true, disabled: file.success || file.error === 'compressing'}" href="#" v-else @click.prevent="file.success || file.error === 'compressing' ? false : $refs.upload.update(file, {active: true})">Upload</a>

                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" @click.prevent="$refs.upload.remove(file)">Remove</a>
                </div>
            </div>

          </td>
        </tr>
      </tbody>
    </table>


    <div class="upload">
        <!--
      <ul v-if="files.length">
        <li v-for="(file, index) in files" :key="file.id">
          <span>{{ index + 1 }}</span> -
          <img :src="file.blob" width="50" height="50" />
          <span>{{ file.name }}</span> -
          <span>{{ file.size }}</span>
          -
          <span class="progress" v-if="file.active || file.progress !== '0.00'">
            <div
              :class="{'progress-bar': true, 'progress-bar-striped': true, 'bg-danger': file.error, 'progress-bar-animated': file.active}"
              role="progressbar"
              :style="{width: file.progress + '%'}"
            >{{file.progress}}%</div>
          </span>
          <span v-if="file.error">{{ file.error }}</span>
          <span v-else-if="file.success">success</span>
          <span v-else-if="file.active">active</span>
          <span v-else></span>
        </li>
      </ul>
      <ul v-else>
        <td colspan="7">
          <div class="text-center p-5">
            <h4>
              Drop files anywhere to upload
              <br />or
            </h4>
            <label for="file" class="btn btn-lg btn-primary">Select Files</label>
          </div>
        </td>
      </ul>
      -->

        <div v-show="$refs.upload && $refs.upload.dropActive" class="drop-active">
            <h3>Drop files to upload</h3>
        </div>

      
        
        <div class="btn">
            <file-upload
                name="file"
                class="btn btn-primary"
                extensions="jpeg,jpg,gif,pdf,mp3,wav,png,webp,mpeg"
                accept="image/png, application/pdf, image/gif, audio/mpeg, audio/mpeg3, audio/x-mpeg-3, video/mpeg, image/jpeg, image/webp"
                v-model="files"
                post-action="/uploader/fileUploader"
                :headers="{'X-CSRF-TOKEN': this.csrf_token }"
                :multiple="true"
                :drop="true"
                :drop-directory="true"
                @input="updatetValue"
                @input-file="inputFile"
                @input-filter="inputFilter"
                :data="{folder_id: this.folder_id}"
                ref="upload">
                <i class="fa fa-plus"></i>
                Select files
            </file-upload>

        <button type="button" class="btn btn-success" v-if="!$refs.upload || !$refs.upload.active" @click.prevent="$refs.upload.active = true">
          <i class="fa fa-arrow-up" aria-hidden="true"></i>Start Upload
        </button>

        <button type="button" class="btn btn-danger" v-else @click.prevent="$refs.upload.active = false">
          <i class="fa fa-stop" aria-hidden="true"></i>Stop Upload
        </button>
        
      </div>
    </div>
  </div>
</template>

<style>
.example-drag label.btn {
  margin-bottom: 0;
  margin-right: 1rem;
}
.example-drag .drop-active {
  top: 0;
  bottom: 0;
  right: 0;
  left: 0;
  position: fixed;
  z-index: 9999;
  opacity: 0.6;
  text-align: center;
  background: #000;
}
.example-drag .drop-active h3 {
  margin: -0.5em 0 0;
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  -webkit-transform: translateY(-50%);
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
  font-size: 40px;
  color: #fff;
  padding: 0;
}
</style>

<script>

import FileUpload from 'vue-upload-component'

export default {
  components: {
    FileUpload,
  },
  props: {
    csrf_token: {
      type: String
    },
    folder_id: {
      type: String
    }
  },
  data() {
    return {
      files: []
    };
  },
  methods: {
    updatetValue(value) {
      this.files = value;
    },
    /**
     * Has changed
     * @param  Object|undefined   newFile   Read only
     * @param  Object|undefined   oldFile   Read only
     * @return undefined
     */
    inputFile: function(newFile, oldFile) {
       //console.log(this.folder_id);
       //console.log(this.csrf_token);
      if (newFile && oldFile && !newFile.active && oldFile.active) 
      {
        if (newFile.xhr) {
            if ( newFile.xhr.status === 200) 
            {
              //Add to the $ref='folderComponent' - uploader/show.blade.php
              let file = [{
                              'id'        : newFile.response.id,
                              'file_name' : newFile.response.file,
                              'size'      : newFile.response.size
                          }]

              let files = this.$root.$refs.folderComponent.files.push(...file);
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
        if (!/\.(jpeg|jpe|jpg|gif|png|webp|pdf|mp3|mp4|doc|docx)$/i.test(newFile.name)) {
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


    }
  }
};
</script>