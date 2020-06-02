<template>
    <div class="example-drag">
        <div class="upload">
            <ul v-if="files.length">

                
                <li v-for="(file, index) in files" :key="file.id">
                    <span>{{ index + 1 }}</span> -
                     <img :src="file.blob" width="50" height="50" />
                    <span>{{ file.name }}</span> -
                    <span>{{ file.size }}</span>
                    -
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
                        <label for="file" class="btn btn-lg btn-primary"
                            >Select Files</label
                        >
                    </div>
                </td>
            </ul>

            <div
                v-show="$refs.upload && $refs.upload.dropActive"
                class="drop-active"
            >
                <h3>Drop files to upload</h3>
            </div>
            token :: {{ this.csrf_token }}
            <div class="example-btn">
                <file-upload
                    name="file"
                    class="btn btn-primary"
                    
                    post-action="fileUploader"
                    :headers="{ 'X-CSRF-TOKEN': this.csrf_token }"
                  
                    :multiple="true"
                    :drop="true"
                    :drop-directory="true"
                    @input="updatetValue"
                    extensions="jpeg,jpg,gif,pdf,mp3,wav,png,webp, mpeg"
                    accept="image/png, application/pdf, image/gif, audio/mpeg, audio/mpeg3, audio/x-mpeg-3, video/mpeg, image/jpeg, image/webp"
                    v-model="files"
                    @input-file="inputFile"
                    @input-filter="inputFilter"
                    ref="upload"
                >
                    <i class="fa fa-plus"></i>
                    Select files
                </file-upload>

                <button
                    type="button"
                    class="btn btn-success"
                    v-if="!$refs.upload || !$refs.upload.active"
                    @click.prevent="$refs.upload.active = true"
                >
                    <i class="fa fa-arrow-up" aria-hidden="true"></i>
                    Start Upload
                </button>

                <button
                    type="button"
                    class="btn btn-danger"
                    v-else
                    @click.prevent="$refs.upload.active = false"
                >
                    <i class="fa fa-stop" aria-hidden="true"></i>
                    Stop Upload
                </button>
            </div>
        </div>

        <div class="pt-5">
            Source code:
            <a
                href="https://github.com/lian-yue/vue-upload-component/blob/master/docs/views/examples/Drag.vue"
                >/docs/views/examples/Drag.vue</a
            >
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
export default {
  props: {
    csrf_token: {
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
      this.files = value
    },
    /**
     * Has changed
     * @param  Object|undefined   newFile   Read only
     * @param  Object|undefined   oldFile   Read only
     * @return undefined
     */
    inputFile: function (newFile, oldFile) {
      if (newFile && oldFile && !newFile.active && oldFile.active) {
        // Get response data
        console.log('response', newFile.response)
        if (newFile.xhr) {
          //  Get the response status code
          console.log('status', newFile.xhr.status)
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
    inputFilter: function (newFile, oldFile, prevent) {
      if (newFile && !oldFile) {
        // Filter non-image file
        if (!/\.(jpeg|jpe|jpg|gif|png|webp)$/i.test(newFile.name)) {
          return prevent()
        }
      }

      // Create a blob field
      newFile.blob = ''
      let URL = window.URL || window.webkitURL
      if (URL && URL.createObjectURL) {
        newFile.blob = URL.createObjectURL(newFile.file)
        console.log(newFile.blob)
      }
    }
  }
 
}
</script>