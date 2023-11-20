<template>


	<div id="member-communication-editor">

		<div id="member_current_comm">

			<div class="col-md-12">			
				<span class="main_comm small">利用通信ソフト: <a href="javascript:void(0)" @click="showEditPrimaryComm()"><i class="fas fa-edit"></i></a></span>
				<span class="text-secondary pt-1" v-if="current_comm_selected == 'My-Room'">
					<a class="small" href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/0212121907.html','スカイプ（ZOOM) ID変更方法',900,820);">
						{{ ucfirst(current_comm_selected) }} 
					</a>
				</span>
				<span class="text-secondary pt-1" v-if="current_comm_selected == 'Backup'">
				
					<a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/0212121907.html','スカイプ（ZOOM) ID変更方法',900,820);" 
						v-if="backupSelected == 'Skype' || backupSelected == 'skype'">
						Skype
					</a>
					<div class="main_comm" v-if="backupSelected == 'Skype' || backupSelected == 'skype'">Username : {{ ucfirst(skype_account_handle) }} </div>	
						
					<a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/0212121907.html','スカイプ（ZOOM) ID変更方法',900,820);" 
						v-if="backupSelected == 'Zoom' || backupSelected == 'zoom'">
						Zoom 						
					</a>
					<div class="main_comm" v-if="backupSelected == 'Zoom' || backupSelected == 'zoom'">Username : {{ ucfirst(zoom_account_handle) }}</div> 
				</span>
			</div>

			<div class="col-md-12">			
				<div class="pt-1 small" v-if="current_comm_selected == 'My-Room'">	
					Back up 通信ソフト: <a href="javascript:void(0)" @click="showEditBackupComm()"><i class="fas fa-edit"></i></a> 
					{{ ucfirst(current_backup_selected) }}
					<div class="text-secondary" v-if="current_backup_selected == 'Skype' || current_backup_selected == 'skype'">
						Username : <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/0212121907.html','スカイプ（ZOOM) ID変更方法',900,820);">
						{{ ucfirst(current_skype_account_handle) }} </a>		
					</div>
					<div class="text-secondary" v-if="current_backup_selected == 'Zoom' || current_backup_selected == 'zoom'">
						Username : <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/0212121907.html','スカイプ（ZOOM) ID変更方法',900,820);">
						{{ ucfirst(current_zoom_account_handle) }} 
						</a>
					</div>														
				</div>		
			</div>	
			
			
		</div>


		<b-modal ref="editPrimaryComm" title="Primary Communication Setting (通信ソフト設定)" size="lg"  @show="isMemberValidToUpdate">

			<div class="alert alert-success text-center" role="alert" v-if="success_message">
				{{ success_message }}
			</div>

			<div class="alert alert-danger text-center" role="alert" v-if="error_message">
				{{ error_message }}
			</div>	

		

			<div class="text-center" v-if="isLoading == true">
				<div>
					<span class="spinner-border spinner-border-sm"></span>
					<div>Loading...</div>
				</div>
			</div>

			
			<div class="container mt-3" v-if="isLoading == false">
				<b-form-group>
					<div class="row">
						<div class="col-4 text-center">
							<img style="width:170px; height:155px;text-align:center" title="My-Room" src="/images/myroom_logo.png">							
						</div>
						<div class="col-4 text-center">
							<img style="width:120px;text-align:center" title="My-Room" src="/images/skype.jpg">													
						</div>
						<div class="col-4 text-center">
							<img style="width:120px;text-align:center" title="My-Room" src="/images/zoom_logo.jpg">							
						</div>					
					</div>		
					<div class="row">
						<div class="col-4 text-center">							
							<b-form-radio v-model="main_com_app"  name="communication_app" :disabled="!isUpdatable" value="My-Room"  @change="setComApp('My-Room', backupSelected)">My-Room</b-form-radio>
						</div>
						<div class="col-4 text-center">							
							<b-form-radio v-model="main_com_app" name="communication_app" :disabled="!isUpdatable" value="Skype" @change="setComApp('Backup', 'Skype')">Skype</b-form-radio>
						</div>
						<div class="col-4 text-center">							
							<b-form-radio v-model="main_com_app" name="communication_app" :disabled="!isUpdatable" value="Zoom" @change="setComApp('Backup', 'Zoom')">Zoom</b-form-radio>
						</div>	
					</div>		
				</b-form-group>		

				<div class="container" v-if="selected == 'backup' || selected == 'Backup'">
					<div class="row">
						<div class="col-3">
							&nbsp;
						</div>
										
						<div class="col-6" v-if="backupSelected == 'Skype' || backupSelected == 'skype'">
							Username: <input type="text" class="form-control" v-model="skype_account_handle">
						</div>
						<div class="col-6" v-if="backupSelected == 'Zoom' || backupSelected == 'zoom'">
							Username: <input type="text" class="form-control" v-model="zoom_account_handle">
						</div>

						<div class="col-3">
							&nbsp;
						</div>						
					</div>
				</div>

			</div>
		
            <template #modal-footer>
				<div class="footer">

					<!--
					<b-button variant="primary" size="sm" class="float-right mr" v-if="isLoading == true">
						<span class="spinner-border spinner-border-sm"></span>
						Loading...
					</b-button>
					-->

					<div class="buttons-container w-100" v-if="isLoading == false">
						<b-button variant="primary" size="sm" class="float-right mr" :disabled="!isUpdatable"  @click="updateMainComm">Save</b-button>
						<b-button variant="danger" size="sm" class="float-right mr-2" @click="hideEditPrimaryComm">Cancel</b-button>                            
					</div>
				</div>

            </template> 

		</b-modal>


		<b-modal ref="editBackupComm" title="Member Backup Communication Settings" size="lg" @show="isMemberValidToUpdate">

			<div class="alert alert-success text-center" role="alert" v-if="success_message">
				{{ success_message }}
			</div>

			<div class="alert alert-danger text-center" role="alert" v-if="error_message">
				{{ error_message }}
			</div>	

			<div class="text-center" v-if="isLoading == true">
				<div>
					<span class="spinner-border spinner-border-sm"></span>
					<div>Loading...</div>
				</div>
			</div>

			<div class="container mt-3 text-center" v-if="isLoading == false">
				<b-form-group>
					<div class="row">					
						<div class="col-3 text-center">&nbsp;</div>
						<div class="col-3 text-center">
							<img style="width:120px;text-align:center" title="My-Room" src="/images/skype.jpg">													
						</div>
						<div class="col-3 text-center">
							<img style="width:120px;text-align:center" title="My-Room" src="/images/zoom_logo.jpg">							
						</div>					
						<div class="col-3 text-center">&nbsp;</div>
					</div>		
					<div class="row">					
						<div class="col-3 text-center">&nbsp;</div>
						<div class="col-3 text-center">							
							<b-form-radio v-model="backupSelected" name="communication_app" :disabled="!isUpdatable" value="Skype" @change="setComApp('My-Room', 'Skype')">Skype</b-form-radio>
						</div>
						<div class="col-3 text-center">							
							<b-form-radio v-model="backupSelected" name="communication_app" :disabled="!isUpdatable" value="Zoom" @change="setComApp('My-Room', 'Zoom')">Zoom</b-form-radio>
						</div>	
						<div class="col-3 text-center">&nbsp;</div>
					</div>		
				</b-form-group>	
			</div>

			<div class="container px-4" v-if="isLoading == false">
				<div class="row">
					<div class="col-3">
						&nbsp;
					</div>
					<div class="col-6">
						<div v-if="backupSelected == 'Skype' || backupSelected == 'skype'">
							Username: <input type="text" class="form-control" v-model="skype_account_handle">
						</div>

						<div v-if="backupSelected == 'Zoom' || backupSelected == 'zoom'">
							Username:  <input type="text" class="form-control" v-model="zoom_account_handle">
						</div>
					</div>
					<div class="col-3">
						&nbsp;
					</div>
				</div>
			</div>

            <template #modal-footer>
				
				<div class="footer">

					<!--
					<b-button variant="primary" size="sm" class="float-right mr" v-if="isLoading == true">
						<span class="spinner-border spinner-border-sm"></span>
						Loading...
					</b-button>
					-->

					<div class="buttons-container"  v-if="isLoading == false">
						<b-button variant="primary" size="sm" class="float-right mr" :disabled="!isUpdatable" @click="updateBackupComm">Save</b-button>
						<b-button variant="danger" size="sm" class="float-right mr-2" @click="hideEditBackupComm">Cancel</b-button>                            
					</div>

				</div>
            </template> 

		</b-modal>

	</div>

</template>

<style lang="scss">
 
</style>

<script>
export default {

	props: {
		api_token: String,
		user_info: Object,
		user_image: String,   
		member_info: Object
	},
	data() {
		return {

			isUpdatable: false,

			main_com_app: "",

			isLoading: false,
			success_message: '',
			error_message: '',
			timerId: null, // Holds the ID of the active timeout

			//Current Member Info
			current_comm_selected: null,
			current_backup_selected: null,
			current_skype_account_handle: '',
			current_zoom_account_handle: '',


			//Models
			selected: null,
			backupSelected: null,

			options: [
				{ text: 'My-Room', value: 'My-Room' },
				{ text: 'Backup', value: 'Backup' },
			],

			
			backupOptions:		[
				{ text: 'Skype', value: 'Skype' },
				{ text: 'Zoom', value: 'Zoom' },
			],

			skype_account_handle: '',
			zoom_account_handle: ''

		};
	},
	mounted() {

		window.memberComEditorComponent = this;

		this.isUpdatable = false;

		this.current_backup_selected 	 = this.member_info.communication_app
		this.current_skype_account_handle = this.member_info.skype_account;
		this.current_zoom_account_handle  = this.member_info.zoom_account;
		
		this.backupSelected		  = this.member_info.communication_app;			
		this.skype_account_handle = this.member_info.skype_account;
		this.zoom_account_handle  = this.member_info.zoom_account;


		if (this.member_info.is_myroom_enabled == true) {
			this.current_comm_selected = "My-Room"
			this.main_com_app = "My-Room";
			this.selected = 'My-Room';		
		} else {
			this.current_comm_selected = "Backup"
			this.selected = 'Backup';

			if (this.backupSelected == "skype" || this.backupSelected == "Skype") {
				this.main_com_app = "Skype";
			} else if (this.backupSelected == "zoom" || this.backupSelected == "Zoom") {
				this.main_com_app = "Zoom";
			}		
		}
	},
	methods: {
		setComApp(primary, application) {
			this.main_com_app 	= primary;
			this.selected 		=  primary;
			this.backupSelected	= application;		
		},
		clear() {
			clearTimeout(this.timerId); // Cancel the timeout if the modal is closed manually
			this.timerId = null;
			this.success_message = "";
			this.error_message = "";
		},
		showEditPrimaryComm() {
			this.clear();
			this.$refs.editPrimaryComm.show();
		},
		hideEditPrimaryComm() {
			this.clear();		
			this.$refs.editPrimaryComm.hide();
		},

		showEditBackupComm() {
			this.clear();		
			this.$refs.editBackupComm.show();
		},
		hideEditBackupComm() {
			this.clear();
			this.$refs.editBackupComm.hide();
		},
		showMemberLessonSelect() {
			const lessonElements = document.querySelectorAll('.lesson-selector');

			// Loop through all elements and hide them one by one
			lessonElements.forEach(element => {
				element.style.display = 'block';
			});
		},
		hideMemberLessonSelect() {
			const lessonElements = document.querySelectorAll('.lesson-selector');

			// Loop through all elements and hide them one by one
			lessonElements.forEach(element => {
				element.style.display = 'none';
			});
		},
		ucfirst(str) {
			if (str) {
				const string = str.charAt(0).toUpperCase() + str.slice(1);
				return str;	
			}
		},
		isMemberValidToUpdate() {

			this.isLoading = true;

			axios.post("/api/isMemberValidToUpdate?api_token=" + this.api_token,
			{
				'method'                : "POST",
				'memberID'				: this.member_info.user_id
			}).then(response => {

				this.isLoading = false;

				if (response.data.success == true) {

					this.isUpdatable = true;
					this.error_message = "";

				} else {
				
					this.isUpdatable = false;
					this.error_message = response.data.message;
				
				}
			});

		
		},
		updateMainComm() {	

			this.isLoading = true;	

            axios.post("/api/updateMainComm?api_token=" + this.api_token,
            {
                'method'			: "POST",
				'user_id'			: this.member_info.user_id,
				'main_comm_account'	: this.selected,
				'backupSelected'	: this.backupSelected,
				'skype_account_handle': this.skype_account_handle,
				'zoom_account_handle':	this.zoom_account_handle
            }).then(response => {

                if (response.data.success == true) {

					if (this.selected == 'backup') {
						this.hideMemberLessonSelect();
					} else {
						this.showMemberLessonSelect();
					}
				
					

					this.current_comm_selected = this.ucfirst(this.selected),

					//backup
					this.current_backup_selected = this.backupSelected,
					this.current_skype_account_handle = this.skype_account_handle,
					this.current_zoom_account_handle = this.zoom_account_handle,

					this.isLoading = false;
					this.success_message  = response.data.message;

					this.timerId = setTimeout(() => {
						this.hideEditPrimaryComm();
						this.success_message = '';
					}, 3000);

						
                } else {
                   this.isLoading = false;
				   this.error_message  = response.data.message ;
                }
            });
		},
		updateBackupComm() {	

			this.isLoading = true;

            axios.post("/api/updateBackupComm?api_token=" + this.api_token,
            {
                'method'                : "POST",
				'user_id'				: this.member_info.user_id,
				'backupSelected'		: this.backupSelected,
				'skype_account_handle'	: this.skype_account_handle,
				'zoom_account_handle'	: this.zoom_account_handle

            }).then(response => {
                if (response.data.success == true) {
                 
					this.isLoading = false;
					this.success_message  = response.data.message;

						//backup
					this.current_backup_selected = this.backupSelected,
					this.current_skype_account_handle = this.skype_account_handle,
					this.current_zoom_account_handle = this.zoom_account_handle,

					this.timerId = setTimeout(() => {
						this.hideEditBackupComm();
						this.success_message = '';
					}, 3000);

                } else {

                   	this.isLoading = false;
					this.error_message  = response.data.message;
                }
            });

		},		

	}
};
</script>