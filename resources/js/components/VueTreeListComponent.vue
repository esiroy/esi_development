<template>
	<div class="row">
		<div class="col-md-4">
			<div class="card">
				<div class="card-header">Folders</div>
				<div class="card-body">
					<b-modal id="loadingModal" ref="loadingModal" title="Please wait">
						<b-spinner class="m-5" label="Busy"></b-spinner>
					</b-modal>

					<div class="createNewFolderContainter">
						<div class="mb-4" v-if="(can_user_create_folder === true)">
							<b-button v-b-modal.createNewFolder>Create New Folder</b-button>
						</div>

						<b-modal
							id="createNewFolder"
							ref="modalCreateNewFolder"
							title="Create New Folder"
							@show="resetModal"
							@hidden="resetModal"
							@ok="handleOk"
						>
							<form ref="form" @submit.stop.prevent="handleSubmit">
								<b-form-group
									:state="folderNameState"
									label="Name"
									label-for="name-input"
									:invalid-feedback="invalidFeedbackMessage"
								>
									<b-form-input id="name-input" v-model="folderName" :state="folderNameState" required></b-form-input>
								</b-form-group>

								<b-form-group
									:state="folderDescriptionState"
									label="Description"
									label-for="description-input"
									invalid-feedback="Description is required"
								>
									<b-form-textarea
										id="description-input"
										v-model="folderDescription"
										:state="folderDescriptionState"
									></b-form-textarea>
								</b-form-group>
							</form>
						</b-modal>
					</div>

					<div class="createSubMenuModalContainer">
						<b-modal
							id="createNewSubFolder"
							ref="modal"
							title="Create Sub Folder"
							@show="resetSubFolderModal"
							@hidden="resetSubFolderModal"
							@cancel="cancelModal"
							@close="closeModal"
							@ok="handleOk"
						>
							<form ref="form" @submit.stop.prevent="handleSubmit">
								<b-form-group
									:state="folderNameState"
									label="Name"
									label-for="name-input"
									:invalid-feedback="invalidFeedbackMessage"
								>
									<b-form-input id="name-input" v-model="folderName" :state="folderNameState" required></b-form-input>
								</b-form-group>

								<b-form-group
									:state="folderDescriptionState"
									label="Description"
									label-for="description-input"
									invalid-feedback="Description is required"
								>
									<b-form-textarea
										id="description-input"
										v-model="folderDescription"
										:state="folderDescriptionState"
									></b-form-textarea>
								</b-form-group>
							</form>
						</b-modal>
					</div>

					<div class="editFolderModalContainer">
						<b-modal
							id="editFolder"
							ref="modalEditFolder"
							title="Edit Folder"
							@show="resetEditFolderModal"
							@hidden="resetEditFolderModal"
							@cancel="cancelModal"
							@close="closeModal"
							@ok="handleOk"
						>
							<form ref="form" @submit.stop.prevent="handleSubmit">
								<b-form-group
									:state="folderNameState"
									label="Name"
									label-for="name-input"
									:invalid-feedback="invalidFeedbackMessage"
								>
									<b-form-input id="name-input" v-model="folderName" :state="folderNameState" required></b-form-input>
								</b-form-group>

								<b-form-group
									:state="folderDescriptionState"
									label="Description"
									label-for="description-input"
									invalid-feedback="Description is required"
								>
									<b-form-textarea
										id="description-input"
										v-model="folderDescription"
										:state="folderDescriptionState"
									></b-form-textarea>
								</b-form-group>
							</form>
						</b-modal>
					</div>

					<vue-tree-list
						v-bind:default-expanded="true"
						@click="onClick"
						@change-name="onChangeName"
						@delete-node="onDel"
						@add-node="onCreateNewSubFolder"
						@drop="onMoveInto"
						@drop-before="onInsertBefore"
						@drop-after="onInsertAfter"
						:model="data"
						default-tree-node-name="New Folder"
						default-leaf-node-name="New Page"
					>
						<span class="icon" slot="addTreeNodeIcon">
                            <a href="#" name="addNode" class="addNode">
                                <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-folder-plus mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M9.828 4H2.19a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91H9v1H2.826a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3h3.982a2 2 0 0 1 1.992 2.181L15.546 8H14.54l.265-2.91A1 1 0 0 0 13.81 4H9.828zm-2.95-1.707L7.587 3H2.19c-.24 0-.47.042-.684.12L1.5 2.98a1 1 0 0 1 1-.98h3.672a1 1 0 0 1 .707.293z"/>
                                    <path fill-rule="evenodd" d="M13.5 10a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1H13v-1.5a.5.5 0 0 1 .5-.5z"/>
                                    <path fill-rule="evenodd" d="M13 12.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0v-2z"/>
                                </svg>
                            </a>
                        </span>
						<span class="icon" slot="addLeafNodeIcon">＋</span>
						<span class="icon" slot="editNodeIcon">
							<a href="#" @click.stop.prevent="onEditFolder($event)" class="editNode">
                                <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-window mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M14 2H2a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1zM2 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2z"/>
                                    <path fill-rule="evenodd" d="M15 6H1V5h14v1z"/>
                                    <path d="M3 3.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm1.5 0a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm1.5 0a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                                </svg>
                            </a>
						</span>
						<span class="icon" slot="delNodeIcon">
                            <a href="#" name="deleteNode" class="deleteNode">
                                <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-trash mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                </svg>
                            </a>
                        </span>
						<span class="icon" slot="leafNodeIcon">🍃</span>
						<span class="icon" slot="treeNodeIcon">
                            <svg class="bi bi-folder mr-2" width="1.2em" height="1.2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.828 4a3 3 0 0 1-2.12-.879l-.83-.828A1 1 0 0 0 6.173 2H2.5a1 1 0 0 0-1 .981L1.546 4h-1L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3v1z"/>
                                <path fill-rule="evenodd" d="M13.81 4H2.19a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4zM2.19 3A2 2 0 0 0 .198 5.181l.637 7A2 2 0 0 0 2.826 14h10.348a2 2 0 0 0 1.991-1.819l.637-7A2 2 0 0 0 13.81 3H2.19z"/>
                            </svg>
                        </span>
					</vue-tree-list>

					<!--<button @click="getNewTree">Get new tree</button>
					<pre>
                        {{newTree}}
                        </pre>
					-->
				</div>
			</div>
		</div>

		<!-- DISPLAY FOLDER DETAILS -->
		<div class="col-md-8">
			<div class="card mb-4" v-if="this.folderCurrentID !== null">
				<div class="card-header">Folder Details</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-3">Folder ID:</div>
						<div class="col-md-9">
							<a
								:href="this.displayFolderLink"
								target="_blank"
								v-if="this.displayFolderLink"
							>{{ this.displayfolderID }}</a>
							<span v-else>{{ this.displayfolderID }}</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">Folder Name:</div>
						<div class="col-md-9">
							<a
								:href="this.displayFolderLink"
								target="_blank"
								v-if="this.displayFolderLink"
							>{{ this.displayFolderName }}</a>
							<span v-else>{{ this.displayFolderName }}</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">Folder Description</div>
						<div class="col-md-9">{{ this.displayFolderDesc }}</div>
					</div>
				</div>
			</div>

			<div class="card my-4" v-if="can_user_upload">
				<div class="card-body">
					<admin-uploader-component
						ref="uploaderComponent"
						:user_can_delete="'true'"
						:folder_id="this.folderCurrentID"
						:csrf_token="this.folderCurrentToken"
					/>
				</div>
			</div>

			<div class="card">
				<folder-files-component
					ref="folderFilesComponent"
					:folder_files="this.files"
					:can_user_delete_uploads="can_user_delete_uploads"
				/>
			</div>
		</div>


        <ul id="right-click-menu" tabindex="-1" v-if="viewMenu" v-bind:style="{ top: this.top, left: this.left }">
            <li @click="contextMenuGetLink">
                <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-clipboard mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                <path fill-rule="evenodd" d="M9.5 1h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                </svg>
                Copy Folder Link
            </li>
            <li @click="contextMenuCreate" v-if="can_user_create_folder">
                <svg class="bi bi-folder mr-2" width="1.2em" height="1.2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.828 4a3 3 0 0 1-2.12-.879l-.83-.828A1 1 0 0 0 6.173 2H2.5a1 1 0 0 0-1 .981L1.546 4h-1L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3v1z"/>
                    <path fill-rule="evenodd" d="M13.81 4H2.19a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4zM2.19 3A2 2 0 0 0 .198 5.181l.637 7A2 2 0 0 0 2.826 14h10.348a2 2 0 0 0 1.991-1.819l.637-7A2 2 0 0 0 13.81 3H2.19z"/>
                </svg>
                 Create Folder
            </li>
            <li @click="contextMenuEdit"  v-if="can_user_edit_folder">
                
                <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-window mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M14 2H2a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1zM2 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2z"/>
                    <path fill-rule="evenodd" d="M15 6H1V5h14v1z"/>
                    <path d="M3 3.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm1.5 0a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm1.5 0a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                </svg>
                Edit Folder
            </li>
            <li @click="contextMenuDelete"  v-if="can_user_delete_folder">
                <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-trash mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                </svg>
                Delete Folder
            </li>
        </ul>

	</div>
</template>

<script>
import qs from "qs";
import { VueTreeList, Tree, TreeNode } from "vue-tree-list";
export default {
	components: {
		VueTreeList
	},
	props: {
		public: {
			type: Boolean
		},
		public_folder_id: {
			type: Number
		},
		folders: {
			type: Array
		},
		folder_files: {
			type: Array
		},
		can_user_upload: {
			type: Boolean
		},
		can_user_delete_uploads: {
			type: Boolean
		},
		can_user_create_folder: {
			type: Boolean
        },
		can_user_edit_folder: {
			type: Boolean
        },
		can_user_delete_folder: {
			type: Boolean
		},
		csrf_token: {
			type: String
		},
		api_token: {
			type: String
		},
		folder_id: {
			type: String
		}
	},
	data() {
		return {
			firstLoad: true,
            parentID: null,
			URLEndPoint: "",
            prevIDClicked: null,
			//Files
            files: [],
            
            //context Menu
            contextMenuPermalink: null,

			//feedbacks
			invalidFeedbackMessage: "Name is required",

			//Display Only
			displayfolderID: "",
			displayFolderLink: "",
			displayFolderName: "",
			displayFolderDesc: "",

			//Modals Inputs
			folderID: "",
			folderName: "",
			folderDescription: "",

            //Modal Variable State
			folderNameState: null,
			folderDescriptionState: null,

			//new folder initialize
			currentNodeCreated: {
				id: null,
				name: null,
				description: null
			},

			//Folder
			folderCurrentID: null,
			folderCurrentToken: this.csrf_token,

			//The Data for the Tree
            data: new Tree(this.folders),
            newTree: {},

            //context menu
            viewMenu: false,
            top: "0px",
            left: "0px"
		}
	},

	beforeMount() {},
	mounted() {
        this.getFolders();

        let elements = document.getElementsByClassName("vtl");
        Array.from(elements).forEach((element) => {
            element.addEventListener('contextmenu', () => this.openMenu(event, element));
        });
        document.addEventListener('click', () => this.closeMenu(event));
	},
	methods: {
        getPermalink(oldNode, id) {
            var newNode = {};
            for (var k in oldNode) {
                if (k !== "children" && k !== "parent") {
                    newNode[k] = oldNode[k];
                }
            }

            if (oldNode.children && oldNode.children.length > 0) {
                newNode.children = [];
                for (var i = 0, len = oldNode.children.length;i < len;i++) {
                    if (oldNode.children[i].id == id) {
                        this.contextMenuPermalink = (oldNode.children[i].permalink) ;
                    }
                    newNode.children.push(
                        this.getPermalink(oldNode.children[i], id)
                    );
                }
            }
        },
        contextMenuGetLink()
        {
			let link = this.getPermalink(this.data, this.parentID);
            this.textToClipboard(this.contextMenuPermalink);
        },
        textToClipboard (text) {
            let dummy = document.createElement("textarea");
            document.body.appendChild(dummy);
            dummy.value = text;
            dummy.select();
            document.execCommand("copy");
            document.body.removeChild(dummy);
        },
        contextMenuCreate() {
            document.getElementById(this.parentID).getElementsByClassName("addNode")[0].click();
        },
        contextMenuEdit() {
            document.getElementById(this.parentID).getElementsByClassName("editNode")[0].click();
        },
        contextMenuDelete() {
            document.getElementById(this.parentID).getElementsByClassName("deleteNode")[0].click();
        },
		setMenu: function(event) {
            this.left = (event.clientX) + "px";
            this.top = (event.clientY) + "px";
		},
		closeMenu: function(e) {
            this.viewMenu = false;
        },
        getParentID(element) {
           if (typeof(element.id) == 'undefined' || typeof(element.id) == null || element.id == 'undefined' || element.id == '' ) 
           {
               this.getParentID(element.parentElement);
           } else {
                this.parentID = element.id;
           }
        },
		openMenu: function(event, element) {

            this.getParentID(event.target);
            console.log(this.parentID)

            this.viewMenu = true;
            Vue.nextTick(function() {
                this.setMenu(event)
            }.bind(this))

			event.preventDefault();
		},
		getFolders() {
			if (this.public === true) {
				this.URLEndPoint = "/api/get_child_folders";
				this.getPublicFolders();
			} else {
				this.URLEndPoint =
					"/api/get_folders?api_token=" + this.api_token;
				this.getPrivateFolder();
			}
		},
        getPublicFolders() 
        {
			axios.post(this.URLEndPoint, {
					method: "POST",
					public_folder_id: this.public_folder_id
				})
				.then(response => {
					this.data = new Tree(response.data.folders);

					this.$nextTick(function() {
						if (this.public === true) {
							this.autoClickFolder(this.data.children[0]);
						}
					});
				})
				.catch(function(error) {
					console.log(error);
				});
		},
        getPrivateFolder() 
        {
			axios.post(this.URLEndPoint, {
					method: "POST"
				})
				.then(response => {
					this.data = new Tree(response.data.folders);
					this.$nextTick(function() {
						if (this.firstLoad === true) {
							this.autoClickFolder(this.data.children[0]);
							this.firstLoad = false;
						}
					});
				})
				.catch(function(error) {
					console.log(error);
				});
		},
		autoClickFolder(data) {

			let nodeItem = {
				id: data.id,
				name: data.name,
				description: data.description,
				permalink: data.permalink
			};

			this.onClick(nodeItem);
		},
		getFolderFiles(folderID) {
			if (this.public === true) {
				this.URLEndPoint = "/api/get_public_folder_files";
			} else {
				this.URLEndPoint =
					"/api/get_folder_files?api_token=" + this.api_token;
			}

			axios.post(this.URLEndPoint, {
					method: "POST",
					folder_id: folderID
				})
				.then(response => {
					//display the folder attributes (id, name, description)
					//console.log(response.data.files);

                    /*
                    this.displayFolderLink = response.data.permalink;
                    this.displayFolderName = response.data.folder_name;
                    this.displayFolderDesc = response.data.folder_description;
                    this.displayFolderLink = response.data.permalink;
                    */

					//set the name of modal
					this.folderID = response.data.folder_id;
					this.folderName = response.data.folder_name;
					this.folderDescription = response.data.folder_description;

                    this.$root.$refs.treeListComponent.$refs.folderFilesComponent.files = response.data.files;
				})
				.catch(function(error) {
					console.log(error);
				});
		},
		onClick(node) {
			/*
			console.log(node.id);
			console.log('name', node.name);
			console.log('description', node.description);
            console.log('permalink', node.permalink);
            */

			//set the display info
			this.displayfolderID = node.id;
			this.displayFolderName = node.name;
			this.displayFolderDesc = node.description;
			this.displayFolderLink = node.permalink;

			this.folderCurrentID = node.id.toString();
			this.getFolderFiles(node.id);

			//reset the uploader and files
			if (this.can_user_upload) {
				this.$root.$refs.treeListComponent.$refs.uploaderComponent.files = [];
			}
			this.$root.$refs.treeListComponent.$refs.folderFilesComponent.files = [];

			if (this.prevIDClicked) {

                try {
                    document.getElementById(this.prevIDClicked).getElementsByClassName("vtl-node-main")[0].removeAttribute("style");
                }
                catch(err) {
                    console.log(err.message);
                }
				
			}

			document.getElementById(node.id).getElementsByClassName("vtl-node-main")[0].style.backgroundColor = "#f0f0f0";
			this.prevIDClicked = node.id;
		},
		onEditFolder(event) {
            let id = event.target.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.id;

            if (id) {
                this.folderID = id;
            } else {
                this.folderID = this.parentID; //this is only on context menu
            }

			this.folderCurrentID = id.toString();
            this.getFolderFiles(this.folderID);
            
            //reset the uploader and files
            if (this.can_user_upload) {
                this.$root.$refs.treeListComponent.$refs.uploaderComponent.files = [];
            }
            this.$root.$refs.treeListComponent.$refs.folderFilesComponent.files = [];

            
			if (this.prevIDClicked) {
               try {
                    document.getElementById(this.prevIDClicked).getElementsByClassName("vtl-node-main")[0].removeAttribute("style");
                }
                catch(err) {
                    console.log(err.message);
                }
			}

			document.getElementById(this.folderID).getElementsByClassName("vtl-node-main")[0].style.backgroundColor = "#f0f0f0";
            this.prevIDClicked = this.folderID;
            
            
			this.$bvModal.show("editFolder");
		},
		onCreateNewSubFolder(params) {
			this.$bvModal.show("createNewSubFolder");
            this.currentNodeCreated = params;
		},
		onDel(node) {
            if( confirm('This folder together with its subfolders will be deleted permanently, are you sure that you want to continue?') ) {
                axios.post("/api/delete_folder?api_token=" + this.api_token, {
                        method: "POST",
                        id: node.id
                    })
                    .then(response => {
                        node.remove();
                    })
                    .catch(function(error) {
                        // handle error
                        alert("Error " + error);
                        console.log(error);
                    });
            }
		},
		onChangeName(id) {
			let folder = {
				id: id,
				name: this.folderName,
				description: this.folderDescription
			};
			_updateFolder(this.data, folder);

			function _updateFolder(oldNode, folder) {
				var newNode = {};

				for (var k in oldNode) {
					if (k !== "children" && k !== "parent") {
						newNode[k] = oldNode[k];
					}
				}

				if (oldNode.children && oldNode.children.length > 0) {
					newNode.children = [];
					for (var i = 0, len = oldNode.children.length;i < len;i++) {
						if (oldNode.children[i].id === id) {
							oldNode.children[i].name = folder.name;
							oldNode.children[i].description = folder.description;
						}
						newNode.children.push(
							_updateFolder(oldNode.children[i], folder)
						);
					}
				}
				return newNode;
			}
		},
		handleSubmit() {
			// Exit when the form isn't valid
			if (!this.checkFormValidity()) {
				return;
			}

			if (this.FolderType == "rootFolder") {
				//Create the root folder
				this.createFolderOnServer(0);
	
			} else if (this.FolderType == "subFolder") {
				//create the subfolder
				this.createFolderOnServer(this.currentNodeCreated.parent.id);
		
			} else if (this.FolderType == "editFolder") {
				this.updateFolderOnServer(this.folderID);

			}
		},
		onAddNode(params) {
			/*
			params.folderId  = this.folderID;
			params.folderName = this.folderName;
			params.folderDescription = this.folderDescription;
			*/

			this.getFolders();
			console.log("on add node ", params);
		},
		addNode(item) {
			var node = new TreeNode({
				id: item.id,
				pid: item.parent_id,
				name: item.folder_name,
				description: item.folder_description,
				isLeaf: false,
				addLeafNodeDisabled: true
			});
			if (!this.data.children) this.data.children = [];
			this.data.addChildren(node);

			//////this.getFolders();
		},
		createFolderOnServer(parent_id) {
			axios.post("/api/create_folder?api_token=" + this.api_token, {
					method: "POST",
					parent_id: parent_id,
					folder_name: this.folderName,
					folder_description: this.folderDescription
				})
				.then(response => {
					if (response.data.success === false) {
						this.invalidFeedbackMessage = response.data.message;
						this.folderNameState = false;
					} else {
						//success
						//console.log("create on folder -- id " + response.data.folder.id)

						//this.folderID = response.data.folder.id;
						//console.log("new folder id ", this.folderID);

						if (this.FolderType == "rootFolder") {
                            this.addNode(response.data.folder);
                            this.$bvModal.hide("createNewFolder");
						} else if (this.FolderType == "subFolder") {
							this.currentNodeCreated.id = response.data.folder.id;
							this.currentNodeCreated.name = this.folderName;
							this.currentNodeCreated.description = this.folderDescription;
							this.currentNodeCreated.addLeafNodeDisabled = true;
                            this.$bvModal.hide("createNewSubFolder");
                        }

                        this.$nextTick(function() {
                            let nodeItem = {
                                id: response.data.folder.id,
                                name: this.folderName,
                                description: this.folderDescription,
                                permalink: response.data.folder.permalink
                            };
                            this.onClick(nodeItem);

                            this.getFolders();
                        });

					}
				})
				.catch(function(error) {
					// handle error
					alert("Error " + error);
					console.log(error);
				});
        },
        
		updateFolderOnServer(folderID) {
			axios
				.post("/api/update_folder?api_token=" + this.api_token, {
					method: "POST",
					folder_id: folderID,
					folder_name: this.folderName,
					folder_description: this.folderDescription
				})
				.then(response => {
					if (response.data.success === false) {
						this.invalidFeedbackMessage = response.data.message;
						this.folderNameState = false;
					} else {
						//success
						this.folderID = response.data.folder.id;
						this.folderName = response.data.folder.folder_name;
						this.folderDescription = response.data.folder.folder_description;

						//display folder
						this.displayfolderID = response.data.folder.id;
						this.displayFolderName = response.data.folder.folder_name;
						this.displayFolderDesc = response.data.folder.folder_description;
						this.displayFolderLink = response.data.folder.permalink;

						this.onChangeName(this.folderID);
                        this.$bvModal.hide("editFolder");
                        
                        this.$nextTick(function() {
                            console.log("")
                            let nodeItem = {
                                id: response.data.folder.id,
                                name: response.data.folder.folder_name,
                                description: response.data.folder.folder_description,
                                permalink: response.data.folder.permalink
                            }
                            this.onClick(nodeItem);

                            this.getFolders();
                        });

                        
					}
				})
				.catch(function(error) {
					// handle error
					//alert("Error " + error);
					console.log(error);
				});
		},
		onMoveInto(item) {
			//console.log("Move Into Folder");

			let { node, src, target } = item;

			//console.log({node}, {src}, {target});

			let nodeItem = {
				id: node.id,
				name: node.name,
				description: node.description
			};

			let parentNode = {
				id: src.id,
				name: src.name,
				description: src.description
			};

			let targetNode = {
				id: target.id,
				name: target.name,
				description: target.description
			};

            if (node.id !== target.id) {
                this.moveIntoParentItem(nodeItem, parentNode, targetNode);
            }
			
		},
		onInsertBefore(item) {
			//console.log("onInsertBefore");

			let { node, src, target } = item;

			//console.log(node.parent);

			let nodeItem = {
				id: node.id,
				name: node.name,
				description: node.description
			};

			let parentNode = {
				id: src.id,
				name: src.name,
				description: src.description
			};

			let targetNode = {
				id: target.id,
				name: target.name,
				description: target.description
			};

			this.reorderItems(nodeItem, parentNode, targetNode);
		},
		onInsertAfter(node) {
			//console.log("onInsertAfter");
			var { node, src, target } = node;
			//console.log({ node }, { src }, { target });
		},
		getNewTree() {
			var vm = this;
			function _dfs(oldNode) {
				var newNode = {};

				for (var k in oldNode) {
					if (k !== "children" && k !== "parent") {
						newNode[k] = oldNode[k];
					}
				}

				if (oldNode.children && oldNode.children.length > 0) {
					newNode.children = [];
					for (
						var i = 0, len = oldNode.children.length;
						i < len;
						i++
					) {
						newNode.children.push(_dfs(oldNode.children[i]));
					}
				}
				return newNode;
			}

			vm.newTree = _dfs(vm.data);
		},
		//Modal Methods Starts Here
		checkFormValidity() {
			const valid = this.$refs.form.checkValidity();
			this.folderNameState = valid;
			return valid;
		},
		cancelModal() {
			//console.log("cancelled")
			if (this.FolderType == "subFolder") {
				this.currentNodeCreated.remove();
			}
		},
		closeModal() {
			//console.log("closed");

			if (this.FolderType == "subFolder") {
				this.currentNodeCreated.remove();
			}
		},
		resetModal() {
			this.FolderType = "rootFolder";
			this.folderName = "";
			this.folderDescription = "";
			this.folderNameState = null;
			this.folderDescriptionState = null;
		},
		resetSubFolderModal() {
			this.FolderType = "subFolder";
			this.folderName = "";
			this.folderDescription = "";
			this.folderNameState = null;
			this.folderDescriptionState = null;
		},
		resetEditFolderModal() {
			this.FolderType = "editFolder";
			this.folderNameState = null;
			this.folderDescriptionState = null;
		},
		handleOk(bvModalEvt) {
			// Prevent modal from closing
			bvModalEvt.preventDefault();
			// Trigger submit handler
			this.handleSubmit();
		},



		showLoading() {
			this.$bvModal.show("loadingModal");
		},
		hideLoading() {
			this.$bvModal.hide("loadingModal");
		},
		moveIntoParentItem(node, src, target) {
			axios
				.post("/api/move_into_parent?api_token=" + this.api_token, {
					method: "POST",
					node: node,
					src: src,
					target: target
				})
				.then(response => {
					if (response.data.success === false) {
						alert(response.data.message);
						this.getFolders();
					} else {
						this.displayFolderLink = response.data.folder.permalink;
						console.log(this.displayFolderLink);
                        this.$bvModal.hide("loadingModal");
                        this.$nextTick(function() {
                            this.getFolders();
                        });
					}
				})
				.catch(function(error) {
					// handle error
					console.log(error);
				});
		},
		reorderItems(node, src, target) {
			//this.showLoading();

			console.log("reordering items", { node }, { src }, { target });
			axios
				.post("/api/reorder_items?api_token=" + this.api_token, {
					method: "POST",
					node: node,
					src: src,
					target: target
				})
				.then(response => {
					if (response.data.success === false) {
						alert(response.data.message);
						this.getFolders();
					} else {
                        this.$nextTick(function() {
                            this.getFolders();
                        });
                    }
				})
				.catch(function(error) {
					// handle error
					console.log(error);
				});
		}
	}
};
</script>

<style lang="scss">
.vtl {
    .vtl-tree-margin {
        margin-left: 1em;
        cursor: pointer;
    }
    .vtl-node-main {
        padding: 3px 0px 3px 1rem;
        .vtl-caret {
            margin-left: -1rem;
            position: relative;
            top: 3px;
        }
    }
    .vtl-up {
        margin-top: 0px;
    }
    .vtl-border {
        height: 3px;
    }

    /*
	.vtl-drag-disabled {
		background-color: #d0cfcf;
		&:hover {
			background-color: #d0cfcf;
		}
	}
	.vtl-disabled {
		 background-color: #d0cfcf; 
    }
    */
    .deleteNode {
        color: #dc3545
    }

    .deleteNode:hover {
        color: #FF0000
    }
}

.icon {
	&:hover {
		cursor: pointer;
	}
}


#right-click-menu {
	background: #fafafa;
	border: 1px solid #bdbdbd;
	box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14),
		0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
	display: block;
	list-style: none;
	margin: 0;
	padding: 0;
	position: fixed;
	width: 250px;
	z-index: 999999;
}

#right-click-menu li {
	border-bottom: 1px solid #e0e0e0;
	margin: 0;
	padding: 5px 35px;
}

#right-click-menu li:last-child {
	border-bottom: none;
}

#right-click-menu li:hover {
	background: #1e88e5;
	color: #fafafa;
}


</style>