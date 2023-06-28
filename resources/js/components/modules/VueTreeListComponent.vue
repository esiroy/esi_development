<template>
	<div class="row">

		<div class="col-sm-4 col-md-4 mb-4">
            <div class="card">
                <div class="card-header">Folders</div>
                <div class="card-body">
                    <div class="folder-tree-container mb-4">

                        <div class="mb-4" v-if="(can_user_create_folder === true)">
                            <button type="button" class="btn btn-outline-dark" v-b-modal.createNewFolder>
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-folder-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M9.828 4H2.19a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91H9v1H2.826a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3h3.982a2 2 0 0 1 1.992 2.181L15.546 8H14.54l.265-2.91A1 1 0 0 0 13.81 4H9.828zm-2.95-1.707L7.587 3H2.19c-.24 0-.47.042-.684.12L1.5 2.98a1 1 0 0 1 1-.98h3.672a1 1 0 0 1 .707.293z"/>
                                    <path fill-rule="evenodd" d="M13.5 10a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1H13v-1.5a.5.5 0 0 1 .5-.5z"/>
                                    <path fill-rule="evenodd" d="M13 12.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0v-2z"/>
                                </svg>

                                New Folder
                            </button>
                        </div>

                    
                        <div id="list-scroller" style="height:480px; overflow-y: scroll">
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
                                default-leaf-node-name="New Page">
                                <!-- TREE NODE / FOLDER ICON -->
                                <span class="icon" slot="treeNodeIcon">
                                    <div class="icon-folder">
                                        <svg class="bi bi-folder mr-2" width="1.2em" height="1.2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.828 4a3 3 0 0 1-2.12-.879l-.83-.828A1 1 0 0 0 6.173 2H2.5a1 1 0 0 0-1 .981L1.546 4h-1L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3v1z"/>
                                            <path fill-rule="evenodd" d="M13.81 4H2.19a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4zM2.19 3A2 2 0 0 0 .198 5.181l.637 7A2 2 0 0 0 2.826 14h10.348a2 2 0 0 0 1.991-1.819l.637-7A2 2 0 0 0 13.81 3H2.19z"/>
                                        </svg>
                                    </div>
                                </span>
                                <!-- ADD FOLDER BUTTON -->
                                <span class="icon" slot="addTreeNodeIcon" title="Add folder">
                                    <a href="#" name="addNode" class="addNode" title="Add folder">
                                        <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-folder-plus mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M9.828 4H2.19a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91H9v1H2.826a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3h3.982a2 2 0 0 1 1.992 2.181L15.546 8H14.54l.265-2.91A1 1 0 0 0 13.81 4H9.828zm-2.95-1.707L7.587 3H2.19c-.24 0-.47.042-.684.12L1.5 2.98a1 1 0 0 1 1-.98h3.672a1 1 0 0 1 .707.293z"/>
                                            <path fill-rule="evenodd" d="M13.5 10a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1H13v-1.5a.5.5 0 0 1 .5-.5z"/>
                                            <path fill-rule="evenodd" d="M13 12.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0v-2z"/>
                                        </svg>
                                    </a>
                                </span>
                                <!-- ADD LEAF / PAGE BUTTON (DISABLED)-->
                                <span class="icon" slot="addLeafNodeIcon">＋</span>
                                <span class="icon" slot="leafNodeIcon">🍃</span>
                                <!-- EDIT FOLDER -->
                                <span class="icon" slot="editNodeIcon">
                                    <a href="#" @click.stop.prevent="onEditFolder($event)" class="editNode">
                                        <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-window mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M14 2H2a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1zM2 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2z"/>
                                            <path fill-rule="evenodd" d="M15 6H1V5h14v1z"/>
                                            <path d="M3 3.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm1.5 0a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm1.5 0a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                                        </svg>
                                    </a>
                                </span>
                                <!-- DELETE -->
                                <span class="icon" slot="delNodeIcon">
                                    <a href="#" name="deleteNode" class="deleteNode">
                                        <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-trash mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                        </svg>
                                    </a>
                                </span>
                            </vue-tree-list>
                        </div>

                    </div>

                </div>
            </div>

		</div>

		<!-- CONTTENT -->
		<div class="col-sm-8 col-md-8">
            <!-- DISPLAY FOLDER DETAILS -->
            <div class="card mb-4" v-if="!this.data.children && !can_user_upload">
                <div class="card-body text-center" v-if="this.folderLoading == false"> 
                   No Shared folders found
                </div>
                <div class="card-body text-center py-4" v-else>
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
            </div>
			<div class="card mb-4" v-else-if="this.folderCurrentID !== null">
				<div class="card-header">Folder Details</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-3">Folder ID:</div>
						<div class="col-md-9">{{ this.displayfolderID }}
                            <!--
							<a
								:href="this.displayFolderLink"
								target="_blank"
								v-if="this.displayFolderLink"
							>{{ this.displayfolderID }}</a>
							<span v-else>{{ this.displayfolderID }}</span>-->
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">Folder Name:</div>
						<div class="col-md-9"> {{ this.displayFolderName }}
                            <!--
							<a
								:href="this.displayFolderLink"
								target="_blank"
								v-if="this.displayFolderLink"
							>{{ this.displayFolderName }}</a>
							<span v-else>{{ this.displayFolderName }}</span>
                        -->
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">Folder Description</div>
						<div class="col-md-9">{{ this.displayFolderDesc }}</div>
					</div>
                    <!--
					<div class="row">
						<div class="col-md-3">Folder Owner</div>
						<div class="col-md-9">{{ this.displayFolderOwner }}</div>
					</div>
                    -->
					<div class="row">
						<div class="col-md-3">Created On</div>
						<div class="col-md-9">{{ this.displayCreatedAt }}</div>
					</div>

					<div class="row">
						<div class="col-md-3">Thumbnail</div>
						<div class="col-md-9">
                            

                            <div v-if="thumb_path !== ''">
                                <img :src="getBaseURL(this.thumb_path)" width="250px">
                            </div>

                            <div class="invisible d-none">
                                <div>{{ this.thumb_file_name }}</div>
                                <div>{{ this.thumb_upload_name }}</div>
                                <div>{{ this.thumb_path }}</div>
                            </div>

                        </div>
					</div>

				</div>
			</div>

            <!--START - UPLOADER -->
			<div class="container p-0 mb-4" v-if="can_user_upload">
                <admin-uploader-component
                    ref="uploaderComponent"
                    :users="this.users"
                    :user_can_delete="'true'"
                    :folder_id="this.folderCurrentID"
                    :csrf_token="this.folderCurrentToken"
                />
			</div>

            <!--START - FILES -->
			<div class="container p-0 mb-4">
                <folder-files-component
                    ref="folderFilesComponent"
                    :folder_id="this.folderID"
                    :public="this.public"
                    :user="this.user"
                    :users="this.users"
                    :api_token="this.api_token"
                    :csrf_token="this.folderCurrentToken"
                    :folder_files="this.files"
                    :can_user_upload="this.can_user_upload"
                    :can_user_share_uploads="this.can_user_share_uploads"
                    :can_user_delete_uploads="this.can_user_delete_uploads"
                />
            </div>
		</div>


        <!-- CONTEXT MENU -->
        <ul :id="this.parentID" class="right-click-menu" tabindex="-1" v-if="viewMenu" v-bind:style="{ top: this.top, left: this.left }">
            
            <li @click="contextmenuShare" v-if="can_user_share_folder">
                <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-share mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M11.724 3.947l-7 3.5-.448-.894 7-3.5.448.894zm-.448 9l-7-3.5.448-.894 7 3.5-.448.894z"/>
                <path fill-rule="evenodd" d="M13.5 4a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zm0 10a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zm-11-6.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                </svg>
                 Share Folder
            </li>
           

            <!--
            <li @click="contextmenuViewFolder" v-if="can_user_share_uploads">
                    <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-eye" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.134 13.134 0 0 0 1.66 2.043C4.12 11.332 5.88 12.5 8 12.5c2.12 0 3.879-1.168 5.168-2.457A13.134 13.134 0 0 0 14.828 8a13.133 13.133 0 0 0-1.66-2.043C11.879 4.668 10.119 3.5 8 3.5c-2.12 0-3.879 1.168-5.168 2.457A13.133 13.133 0 0 0 1.172 8z"/>
                    <path fill-rule="evenodd" d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                    </svg>
                    View Folder
            </li>
            -->

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

            <li @click="contextMenuGetLink">
                <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-clipboard mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                <path fill-rule="evenodd" d="M9.5 1h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                </svg>
                Copy Folder Link
            </li>

            <li @click="contextMenuDelete"  v-if="can_user_delete_folder">
                <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-trash mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                </svg>
                Delete Folder
            </li>
        </ul>

        <!-- [START] MODALS -->
        <div class="modals">
            <b-modal id="loadingModal" ref="loadingModal" title="Please wait">
                <b-spinner class="m-5" label="Busy"></b-spinner>
            </b-modal>

            <div class="sharefolderModalContainer"> 
                <b-modal
                    id="shareFolder"
                    ref="modalShareFolder"
                    :title="'Share Folder - ' + this.node.name"
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

                       
                        <multiselect 
                                v-model="sharingValues" 
                                deselect-label="Can't remove this value" 
                                track-by="code" 
                                label="name" 
                                placeholder="Select one"
                                :disabled="isSharingDisabled"
                                :options="sharingOptions" :searchable="false" 
                                :allow-empty="false">
                                    <template slot="singleLabel" slot-scope="{ option }">
                                        <strong>{{ option.name }}</strong>
                                    </template>
                        </multiselect>
                        

                        <br>
                      

                        <!--
                          <span v-if="this.sharingValues.code === 'private'">Share With Specific Users</span>
                        <multiselect v-if="this.sharingValues.code === 'private'"
                            v-model="userValues" tag-placeholder="Add this as new user" 
                            placeholder="Search or add a user" label="name" 
                            track-by="code" 
                            :options="userOptions" 
                            :multiple="true" 
                            :taggable="true"
                            :disabled="isSharingDisabled"
                            @tag="addTag">
                        </multiselect>
                        -->

                    </form>
                </b-modal>
            </div>

            <div class="createNewFolderModalContainter">
                <b-modal id="createNewFolder"
                    ref="modalCreateNewFolder"
                    title="Create New Folder"
                    @show="resetModal"
                    @hidden="resetModal"
                    @ok="handleOk">

                    <form ref="form" @submit.stop.prevent="handleSubmit">
                        <b-form-group
                            :state="folderNameState"
                            label="Name"
                            label-for="name-input"
                            :invalid-feedback="invalidFeedbackMessage">
                            <b-form-input id="name-input" v-model="folderName" :state="folderNameState" required></b-form-input>
                        </b-form-group>

                        <b-form-group
                            :state="folderDescriptionState"
                            label="Description"
                            label-for="description-input"
                            invalid-feedback="Description is required">
                            <b-form-textarea
                                id="description-input"
                                v-model="folderDescription"
                                :state="folderDescriptionState"
                            ></b-form-textarea>
                        </b-form-group>


                        <table class="table table-borderless table-hover">
                            <thead v-if="uploadFiles.length">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Size</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!uploadFiles.length">
                                <td colspan="7" align="center">
                                    <div class="small"> Drop files anywhere to upload thumbnial</div>
                                    <div>or</div>
                                    <label for="uploadFiles" class="btn btn-sm btn-primary">Select Files</label>
                                                       
                  
                                </td>
                                </tr>
                                <tr v-for="(file, index) in uploadFiles" :key="file.id">
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
                                            <a :class="{'dropdown-item small': true, disabled: !file.active}" href="#" @click.prevent="file.active ? $refs.upload.update(file, {error: 'cancel'}) : false">Cancel</a>

                                            <a class="dropdown-item small" href="#" v-if="file.active" @click.prevent="$refs.upload.update(file, {active: false})">Abort</a>

                                            <!--<a class="dropdown-item small" href="#" v-else-if="file.error && file.error !== 'compressing' && $refs.upload.features.html5" -->
                                            <a class="dropdown-item small" href="#" v-else-if="file.error && file.error !== 'compressing'"  @click.prevent="$refs.upload.update(file, {active: true, error: '', progress: '0.00'})">Retry upload</a>

                                            <a :class="{'dropdown-item small': true, disabled: file.success || file.error === 'compressing'}" href="#" 
                                                v-else @click.prevent="file.success || file.error === 'compressing' ? false : $refs.upload.update(file, {active: true})">Upload</a>
                                            <div class="dropdown-divider"></div>

                                            <a class="dropdown-item small" href="#" @click.prevent="$refs.upload.remove(file)">Remove</a>
                                        </div>
                                    </div>

                                </td>
                                </tr>
                            </tbody>
                        </table>

                         
                        <div class="upload">
                            <file-upload
                                name="uploadFiles"
                                input-id="uploadFiles"
                                class="btn btn-sm btn-primary"
                                extensions="jpeg,jpg,gif,png"
                                accept="image/png,image/gif,image/jpeg"
                                v-model="uploadFiles"
                                :post-action="getPostActionUrl()"
                                :headers="{'X-CSRF-TOKEN': this.csrf_token }"
                                :multiple="false"
                                :drop="true"
                                :drop-directory="true"                                   

                                @input="updateValue"
                                @input-file="inputFile"
                                @input-filter="inputFilter"
                                
                                ref="upload">
                                <i class="fa fa-plus"></i>
                                Select files
                            </file-upload>

                            <button type="button" class="btn btn-sm btn-success" v-if="!$refs.upload || !$refs.upload.active" @click.prevent="$refs.upload.active = true">
                            <i class="fa fa-arrow-up" aria-hidden="true"></i>Start Upload
                            </button>

                            <button type="button" class="btn btn-sm btn-danger" v-else @click.prevent="$refs.upload.active = false">
                            <i class="fa fa-stop" aria-hidden="true"></i>Stop Upload
                            </button>
                        </div>

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

                        <table class="table table-borderless table-hover">
                            <thead v-if="uploadFiles.length">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Size</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!uploadFiles.length">
                                <td colspan="7" align="center">
                                    <div class="small"> Drop files anywhere to upload thumbnial</div>
                                    <div>or</div>
                                    <label for="uploadFiles" class="btn btn-sm btn-primary">Select Files</label>
                                                       
                  
                                </td>
                                </tr>
                                <tr v-for="(file, index) in uploadFiles" :key="file.id">
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
                                            <a :class="{'dropdown-item small': true, disabled: !file.active}" href="#" @click.prevent="file.active ? $refs.upload.update(file, {error: 'cancel'}) : false">Cancel</a>

                                            <a class="dropdown-item small" href="#" v-if="file.active" @click.prevent="$refs.upload.update(file, {active: false})">Abort</a>

                                            <!--<a class="dropdown-item small" href="#" v-else-if="file.error && file.error !== 'compressing' && $refs.upload.features.html5" -->
                                            <a class="dropdown-item small" href="#" v-else-if="file.error && file.error !== 'compressing'"  @click.prevent="$refs.upload.update(file, {active: true, error: '', progress: '0.00'})">Retry upload</a>

                                            <a :class="{'dropdown-item small': true, disabled: file.success || file.error === 'compressing'}" href="#" 
                                                v-else @click.prevent="file.success || file.error === 'compressing' ? false : $refs.upload.update(file, {active: true})">Upload</a>
                                            <div class="dropdown-divider"></div>

                                            <a class="dropdown-item small" href="#" @click.prevent="$refs.upload.remove(file)">Remove</a>
                                        </div>
                                    </div>

                                </td>
                                </tr>
                            </tbody>
                        </table>

                         
                        <div class="upload">
                            <file-upload
                                name="uploadFiles"
                                input-id="uploadFiles"
                                class="btn btn-sm btn-primary"
                                extensions="jpeg,jpg,gif,png"
                                accept="image/png,image/gif,image/jpeg"
                                v-model="uploadFiles"
                                :post-action="getPostActionUrl()"
                                :headers="{'X-CSRF-TOKEN': this.csrf_token }"
                                :multiple="false"
                                :drop="true"
                                :drop-directory="true"                                   

                                @input="updateValue"
                                @input-file="inputFile"
                                @input-filter="inputFilter"
                                
                                ref="upload">
                                <i class="fa fa-plus"></i>
                                Select files
                            </file-upload>

                            <button type="button" class="btn btn-sm btn-success" v-if="!$refs.upload || !$refs.upload.active" @click.prevent="$refs.upload.active = true">
                            <i class="fa fa-arrow-up" aria-hidden="true"></i>Start Upload
                            </button>

                            <button type="button" class="btn btn-sm btn-danger" v-else @click.prevent="$refs.upload.active = false">
                            <i class="fa fa-stop" aria-hidden="true"></i>Stop Upload
                            </button>
                        </div>

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

                    Current image :

                    <div v-if="thumb_path !== ''">
                        <img :src="getBaseURL(this.thumb_path)" width="250px">
                    </div>

                    <div class="invisible d-none">
                        <div>{{ this.thumb_file_name }}</div>
                        <div>{{ this.thumb_upload_name }}</div>
                        <div>{{ this.thumb_path }}</div>
                    </div>

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



                        <table class="table table-borderless table-hover">
                            <thead v-if="uploadFiles.length">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Size</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!uploadFiles.length">
                                <td colspan="7" align="center">
                                    <div class="small"> Drop files anywhere to upload thumbnial</div>
                                    <div>or</div>
                                    <label for="uploadFiles" class="btn btn-sm btn-primary">Select Files</label>
                                                       
                  
                                </td>
                                </tr>
                                <tr v-for="(file, index) in uploadFiles" :key="file.id">
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
                                            <a :class="{'dropdown-item small': true, disabled: !file.active}" href="#" @click.prevent="file.active ? $refs.upload.update(file, {error: 'cancel'}) : false">Cancel</a>

                                            <a class="dropdown-item small" href="#" v-if="file.active" @click.prevent="$refs.upload.update(file, {active: false})">Abort</a>

                                            <!--<a class="dropdown-item small" href="#" v-else-if="file.error && file.error !== 'compressing' && $refs.upload.features.html5" -->
                                            <a class="dropdown-item small" href="#" v-else-if="file.error && file.error !== 'compressing'"  @click.prevent="$refs.upload.update(file, {active: true, error: '', progress: '0.00'})">Retry upload</a>

                                            <a :class="{'dropdown-item small': true, disabled: file.success || file.error === 'compressing'}" href="#" 
                                                v-else @click.prevent="file.success || file.error === 'compressing' ? false : $refs.upload.update(file, {active: true})">Upload</a>
                                            <div class="dropdown-divider"></div>

                                            <a class="dropdown-item small" href="#" @click.prevent="$refs.upload.remove(file)">Remove</a>
                                        </div>
                                    </div>

                                </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="upload">
                            <file-upload
                                name="uploadFiles"
                                input-id="uploadFiles"
                                class="btn btn-sm btn-primary"
                                extensions="jpeg,jpg,gif,png"
                                accept="image/png,image/gif,image/jpeg"
                                v-model="uploadFiles"
                                :post-action="getPostActionUrl()"
                                :headers="{'X-CSRF-TOKEN': this.csrf_token }"
                                :multiple="false"
                                :drop="true"
                                :drop-directory="true"                                   

                                @input="updateValue"
                                @input-file="inputFile"
                                @input-filter="inputFilter"
                                
                                ref="upload">
                                <i class="fa fa-plus"></i>
                                Select files
                            </file-upload>

                            <button type="button" class="btn btn-sm btn-success" v-if="!$refs.upload || !$refs.upload.active" @click.prevent="$refs.upload.active = true">
                            <i class="fa fa-arrow-up" aria-hidden="true"></i>Start Upload
                            </button>

                            <button type="button" class="btn btn-sm btn-danger" v-else @click.prevent="$refs.upload.active = false">
                            <i class="fa fa-stop" aria-hidden="true"></i>Stop Upload
                            </button>
                        </div>

                    </form>
                </b-modal>
            </div>
        </div>

	</div>
</template>

<script>

import FileUpload from 'vue-upload-component'

import qs from "qs";
import { VueTreeList, Tree, TreeNode } from "vue-tree-list";
import Multiselect from 'vue-multiselect'
import VueClipboard from 'vue-clipboard2'
VueClipboard.config.autoSetContainer = true
Vue.use(VueClipboard)

export default {
	components: {
        VueTreeList,
        Multiselect,
        FileUpload
	},
	props: {
		public: {
			type: Boolean
        },
        user: {
            type: Object
        },
        public_viewer_id: {
            type: Number
        },
		public_folder_id: {
			type: Number
        },
        users: {
            type: Array
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
        can_user_share_uploads: {
			type: Boolean
        },
		can_user_delete_uploads: {
			type: Boolean
        },
        can_user_share_folder: {
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
        can_user_manage_folder: {
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
            uploadFiles: [],
            tempID: null,
         

            //variables
            firstLoad       : true,
            folderLoading   : null,
            parentID        : null,
			URLEndPoint     : "",
            prevIDClicked   : null,
            node            : {},
            files           : [],

            //Users Select
            userValues: [],
            userOptions: [],
            //Sharing
            sharingValues: [],
            sharingOptions: [
                { name: 'Public', code: 'public' },
                { name: 'Private', code: 'private' }
            ],
            isSharingDisabled: false,

            //context Menu
            contextMenuPermalink    : null,

			//feedbacks
            invalidFeedbackMessage  : "Name is required",
            
			//Display Only
			displayfolderID         : "",
			displayFolderLink       : "",
			displayFolderName       : "",
            displayFolderDesc       : "",
            displayFolderOwner      : "",   
            displayCreatedAt        : "",

			//Modals Inputs
			folderID                : null,
			folderName              : "",
			folderDescription       : "",
            //new updates
            thumb_file_name         : "",
            thumb_upload_name       : "",
            thumb_path              : "",

            //Modal Variable State
			folderNameState         : null,
			folderDescriptionState  : null,

			//new folder initialize
			currentNodeCreated: {
				id: null,
				name: null,
				description: null
			},

			//Folder
			folderCurrentID         : null,
			folderCurrentToken      : this.csrf_token,

			//The Data for the Tree
            data: new Tree(this.folders),
            newTree: {},

            //context menu
            viewMenu            : false,
            top                 : "0px",
            left                : "0px",

            iconSharedFolder    : "<svg class='bi bi-folder-symlink mr-2' width='1.2em' height='1.2em' viewBox='0 0 16 16'  fill='currentColor' xmlns='http://www.w3.org/2000/svg'><path d='M9.828 4a3 3 0 0 1-2.12-.879l-.83-.828A1 1 0 0 0 6.173 2H2.5a1 1 0 0 0-1 .981L1.546 4h-1L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3v1z'/><path fill-rule='evenodd' d='M13.81 4H2.19a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4zM2.19 3A2 2 0 0 0 .198 5.181l.637 7A2 2 0 0 0 2.826 14h10.348a2 2 0 0 0 1.991-1.819l.637-7A2 2 0 0 0 13.81 3H2.19z'/> <path d='M8.616 10.24l3.182-1.969a.443.443 0 0 0 0-.742l-3.182-1.97c-.27-.166-.616.036-.616.372V6.7c-.857 0-3.429 0-4 4.8 1.429-2.7 4-2.4 4-2.4v.769c0 .336.346.538.616.371z'/></svg>",

            iconPrivateFolder   : "<svg class='bi bi-folder mr-2' width='1.2em' height='1.2em' viewBox='0 0 16 16' fill='currentColor' xmlns='http://www.w3.org/2000/svg'><path d='M9.828 4a3 3 0 0 1-2.12-.879l-.83-.828A1 1 0 0 0 6.173 2H2.5a1 1 0 0 0-1 .981L1.546 4h-1L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3v1z'/><path fill-rule='evenodd' d='M13.81 4H2.19a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4zM2.19 3A2 2 0 0 0 .198 5.181l.637 7A2 2 0 0 0 2.826 14h10.348a2 2 0 0 0 1.991-1.819l.637-7A2 2 0 0 0 13.81 3H2.19z'/></svg>",  

		}
	},

	beforeMount() {},
    mounted() 
    {

        this.tempID = Math.floor(Math.random() * 999) + 1;

        //get all folders
        this.getFolders();

        //context menus
        let elements = document.getElementsByClassName("vtl");
        Array.from(elements).forEach((element) => {
            element.addEventListener('contextmenu', () => this.openMenu(event, element));
        });
        document.addEventListener('click', () => this.closeMenu(event));


	},
	methods: {
        getBaseURL(path) {
            return window.location.origin + "/" +path
        },

        getPostActionUrl() { 
           return '/api/uploader/uploadFolderThumbnail?api_token='+this.api_token+'&tempID='+ this.tempID;
        },

        //[start] uploader
        updateValue(value) {
            this.uploadFiles = value;
        },
        /**
        * Has changed
        * @param  Object|undefined   newFile   Read only
        * @param  Object|undefined   oldFile   Read only
        * @return undefined
        */
        inputFile: function(newFile, oldFile) {

            if (newFile && oldFile && !newFile.active && oldFile.active) {

                if (newFile.xhr) {

                    console.log(newFile.xhr.status)

                    if ( newFile.xhr.status === 200) {


                        console.log("XHR", newFile, oldFile);

                        if (this.FolderType == "rootFolder") {

                            this.$bvModal.hide("createNewFolder");

                        } else if (this.FolderType == "subFolder")  {

                            this.$bvModal.hide("createNewSubFolder");

                        } else {
                        
                            this.$bvModal.hide("editFolder");
                        }


                        this.thumb_file_name = newFile.response.thumb_file_name;
                        this.thumb_upload_name = newFile.response.thumb_upload_name;
                        this.thumb_path = newFile.response.path; 

                     
                        this.uploadFiles = [{
                                        'id'          : newFile.response.id,
                                        'file_name'   : newFile.response.file,
                                        'size'        : newFile.response.size,
                                        'owner'       : newFile.response.owner,
                                        'notes'       : newFile.response.notes,
                                        'audioFiles'  : newFile.response.audioFiles,
                                        'folderType'  : this.FolderType
                                    }];  
               

                        axios.post("/api/updateFolderThumbDetails?api_token=" + this.api_token, 
                        {
                            'method'                : "POST",
                            'folderID'              : this.folderID,
                            'folderType'            : this.FolderType,
                            'thumb_file_name'       : newFile.response.thumb_file_name,
                            'thumb_upload_name'     : newFile.response.thumb_upload_name,
                            'path'                  : newFile.response.path
                        }).then(response => {
                            if (response.data.success == false) {
                                alert (response.data.message)
                            }
                        });           

                        //remove the files
                        this.uploadFiles.splice(this.uploadFiles.findIndex(function(i){
                            return i.id === newFile.id;
                        }), 1);

                        this.uploadFiles = [];
                          
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

           console.log(newFile, oldFile);


            if (newFile && !oldFile) {
                // Filter non-image file
                if (!/\.(jpeg|jpg|gif|png)$/i.test(newFile.name)) {
                    alert ("Please upload only images here")
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

        //[start]Folder
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
        addTag (newTag) {
            const tag = {
                name: newTag,
                code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
            }
            this.options.push(tag)
            this.value.push(tag)
        },

        copyPermalinkHandler() {
            let container = this.$refs.container
            this.$copyText("Text to copy", container)
        },
        getPermalink(id) {
             this.searchPermalink(this.data, id)
        },
        searchPermalink(oldNode, id) {
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
                        this.searchPermalink(oldNode.children[i], id)
                    );
                }
            }
        },
        getNode(id) {
            this.getNodeData(this.data, id);
        },
        getNodeData(oldNode, id) {
            var newNode = {};
            for (var k in oldNode) {
                if (k !== "children" && k !== "parent") {
                    newNode[k] = oldNode[k];
                }
            }

            if (oldNode.children && oldNode.children.length > 0) {
                newNode.children = [];
                for (var i = 0, len = oldNode.children.length;i < len;i++) {
                    if (oldNode.children[i].id == id) 
                    {
                        this.node = {
                            id          : oldNode.children[i].id,
                            name        : oldNode.children[i].name,
                            description : oldNode.children[i].description,
                            permalink   : oldNode.children[i].permalink,
                            owner       : oldNode.children[i].owner,
                            privacy     : oldNode.children[i].privacy,
                            sharedTo    : oldNode.children[i].sharedTo
                        }             

                    }
                    newNode.children.push(
                        this.getNodeData(oldNode.children[i], id)
                    );
                }
            }
        },
        openMenu: function(event, element) 
        {
            this.getParentID(event.target);
            Vue.nextTick(function()
            {
                this.getNode(this.parentID);
                if (this.public == false) 
                {
                    let userLists = this.users;
                    let userOptionsList     = userLists.filter((u) => { if (u.id !== this.user.id ) { return u } });
                    this.userOptions        = userOptionsList;
                }

                if (!this.public) {
                    if (this.node.owner.id !== this.user.id) {
                        if (this.can_user_manage_folder == false) {
                            this.isSharingDisabled = true;
                        } else {
                            this.isSharingDisabled = false; //filemanger admin mode
                        }
                    } else {
                        this.isSharingDisabled = false;
                    }
                } else {
                    this.isSharingDisabled = false;
                }

                this.viewMenu = true;
                this.setMenu(event)
            }.bind(this))
            event.preventDefault();
		},

        contextmenuShare: function(event, element)  
        {
            this.getParentID(event.target);
            this.getNode(this.parentID);
            this.$nextTick(function() {
                 this.$nextTick(function() 
                 {
                    this.contextMenuPermalink    = this.node.permalink;
                    this.sharingValues           = {
                                                        'code': this.node.privacy, 
                                                        'name': this.node.privacy.toLowerCase().replace(/(?<= )[^\s]|^./g, a=>a.toUpperCase())
                                                    };

                    this.userValues             = [...this.node.sharedTo];

                    
                    this.$bvModal.show("shareFolder");
                 });
            });   
        },
        contextmenuViewFolder() {
            this.getPermalink(this.parentID);
            this.$nextTick(function() {
                let win = window.open(this.contextMenuPermalink, '_blank');
                win.focus();
            });

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
        contextMenuGetLink()
        {
            this.getPermalink(this.parentID);
            this.$nextTick(function(){
                this.textToClipboard(this.contextMenuPermalink);
            });
        },
        textToClipboard (text) {
            let dummy = document.createElement("textarea");
            document.body.appendChild(dummy);
            dummy.value = text;
            dummy.select();
            document.execCommand("copy");
            document.body.removeChild(dummy);
        },
        getParentID(element) {
           if (typeof(element.id) == 'undefined' || typeof(element.id) == null || element.id == 'undefined' || element.id == '' ) 
           {
               this.getParentID(element.parentElement);
           } else {
                this.parentID = element.id;
           }
        },

		getFolders() {
            this.folderLoading = true;
			if (this.public === true) {
				this.URLEndPoint = "/api/get_child_folders";
				this.getPublicFolders();
			} else {
				this.URLEndPoint = "/api/get_folders?api_token=" + this.api_token;
				this.getPrivateFolder();
			}
		},
        getPublicFolders() 
        {
			axios.post(this.URLEndPoint, {
					method: "POST",
                    public_folder_id: this.public_folder_id,
                    public_viewer_id: this.public_viewer_id,
				})
				.then(response => {
					this.data = new Tree(response.data.folders);

                    this.$nextTick(function() 
                    {
                         this.folderLoading = false;
                         this.$bvModal.hide("shareFolder");

                            let elements = document.getElementsByClassName("vtl-tree-node");
                            Array.from(elements).forEach((element) => 
                            {
                               this.getNode(element.id);
                               let content = document.getElementById(element.id);
                               let icon = content.querySelectorAll(".icon-folder");

                               if (this.node.privacy == "public") 
                               {
                                   icon[0].innerHTML = this.iconSharedFolder;
                               } 
                               else if (this.node.privacy == "private" && (this.node.sharedTo).length >= 1) 
                               {
                                    icon[0].innerHTML = this.iconSharedFolder;
                               } else {
                                    icon[0].innerHTML = this.iconPrivateFolder;                 
                               }
                            });

						if (this.public === true) {
                            try {
                                this.autoClickFolder(this.data.children[0]);
                            } catch(e) {
                                //console.log(e)
                            }
                        }
					});
				})
				.catch(function(error) {
                    alert(error)
					//console.log(error);
				});
		},
        getPrivateFolder() 
        {
			axios.post(this.URLEndPoint, {
					method: "POST"
				})
				.then(response => {
                    this.data = new Tree(response.data.folders);
                    this.$nextTick(function() 
                    {
                        this.folderLoading = false;
                        this.$bvModal.hide("shareFolder");
                        this.$nextTick(function()
                        {
                            let elements = document.getElementsByClassName("vtl-tree-node");
                            Array.from(elements).forEach((element) => 
                            {
                               this.getNode(element.id);
                               let content = document.getElementById(element.id);
                               let icon = content.querySelectorAll(".icon-folder");

                               if (this.node.privacy == "public") 
                               {
                                   icon[0].innerHTML = this.iconSharedFolder;
                               } 
                               else if (this.node.privacy == "private" && (this.node.sharedTo).length >= 1) 
                               {
                                    icon[0].innerHTML = this.iconSharedFolder;
                               } else {
                                    icon[0].innerHTML = this.iconPrivateFolder;                 
                               }
                            });

                            if (this.firstLoad === true) {
                                try {
                                    this.autoClickFolder(this.data.children[0]);
                                } catch(e) {
                                   // console.log(e)
                                }
                                
                                this.firstLoad = false;
                            }
                        })


                    });
				})
				.catch(function(error) {
                    alert(error)
					//console.log(error);
				});
        },
  
        autoClickFolder(data) 
        {
			let nodeItem = {
				id          : data.id,
				name        : data.name,
				description : data.description,
                permalink   : data.permalink,
                owner       : data.owner,
                created_at  : data.created_at,
			};
            this.onClick(nodeItem);
            
            this.$root.$refs.treeListComponent.$refs.folderFilesComponent.currentFolderViewing = true;
		},
		getFolderFiles(folderID) {

            this.$root.$refs.treeListComponent.$refs.folderFilesComponent.file_loading = true;

			if (this.public === true) {
				this.URLEndPoint = "/api/get_public_folder_files";
			} else {
				this.URLEndPoint = "/api/get_folder_files?api_token=" + this.api_token;
			}

			axios.post(this.URLEndPoint, {
					method: "POST",
					folder_id: folderID
				})
				.then(response => {

                    console.log(response.data)
                
					//set the name of modal
					this.folderID = response.data.folder_id;
					this.folderName = response.data.folder_name;
					this.folderDescription = response.data.folder_description;
                    
                    //thumbnail
                    this.thumb_file_name = response.data.thumb_file_name;
                    this.thumb_upload_name = response.data.thumb_upload_name;
                    this.thumb_path = response.data.thumb_path;

                    this.$root.$refs.treeListComponent.$refs.folderFilesComponent.files = response.data.files;
                    this.$root.$refs.treeListComponent.$refs.folderFilesComponent.file_loading = false;
				})
				.catch(function(error) {
                    //console.log(error);
                    alert(error)
                    this.$root.$refs.treeListComponent.$refs.folderFilesComponent.file_loading = false;
				});
		},
        onClick(node) 
        {

            console.log("onClick", node);

            this.getFolderFiles(node.id);
           
            //set the display info
            this.folderCurrentID        = node.id.toString();
			this.displayfolderID        = node.id;
			this.displayFolderName      = node.name;
			this.displayFolderDesc      = node.description;
            this.displayFolderLink      = node.permalink;
            this.displayFolderOwner     = node.owner.firstname + " " + node.owner.lastname;
            this.displayCreatedAt       = node.created_at;

            //thumbnail
            console.log("?=>"+ node.thumb_path)
            this.thumb_file_name = node.thumb_file_name;
            this.thumb_upload_name = node.thumb_upload_name;
            this.thumb_path = node.thumb_path;            

			//reset the uploader and files
			if (this.can_user_upload) {
				this.$root.$refs.treeListComponent.$refs.uploaderComponent.files = [];
			}
			this.$root.$refs.treeListComponent.$refs.folderFilesComponent.files = [];

            //remove the hightlighted and highlight the new folder
			if (this.prevIDClicked) {
                try {
                    document.getElementById(this.prevIDClicked).getElementsByClassName("vtl-node-main")[0].removeAttribute("style");
                     this.$root.$refs.treeListComponent.$refs.folderFilesComponent.currentFolderViewing = true;
                }
                catch(err) {
                    //console.log(err.message);
                    this.$root.$refs.treeListComponent.$refs.folderFilesComponent.currentFolderViewing = null;
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
                    //console.log(err.message);
                    this.$root.$refs.treeListComponent.$refs.folderFilesComponent.currentFolderViewing = null;
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
                       
                        this.$nextTick(function() {
                            node.remove();
                            this.$root.$refs.treeListComponent.$refs.folderFilesComponent.currentFolderViewing = null;

                            if (node.id == this.folderCurrentID) 
                            {
                                //user has deleted the current selected folder, therefore none is selected
                                //reset the uploader and files
                                this.folderCurrentID = null;

                                if (this.can_user_upload) {
                                    this.$root.$refs.treeListComponent.$refs.uploaderComponent.files = [];
                                }
                                this.$root.$refs.treeListComponent.$refs.folderFilesComponent.files = [];
                                this.$forceUpdate();

                            }
                        });
                    })
                    .catch(function(error) {
                        // handle error
                        alert("Error " + error);
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
        onAddNode(params) 
        {
			this.getFolders();
		},
		addNode(item) {
			var node = new TreeNode({
				id: item.id,
				pid: item.parent_id,
				name: item.folder_name,
				description: item.folder_description,
				isLeaf: false,
                addLeafNodeDisabled: true,
                owner: {
                    id: this.user.id,
                    name: this.user.first_name + " " + this.user.last_name
                }
			});
			if (!this.data.children) this.data.children = [];
			this.data.addChildren(node);
        },
        shareFolderOnServer(folderID)
        {
            axios.post("/api/share_folder?api_token=" + this.api_token, 
            {
                method: "POST",
                folderID        : folderID,
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
                        this.getFolders();
                        let nodeItem = {
                            id              : response.data.folder.id,
                            name            : this.folderName,
                            description     : this.folderDescription,
                            
                            //thumbnail
                            thumb_file_name : response.data.thumb_file_name,
                            thumb_upload_name : response.data.thumb_upload_name,
                            thumb_path : response.data.thumb_path,

                            permalink       : response.data.folder.permalink,
                            owner           : response.data.folder.owner,
                            created_at      : response.data.folder.created_at,
                        };

                        console.log("nodeItem", nodeItem);

                        this.onClick(nodeItem);
                    });
                }
			}).catch(function(error) {
                // handle error
                alert("Error " + error);
			});
        },
        createFolderOnServer(parent_id) 
        {
           

            axios.post("/api/create_folder?api_token=" + this.api_token, 
            {
                method: "POST",
                parent_id: parent_id,
                folder_name: this.folderName,
                folder_description: this.folderDescription,
                thumb_file_name: this.tempID,
            })
            .then(response => 
            {
                

                if (response.data.success === false) {

                    this.invalidFeedbackMessage = response.data.message;
                    this.folderNameState = false;

                } else {


                    this.folderID   = response.data.folder.id;
                  

                    this.$forceUpdate();

                    if (this.FolderType == "rootFolder") 
                    {
                        this.addNode(response.data.folder);

                        if (this.uploadFiles.length >= 1 ) {
                            this.$refs.upload.active = true;
                        } else {
                            this.$bvModal.hide("createNewFolder");
                        }

                    } else if (this.FolderType == "subFolder") {

                        this.currentNodeCreated.id = response.data.folder.id;
                        this.currentNodeCreated.name = this.folderName;
                        this.currentNodeCreated.description = this.folderDescription;
                        this.currentNodeCreated.addLeafNodeDisabled = true;

                        if (this.uploadFiles.length >= 1 ) {
                            this.$refs.upload.active = true;                        
                        } else {
                            this.$bvModal.hide("createNewSubFolder");
                        }                          
                    }

                    this.$nextTick(function() {

                        let nodeItem = {
                            id: response.data.folder.id,
                            name: this.folderName,
                            description: this.folderDescription,
                            //thumbnail
                            thumb_file_name : response.data.thumb_file_name,
                            thumb_upload_name : response.data.thumb_upload_name,
                            thumb_path : response.data.thumb_path,

                            permalink: response.data.folder.permalink,
                            owner: response.data.folder.owner,
                            created_at: response.data.folder.created,
                        };

                        this.onClick(nodeItem);
                        this.getFolders();
                    });

                }
			}).catch(function(error) {
                // handle error
                alert("Error " + error);
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
						this.displayfolderID    = response.data.folder.id;
						this.displayFolderName  = response.data.folder.folder_name;
						this.displayFolderDesc  = response.data.folder.folder_description;
                        this.displayFolderLink  = response.data.folder.permalink;
                        this.displayFolderOwner = response.data.folder.owner.first_name + " " + response.data.folder.owner.last_name;

                        this.thumb_file_name = response.data.thumb_file_name,
                        this.thumb_upload_name = response.data.thumb_upload_name,
                        this.thumb_path = response.data.thumb_path,

						this.onChangeName(this.folderID);
                      

                        if (this.uploadFiles.length >= 1 ) {
                            this.$refs.upload.active = true;
                        } else {
                            this.$bvModal.hide("editFolder");
                        }

                        
                        this.$nextTick(function() {
                            
                            //upload thumbs
                            if (this.uploadFiles.length >= 1 ) 
                            {
                                this.$refs.upload.active = true;
                            }  

                            let nodeItem = {
                                id: response.data.folder.id,
                                name: response.data.folder.folder_name,
                                description: response.data.folder.folder_description,

                                thumb_file_name: response.data.thumb_file_name,
                                thumb_upload_name: response.data.thumb_upload_name,
                                thumb_path: response.data.thumb_path,

                                permalink: response.data.folder.permalink,
                                owner:  {
                                    id: response.data.folder.owner.id,
                                    name: response.data.folder.owner.first_name + " " + response.data.folder.owner.last_name
                                },
                                created_at: response.data.folder.created_at,
                            }
                            this.onClick(nodeItem);

                            this.getFolders();
                        });

                        
					}
				})
				.catch(function(error) {
					// handle error
					alert("Error " + error);
					//console.log(error);
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

            let { node, src, target } = item;
            
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
			var { node, src, target } = node;
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

			if (this.FolderType == "subFolder") {
				this.currentNodeCreated.remove();
			}
		},
		closeModal() {

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
        resetShareModal() {
            this.FolderType = "shareFolder";
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
			bvModalEvt.preventDefault();
            this.handleSubmit();
        },
		handleSubmit() {

            
			// Exit when the form isn't valid
			if (!this.checkFormValidity()) {
				return;
			}


            if (this.FolderType == "shareFolder") {

                console.log("shareFolder");

                //Share
                this.shareFolderOnServer(this.parentID);

            }else if (this.FolderType == "rootFolder") {
                
                console.log("rootFolder")

				//Create the root folder
				this.createFolderOnServer(0);
	
			} else if (this.FolderType == "subFolder") {
             
                console.log("subFolder")

				//create the subfolder
				this.createFolderOnServer(this.currentNodeCreated.parent.id);
		
			} else if (this.FolderType == "editFolder") {

                console.log("editFolder")

				this.updateFolderOnServer(this.folderID);

			}
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
                        this.$nextTick(function() {
                            this.getFolders();
                        });
					} else {
						this.displayFolderLink = response.data.folder.permalink;
                        this.$bvModal.hide("loadingModal");
                        this.$nextTick(function() {
                            this.getFolders();
                        });
					}
				})
				.catch(error => {
                    this.$nextTick(function() {
                        this.getFolders();
                    });
				});
		},
		reorderItems(node, src, target) {
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
                        this.$nextTick(function() {
                            this.getFolders();
                        });
					} else {
                        this.$nextTick(function() {
                            this.getFolders();
                        });
                    }
				})
				.catch(error => {
                    this.$nextTick(function() {
                        this.getFolders();
                    });
				});
		}
	}
};
</script>

<style lang="scss">
.vtl {
    .vtl-node {
        cursor: pointer;
    }
    .vtl-tree-margin {
        margin-left: 0.8em;
        cursor: pointer;
    }
    .vtl-node-main {
        padding: 3px 0px 3px 1rem;
        .vtl-caret {
            margin-left: -1rem;
            position: relative;
            top: 3px;
            cursor: pointer;
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

    .vtl-operation {
        display:none;
    }
}

.icon {
	&:hover {
		cursor: pointer;
	}
}
/* Context Menu*/
.right-click-menu {
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

.right-click-menu li {
	border-bottom: 1px solid #e0e0e0;
	margin: 0;
	padding: 5px 35px;
}

.right-click-menu li:last-child {
	border-bottom: none;
}

.right-click-menu li:hover {
	background: #1e88e5;
	color: #fafafa;
}

.uploader label.btn {
  margin-bottom: 0;
  margin-right: 1rem;
}
</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

