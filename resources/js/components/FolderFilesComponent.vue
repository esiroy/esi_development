<template>
	<div class="folder-files">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>File Name</th>
					<th>File Size</th>
					<th>Action</th>
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
					<td>
						<div class="filename">
							<a :href="'/file/'+file.id" target="_blank">{{file.file_name}}</a>
						</div>
					</td>
					<td>
						<div class="filesize">{{ file.size | formatSize }}</div>
					</td>
					<td>
						<div class="dropdown">
							<button class="btn btn-secondary btn-sm dropdown-toggle "
								type="button"
								id="dropdownMenuButton"
								data-toggle="dropdown"
								aria-haspopup="true"
								aria-expanded="false"
							>Action</button>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item small" :href="'/file/'+file.id" target="_blank">View File</a>
                                <a class="dropdown-item small" :href="createLink(file)" :download="file.file_name">Download File</a>
								<a class="dropdown-item small" v-on:click="copyFile(index, file)">Copy URL</a>
								<a class="dropdown-item small" v-on:click="deleteFile(index, file.id)" v-if="(can_user_delete_uploads === true)">Delete</a>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>

<style>
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
        createLink(file) {
            return window.location.protocol + "//" + window.location.host + "/" + file.path;
        },
		copyFile(index, file) {
            let fileURL = window.location.protocol + "//" + window.location.host + "/file/" + file.id;
            this.textToClipboard (fileURL)
        },
        textToClipboard (text) {
            let dummy = document.createElement("textarea");
            document.body.appendChild(dummy);
            dummy.value = text;
            dummy.select();
            document.execCommand("copy");
            document.body.removeChild(dummy);
        },
		deleteFile(index, id) {
            if (confirm("This file will be deleted permanently, are you sure that you want to continue?")) 
            {
				axios.post("/file/" + id, {
						_method: "delete",
						id: id
					})
					.then(response => {
						//console.log(response.data);
						this.files.splice(index, 1);
					})
					.catch(function(error) {
						// handle error
						alert("Error " + error);
						console.log(error);
					});
			}
		}
	},
	mounted: function() {
		this.$nextTick(function() {
			this.files = this.folder_files;
		});
	}
};
</script>