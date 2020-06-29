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

									<b-form-textarea id="description-input" v-model="folderDescription" :state="folderDescriptionState"></b-form-textarea>

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
									<b-form-textarea id="description-input" v-model="folderDescription" :state="folderDescriptionState"></b-form-textarea>
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
									<b-form-textarea id="description-input" v-model="folderDescription" :state="folderDescriptionState"></b-form-textarea>
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
						<span class="icon" slot="addTreeNodeIcon">📂</span>
						<span class="icon" slot="addLeafNodeIcon">＋</span>
 						<span class="icon" slot="editNodeIcon">
							<a href="#" @click.stop.prevent="onEditFolder($event)">📃</a>
						</span>
						<span class="icon" slot="delNodeIcon">✂️</span>
						<span class="icon" slot="leafNodeIcon">🍃</span>
						<span class="icon" slot="treeNodeIcon">🌲</span>
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
				<div class="card-header">
					Folder Details
				</div>
				<div class="card-body">
					<div class="row"> 
						<div class="col-md-3"> Folder ID: 
						</div>
						<div class="col-md-9">
							<a :href="this.displayFolderLink" target="_blank">{{ this.folderID }}</a>
						</div>
					</div>
					<div class="row"> 
						<div class="col-md-3"> Folder Name: 
						</div>
						<div class="col-md-9">
							<a :href="this.displayFolderLink" target="_blank">{{ this.displayFolderName }}</a>
						</div>
					</div>
					<div class="row"> 
						<div class="col-md-3"> Folder Description 
						</div>
						<div class="col-md-9">
							{{ this.displayFolderDesc }}
						</div>
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
	</div>
</template>

<script>
import qs from 'qs';
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
           
			//Files
            files: [],
            
            URLEndPoint: '',

            prevIDClicked: '',
			
			//feedbacks
			invalidFeedbackMessage: "Name is required",

			//Display Only
			displayFolderLink: '',
			displayFolderName: '',
			displayFolderDesc: '',

			//Modals Inputs
			folderID: '',
			folderName: '',
			folderDescription: '',
			
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
			newTree: {},

			//The Data for the Tree
			data: new Tree(this.folders)
		};
    },
    
	beforeMount() {
		
	},
	mounted() {
        this.getFolders();

        if (this.public === true ) 
        {
            this.autoClickFolder(this.public_folder_id);
        }
       
	},
	methods: {
		getFolders() {

            if (this.public === true) {
                this.URLEndPoint = "/api/get_child_folders"
                this.getPublicFolders();
            } else {
                this.URLEndPoint = "/api/get_folders?api_token=" + this.api_token;
                this.getFolderPermission();
            }
        },
        getPublicFolders() {
			axios.post(this.URLEndPoint,
				{ 
                    method: "POST",
                    public_folder_id: this.public_folder_id
				}
			)
			.then(response => {
                this.data = new Tree(response.data.folders);
                
              

			})
			.catch(function(error) {
				console.log(error);
			});  
        },
        autoClickFolder(id) {
         
			let nodeItem = {
				id: id
			};

            this.onClick(nodeItem)
         
        },
        getFolderPermission() {
			axios.post(this.URLEndPoint,
				{ 
                    method: "POST",
				}
			)
			.then(response => {
				this.data = new Tree(response.data.folders)
			})
			.catch(function(error) {
				console.log(error);
			});  
        },
		getFolderFiles(folderID) {


            if (this.public === true) {
                this.URLEndPoint = "/api/get_public_folder_files"
            } else {
                this.URLEndPoint = "/api/get_folder_files?api_token=" + this.api_token
            }
		
			axios.post(
				this.URLEndPoint,
				{ 
					method: "POST",
					folder_id: folderID,
				}
			)
			.then(response => {
				//display the folder attributes (id, name, description)	
				//console.log(response.data.files);
				
				this.displayFolderLink = response.data.permalink;
				this.displayFolderName = response.data.folder_name;
				this.displayFolderDesc = response.data.folder_description;

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

			console.log(node.id);
			console.log('name', node.name);
			console.log('description', node.description);

			//set the display info
			//this.displayFolderName = node.name;
			//this.displayFolderDesc = node.description;

			this.folderCurrentID = node.id.toString();
			this.getFolderFiles(node.id);

            //reset the uploader and files
            if (this.can_user_upload) {
                this.$root.$refs.treeListComponent.$refs.uploaderComponent.files = [];
            }
            this.$root.$refs.treeListComponent.$refs.folderFilesComponent.files = [];


            if (this.prevIDClicked) {
                document.getElementById(this.prevIDClicked).getElementsByClassName( 'vtl-node-main' )[0].removeAttribute("style");
                document.getElementById(node.id).getElementsByClassName( 'vtl-node-main' )[0].style.backgroundColor = "#f0f0f0";
            } else {
               // document.getElementById(this.public_folder_id).getElementsByClassName( 'vtl-node-main' )[0].style.backgroundColor = "#f0f0f0";
            }
            
            this.prevIDClicked = node.id;
		},
		onCreateNewSubFolder(params) {
			this.$bvModal.show("createNewSubFolder");
			this.currentNodeCreated = params;
			console.log(params)
		},
		onDel(node) {

			axios.post(
				"/api/delete_folder?api_token=" + this.api_token,
				{ 
					method: "POST",
					id: node.id
				}
			)
			.then(response => {
				
				node.remove();

			})
			.catch(function(error) {
				// handle error
				alert("Error " + error);
				console.log(error);
			});   

		},
		onEditFolder(event) {
			let id = event.target.parentElement.parentElement.parentElement.parentElement.parentElement.id;
			this.folderID = id;
			this.folderCurrentID = id.toString();
			this.getFolderFiles(this.folderID);

			//reset the uploader and files
			this.$root.$refs.treeListComponent.$refs.uploaderComponent.files = [];
			this.$root.$refs.treeListComponent.$refs.folderFilesComponent.files = [];

			this.$bvModal.show("editFolder");
		},
		onChangeName(id) {

			let folder = {
				id: id,
				name: this.folderName,
				description: this.folderDescription
			}
			_updateFolder(this.data, folder);
			function _updateFolder(oldNode, folder) {
				var newNode = {};

				for (var k in oldNode) {
					if (k !== "children" && k !== "parent") { newNode[k] = oldNode[k]; }
				}

				if (oldNode.children && oldNode.children.length > 0) {
					newNode.children = [];
					for (var i = 0, len = oldNode.children.length; i < len;i++) {
						if (oldNode.children[i].id === id) {
							oldNode.children[i].name = folder.name;
							oldNode.children[i].description = folder.description;
						}
						newNode.children.push(_updateFolder(oldNode.children[i],folder ));
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
			
			if (this.FolderType == 'rootFolder') {
				//Create the root folder
				this.createFolderOnServer(0);
				this.$nextTick(() => {
					//this.$bvModal.hide("createNewFolder");
				});
			} else if (this.FolderType == 'subFolder') {
				//create the subfolder
				this.createFolderOnServer(this.currentNodeCreated.parent.id);
				this.$nextTick(() => {
					//this.$bvModal.hide("createNewSubFolder");
				});
			} else if (this.FolderType == 'editFolder') {
				this.updateFolderOnServer(this.folderID);
				
				this.$nextTick(() => {
					//this.$bvModal.hide("editFolder");
				});
			}
		},
		onAddNode(params) 
		{
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
					id: item.id , 
					pid: item.parent_id,
					name: item.folder_name,
					description: item.folder_description, 
					isLeaf: false,
					addLeafNodeDisabled: true,
				});
			if (!this.data.children) this.data.children = [];
			this.data.addChildren(node);

			//////this.getFolders();
		},
		createFolderOnServer(parent_id) 
		{
			axios.post(
				"/api/create_folder?api_token=" + this.api_token,
				{ 
					method: "POST",
					parent_id: parent_id,
					folder_name: this.folderName,
					folder_description:  this.folderDescription,
				}
			)
			.then(response => {
				
				if (response.data.success === false) {
					this.invalidFeedbackMessage = response.data.message;
					this.folderNameState = false;
				} else {
					//success
					//console.log("create on folder -- id " + response.data.folder.id)

					//this.folderID = response.data.folder.id;
					//console.log("new folder id ", this.folderID);
					
					if (this.FolderType == 'rootFolder') {
						this.addNode(response.data.folder);
						this.$bvModal.hide("createNewFolder");
					} else if (this.FolderType == 'subFolder') {

						//this.addNode(response.data.folder);

						this.currentNodeCreated.id = response.data.folder.id
						this.currentNodeCreated.name = this.folderName;
						this.currentNodeCreated.description = this.folderDescription;
						this.currentNodeCreated.addLeafNodeDisabled = true;

						this.$bvModal.hide("createNewSubFolder");
					}
				
				}

			})
			.catch(function(error) {
				// handle error
				alert("Error " + error);
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
				description: node.description,
			};

			let parentNode = {
				id: src.id,
				name: src.name,
				description: src.description,
			};
	
			let targetNode = {
				id: target.id,
				name: target.name,
				description: target.description,
			};

			this.moveIntoParentItem(nodeItem, parentNode, targetNode);
		},
		onInsertBefore(item) {

			//console.log("onInsertBefore");
		
			let { node, src, target } = item; 

			//console.log(node.parent);

			let nodeItem = {
				id: node.id,
				name: node.name,
				description: node.description,
			};

			let parentNode = {
				id: src.id,
				name: src.name,
				description: src.description,
			};
	
			let targetNode = {
				id: target.id,
				name: target.name,
				description: target.description,
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
			if (this.FolderType == 'subFolder') {
				this.currentNodeCreated.remove();
			}
		},
		closeModal(){
			//console.log("closed");

			if (this.FolderType == 'subFolder') {
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
			//this.folderName = "";
			//this.folderDescription = "";
			this.folderNameState = null;
			this.folderDescriptionState = null;
		},
		handleOk(bvModalEvt) {
			// Prevent modal from closing
			bvModalEvt.preventDefault();
			// Trigger submit handler
			this.handleSubmit();
		},


		updateFolderOnServer(folderID) {
			axios.post(
				"/api/update_folder?api_token=" + this.api_token,
				{ 
					method: "POST",
					folder_id: folderID,
					folder_name: this.folderName,
					folder_description:  this.folderDescription,
				}
			)
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
					this.displayFolderName = response.data.folder.folder_name;
					this.displayFolderDesc = response.data.folder.folder_description;

					this.onChangeName(this.folderID);

					this.$bvModal.hide("editFolder");
				}
			})
			.catch(function(error) {
				// handle error
				//alert("Error " + error);
				console.log(error);
			});   
		},

		showLoading() {
			this.$bvModal.show("loadingModal");
		},
		hideLoading() {
			this.$bvModal.hide("loadingModal");
		},
		moveIntoParentItem(node, src, target) {

			axios.post(
				"/api/move_into_parent?api_token=" + this.api_token,
				{ 
					method: "POST",
					node: node,
					src: src,
					target: target
				}
			)
			.then(response => {

				if (response.data.success === false) {
					alert (response.data.message);
					this.getFolders();

				} else {
					this.$bvModal.hide("loadingModal");
				}
					
			})
			.catch(function(error) {
				// handle error
				console.log(error);
			});
		

		},
		reorderItems(node, src, target) {

			//this.showLoading();

			console.log("reordering items", {node}, {src}, {target});
			axios.post(
				"/api/reorder_items?api_token=" + this.api_token,
				{ 
					method: "POST",
					node: node,
					src: src,
					target: target
				}
			)
			.then(response => {
				if (response.data.success === false) {
					alert (response.data.message);
					this.getFolders();
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

<style lang="scss" scoped>
.vtl {
	.vtl-drag-disabled {
		background-color: #d0cfcf;
		&:hover {
			background-color: #d0cfcf;
		}
	}
	.vtl-disabled {
		background-color: #d0cfcf;
	}
}

.icon {
	&:hover {
		cursor: pointer;
	}
}
</style>