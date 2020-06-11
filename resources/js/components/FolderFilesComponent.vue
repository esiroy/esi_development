<template>
  <div class="example-drag">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>File Name</th>
          <th>File Size</th>
          <th v-if="(can_user_delete_uploads === true)">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="!files.length">
          <td colspan="7" align="center">
            <h4>No Files</h4>
          </td>
        </tr>
        <tr v-for="(file, index) in files" :key="file.id">
          <td>{{index + 1}}</td>
          <!--
          <td>
            <img v-if="file.path" :src="file.path" width="40" height="auto" />
            <span v-else>No Image</span>
          </td>
          -->
          <td>
            <div class="filename">{{file.file_name}}</div>
          </td>
          <td>
            <div class="filesize">{{ file.size | formatSize }}</div>
          </td>
          <td v-if="(can_user_delete_uploads === true)">
            <div class="dropdown" v-if="(can_user_delete_uploads === true)">
               
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" v-if="(can_user_delete_uploads === true)">
                    <a class="dropdown-item" v-on:click="deleteFile(index, file.id)">Delete</a>
                </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
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
    folder_id: {
        type: String
    },
    folder_files: {
        type: Array
    },
    can_user_delete_uploads: {
      type: Boolean
    }
  },
  data() {
    return {
      files: []
    };
  },
  methods: {
    deleteFile(index, id) {

      axios.post(
            "/file/" + id,
            { 
                _method: "delete",
                id: id
            }
        )
        .then(response => {
            //success
            //console.log(response.data);
            this.files.splice(index, 1);
            
        })
        .catch(function(error) {
            // handle error
            alert("Error " + error);
            console.log(error);
        });


    }
 },
 mounted: function () {
  this.$nextTick(function () {
    // Code that will run only after the
    // entire view has been rendered

    this.files = this.folder_files;

    console.log(this.folder_files)
  })
}

};
</script>