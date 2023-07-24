<template>


	<div id="member-communication-editor">

		<div id="member_current_comm">
			<div class="col-md-12">
				<div class="text-secondary pt-1">
					<span> Main Communication:</span> <a href="javascript:void(0)" @click="showEditPrimaryComm()"><i class="fas fa-edit"></i></a>
					<a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/0212121907.html','スカイプ（ZOOM) ID変更方法',900,820);">
						{{ ucfirst(current_comm_selected) }} 
					</a>
				</div>
			</div>
			<div class="col-md-12">
				<div class="text-secondary pt-1">			
					Backup Communication: 
					<a href="javascript:void(0)" @click="showEditBackupComm()"><i class="fas fa-edit"></i></a>
					<div v-if="current_backup_selected == 'Skype' || current_backup_selected == 'skype'">
						<a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/0212121907.html','スカイプ（ZOOM) ID変更方法',900,820);">Skype:</a>
						{{ current_skype_account_handle }}
					</div>
					<div v-if="current_backup_selected == 'Zoom' || current_backup_selected == 'zoom'">
						<a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/0212121907.html','スカイプ（ZOOM) ID変更方法',900,820);">Zoom:</a>
						{{ current_zoom_account_handle }}						
					</div>									
				</div>
			</div>
		</div>

		<b-modal ref="editPrimaryComm" title="Primary Member Communication Settings" size="md">

			<div class="alert alert-success text-center" role="alert" v-if="success_message">
				{{ success_message }}
			</div>

			<div class="alert alert-success text-center" role="alert" v-if="error_message">
				{{ error_message }}
			</div>			

			<b-form-group label="Note: If you want to be directly contacted by our tutor with skype or zoom, please choose backup">

				<b-form-radio-group
				id="btn-radios-2"
				class="mt-2"
				v-model="selected"
				:options="options"
				
				button-variant="outline-primary"
				size="sm"
				name="radio-btn-outline"
				buttons
				></b-form-radio-group>
			</b-form-group>

			<div v-if="selected == 'backup' || selected == 'Backup'">
				<b-form-group>
					<b-form-radio-group
						id="radio-group-1"
						v-model="backupSelected"
						:options="backupOptions"
						name="radio-options"
					></b-form-radio-group>
				</b-form-group>

				<div v-if="backupSelected == 'Skype' || backupSelected == 'skype'">
					<input type="text" class="form-control" v-model="skype_account_handle">
				</div>

				<div v-if="backupSelected == 'Zoom' || backupSelected == 'zoom'">
					<input type="text" class="form-control" v-model="zoom_account_handle">
				</div>
			</div>

            <template #modal-footer>

				<div class="footer">

					<b-button variant="primary" size="sm" class="float-right mr" v-if="isLoading == true">
						<span class="spinner-border spinner-border-sm"></span>
						Loading...
					</b-button>

					<div class="buttons-container w-100" v-if="isLoading == false">
						<b-button variant="primary" size="sm" class="float-right mr"  @click="updateMainComm">Save</b-button>
						<b-button variant="danger" size="sm" class="float-right mr-2" @click="hideEditPrimaryComm">Cancel</b-button>                            
					</div>
				</div>

            </template> 

		</b-modal>


		<b-modal ref="editBackupComm" title="Member Backup Communication Settings">

			<div class="alert alert-success text-center" role="alert" v-if="success_message">
				{{ success_message }}
			</div>

			<div class="alert alert-success text-center" role="alert" v-if="error_message">
				{{ error_message }}
			</div>	

			<b-form-group>
				<b-form-radio-group
					id="radio-group-1"
					v-model="backupSelected"
					:options="backupOptions"
					name="radio-options"
				></b-form-radio-group>
			</b-form-group>

			<div v-if="backupSelected == 'Skype' || backupSelected == 'skype'">
				<input type="text" class="form-control" v-model="skype_account_handle">
			</div>

			<div v-if="backupSelected == 'Zoom' || backupSelected == 'zoom'">
				<input type="text" class="form-control" v-model="zoom_account_handle">
			</div>

            <template #modal-footer>
				<div class="footer">

					<b-button variant="primary" size="sm" class="float-right mr" v-if="isLoading == true">
						<span class="spinner-border spinner-border-sm"></span>
						Loading...
					</b-button>

					<div class="buttons-container"  v-if="isLoading == false">
						<b-button variant="primary" size="sm" class="float-right mr" @click="updateBackupComm">Save</b-button>
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
				{ text: 'MyTutor Room', value: 'mytutor' },
				{ text: 'Backup', value: 'backup' },
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

		console.log(this.member_info)

		this.current_backup_selected 	 = this.member_info.communication_app
		this.current_skype_account_handle = this.member_info.skype_account;
		this.current_zoom_account_handle  = this.member_info.zoom_account;
		

		if (this.member_info.is_myroom_enabled == true) {
			this.current_comm_selected = "mytutor"
			this.selected = 'mytutor';		
		} else {
			this.current_comm_selected = "backup"
			this.selected = 'backup';
		}

		this.backupSelected		  	= this.member_info.communication_app;			
		this.skype_account_handle = this.member_info.skype_account;
		this.zoom_account_handle  = this.member_info.zoom_account;

		
	},
	methods: {
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
				
					

					this.current_comm_selected = this.selected,

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

					setTimeout(() => {
						this.hideEditPrimaryComm();
						this.error_message = '';
					}, 3000);				   
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

					setTimeout(() => {
						this.hideEditBackupComm();
						this.error_message = '';
					}, 3000);				   
                }
            });

		},		

	}
};
</script>