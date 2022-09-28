<template>
   
    <div class="container">
     
        <div class="card-body text-center py-4" v-show="loading == true">
            <div class="spinner-grow text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <div class="spinner-grow text-secondary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <div class="spinner-grow text-success" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
       

            
        <div class="card" v-if="files.length == 0"> 
            <div class="card-body text-center py-4">
                No files found on this folder
            </div>
        </div>

        <div class="card" v-else-if="files.length >= 1">

            <div class="shareFileContainer"> 
                <b-modal
                    id="shareFile"
                    ref="modalShareFile"
                    :title="'Share File'"
                    @show="resetShareModal"
                    @hidden="resetShareModal"
                    @ok="handleOk">

                    <form ref="form" @submit.stop.prevent="handleSubmit">

                        <div class="permalinks">
                            <div class="form-group">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-9 px-0">
                                            <input readonly type="text" class="form-control pr-2" :value="this.contextMenuPermalink" placeholder="Permalink">
                                        </div>
                                        <div class="col-3 pl-2 pr-0">
                                            <button type="button" class="col-12 btn btn-outline-primary" v-clipboard:copy="contextMenuPermalink" v-clipboard:success="onCopy" v-clipboard:error="onError">
                                                <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-clipboard mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                                <path fill-rule="evenodd" d="M9.5 1h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                                </svg>
                                                Copy
                                            </button>
                                        </div>

                                        <div id="fade" class="d-none col-12 rounded px-2 py-1 my-2 bg-primary text-white ">
                                            <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-check2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                            </svg>
                                                Link Copied
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <multiselect v-model="sharingValues" deselect-label="Can't remove this value" track-by="code" label="name" placeholder="Select one" 
                        :options="sharingOptions" :searchable="false" :allow-empty="false">
                            <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.name }}</strong></template>
                        </multiselect>
                        <br>
                        <span v-if="this.sharingValues.code === 'private'">Share With Specific Users</span>
                        <multiselect v-if="this.sharingValues.code === 'private'"
                            v-model="userValues" tag-placeholder="Add this as new user" 
                            placeholder="Search or add a user" label="name" 
                            track-by="code" 
                            :options="userOptions" 
                            :multiple="true" 
                            :taggable="true" 
                            @tag="addTag">
                        </multiselect>
                    </form>
                </b-modal>
            </div>


            <div class="list" v-show="(this.view == 'list' || this.view == 'lists')">
                <div class="card-header">Files</div>

                <div class="card-body table-responsive">
                    <table class="table table-borderless table-hover">
                        <thead >
                            <tr>
                            
                                <th>File Name</th>
                                <th>File Size</th>
                                <!--<th>Owner</th>
                                <th>Action</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="!files.length">
                                <td colspan="7" align="center">
                                    <h4>No Files</h4>
                                </td>
                            </tr>
                            <tr :id="index" v-on:click.right="openMenu" v-for="(file, index) in files" :key="index">
                                <td>
                                    <div class="filename">
                                        <a :href="'/file/'+file.id" target="_blank">{{file.file_name}}</a>
                                    </div>
                                </td>
                                <td>
                                    <div class="filesize">{{ file.size | formatSize }}</div>
                                </td>

                                <!--
                                <td>
                                    <div class="owner">
                                        {{ file.owner.first_name }} {{ file.owner.last_name }}
                                    </div> 
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
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item small" v-on:click="deleteFile(index, file.id)" v-if="(can_user_delete_uploads === true)">Delete</a>
                                        </div>
                                    </div>
                                </td>
                                -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="list-icon row px-2 py-2" v-show="(this.view == 'icon' || this.view == 'icons')"> 
                    
                <div class="col-md-3 " :id="index" v-on:click.right="openMenu" v-for="(file, index) in files" :key="index">               

                    <div class="card mb-2 hover-hand" v-on:click="openURL(baseURL('/file/'+file.id))">
                        <div class="filename text-center ">
                            <a :href="'/file/'+file.id" target="_blank">
                                <img :src="baseURL('/preview/show?url='+file.path)" class="img-responsive">
                            </a>
                        </div>
                        <div class="filename text-secondary text-center" style="font-size:10px"> {{ file.file_name }}</div>
                        <!--<div class="filesize">{{ file.size | formatSize }}</div>-->
                    </div>

                </div>

            </div>

            <ul class="right-click-menu" tabindex="-1" v-if="viewMenu" v-bind:style="{ top: this.top, left: this.left }">
                
                <li @click="contextmenuShareFile" v-if="can_user_share_uploads">
                    <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-share mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M11.724 3.947l-7 3.5-.448-.894 7-3.5.448.894zm-.448 9l-7-3.5.448-.894 7 3.5-.448.894z"/>
                    <path fill-rule="evenodd" d="M13.5 4a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zm0 10a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zm-11-6.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                    </svg>
                        Share File
                </li>

                <li @click="contextmenuViewFile" v-if="can_user_share_uploads">
                        <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-eye" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.134 13.134 0 0 0 1.66 2.043C4.12 11.332 5.88 12.5 8 12.5c2.12 0 3.879-1.168 5.168-2.457A13.134 13.134 0 0 0 14.828 8a13.133 13.133 0 0 0-1.66-2.043C11.879 4.668 10.119 3.5 8 3.5c-2.12 0-3.879 1.168-5.168 2.457A13.133 13.133 0 0 0 1.172 8z"/>
                        <path fill-rule="evenodd" d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>
                        View File
                </li>

                <li @click="contextMenuGetLink">
                    <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-clipboard mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                    <path fill-rule="evenodd" d="M9.5 1h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                    </svg>
                    Copy File Link
                </li>

                <li @click="contextMenuDelete"  v-if="can_user_delete_uploads">
                    <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-trash mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                    Delete File
                </li>
            </ul>
        </div>

    </div>






</template>

<style>

.hover-hand {
    cursor: pointer;
}
</style>

<script>
import Multiselect from 'vue-multiselect'
import VueClipboard from 'vue-clipboard2'
VueClipboard.config.autoSetContainer = true
Vue.use(VueClipboard)

export default {
	components: {
        Multiselect
	},
	props: {
        users: {
            type: Array
        },
        user: {
            type: Object
        },       
        public: {
            type:Boolean
        },
        can_user_upload: {
            type:Boolean
        },
        can_user_share_uploads: {
			type: Boolean
        },
		folder_id: {
			type: String
		},
		folder_files: {
			type: Array
		},
		can_user_delete_uploads: {
			type: Boolean
        },
		api_token: {
			type: String
		},
	},
	data() {
		return {
            files: [],
            //selected file
            file : {},
            //ID of the selected element
            currentFolderViewing: null,
            parentID: null,
             //context menus
            viewMenu: false,
            top: "0px",
            left: "0px",
            //contextMenuPermalink
            contextMenuPermalink: null,
            node: {},
            //Users Select
            userValues: [],
            userOptions: [],
            //Sharing
            modalType: null,
            sharingValues: [],
            sharingOptions: [
                { name: 'Public', code: 'public' },
                { name: 'Private', code: 'private' }
            ],
            loading: true,
            view: 'icon'
		};
    },
    created() {
        //context menus
        document.addEventListener('click', () => this.closeMenu(event));
    },
    methods:
    {
        openURL(url) {
        
            window.open(url);

        },
        baseURL(filePath) {
        
            return window.location.origin + filePath
        },
        openMenu: function(event, element) 
        {
            if (this.public == false) 
            {
                let userLists           = this.users;
                let userOptionsList     = userLists.filter((u) => { if (u.id !== this.user.id ) { return u } });
                this.userOptions        = userOptionsList;
            }
            this.getParentID(event.target);
            this.file = this.files[this.parentIndexID];
            this.userValues     = this.file.sharedTo;
            this.viewMenu       = true;
            this.setMenu(event)
            this.$forceUpdate();
            event.preventDefault();
        },
		closeMenu: function(e) {
            this.viewMenu = false;
        },
		setMenu: function(event) {
            this.left = (event.clientX) + "px";
            this.top = (event.clientY) + "px";
        },
        contextmenuViewFile()  {
            let url = this.createPageLink(this.file);
            let win = window.open(url, '_blank');
            win.focus();

        },
        contextmenuShareFile: function(event, element)  
        { 
            this.sharingValues           = {
                                            'code': this.file.privacy, 
                                            'name': this.file.privacy
                                        };
            this.contextMenuPermalink = this.createPageLink(this.file);
            this.$bvModal.show("shareFile");
        },
        contextMenuCreate() {
           //@create
        },
        contextMenuEdit() {
           //@edit
        },
        contextMenuDelete() {
            this.deleteFile(this.parentIndexID, this.file.id)
        },
        contextMenuGetLink()
        {
            //console.log(this.file.id);
            this.$nextTick(function(){
                console.log(this.createPageLink(this.file));
                this.textToClipboard(this.createPageLink(this.file));
            });
        },
        //Helpers
        createPageLink(file) {
             return window.location.protocol + "//" + window.location.host + "/file/" + file.id;
        },
        createLink(file) {
            return window.location.protocol + "//" + window.location.host + "/" + file.path;
        },
		copyFile(index, file) {
            let fileURL = this.createPageLink(file);
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
        addTag (newTag) {
            const tag = {
                name: newTag,
                code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
            }
            this.options.push(tag)
            this.value.push(tag)
        },
        //Modal
		handleOk(bvModalEvt) {
			// Prevent modal from closing
			bvModalEvt.preventDefault();
			// Trigger submit handler
			this.handleSubmit();
        },
        handleSubmit() {
            if (this.modalType == 'shareFile') 
            {
                this.shareFileOnServer(this.file.id);
            }
        },
        resetShareModal() {
            this.modalType = "shareFile";
            //this.sharingValue = [];
            //this.sharingOptions;
        },
        //Modal Action Callback
        onCopy: function (e) {
            this.fadeOut("fade", 2);
        },
        onError: function (e) {
            alert('Failed to copy texts')
        },
        fadeOut(id, seconds) {
            let element = document.getElementById(id); // get required element
            element.style.display="block"
            element.style.opacity = 1;
            element.classList.remove("d-none");

            var interval = setInterval(fadeOut, seconds * 1000); 
            function fadeOut() {
                (function fade(){
                    (element.style.opacity -=.1) < 0 ? element.style.display="none" : setTimeout(fade, 30)
                    
                })();
                clearInterval(interval);
            }
        },
        //recursive parent id fether
        getParentID(element) {
           if (typeof(element.id) == 'undefined' || typeof(element.id) == null || element.id == 'undefined' || element.id == '' ) 
           {
               this.getParentID(element.parentElement);
           } else {
                this.parentIndexID = element.id;
           }
        },
        //xhr
        shareFileOnServer(fileID) {
            axios.post("/api/share_file?api_token=" + this.api_token, 
            {
                method          : "POST",
                fileID          : fileID,
                privacy         : this.sharingValues.code,
                userValues      : this.userValues,
            })
            .then(response => 
            {
                if (response.data.success === false) {
                    this.invalidFeedbackMessage = response.data.message;
                    alert (this.invalidFeedbackMessage);
                } else {
                    this.$nextTick(function()
                    {
                        this.$root.$refs.treeListComponent.getFolderFiles(this.file.folder_id);
                        this.$bvModal.hide("shareFile");

                    });
                }
			}).catch(function(error) {
                // handle error
                alert("Error " + error);
                console.log(error);
			});
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
						("Error " + error);alert
						console.log(error);
					});
			}
		}
	},
	mounted: function() {

		this.$nextTick(function() {
			this.files = this.folder_files;

            this.loading = false;

            console.log(this.loading)
		});



	}
};
</script>