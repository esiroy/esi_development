<template>
	<div id="MemberNoteComponent" class="MemberNoteComponent">
		<div class="NoteHolder">
			<div id="notes" class="notes my-2">	
				<div class="card">
					<div class="card-header bg-darkblue text-white font-weight-bold">
						<div class="small">				
							<strong>Notes</strong>
							<span class="pl-2 ">
								<i class="fas fa-plus" v-b-modal.noteEntryModal></i>				
							</span>				
						</div>
					</div>

					<div class="card-body p-0 m-0 b-0">

						<div class="py-3 text-center small" v-if="notes.length == 0">
							Hey Teachers!!! You can drop a note by clicking  
							<span class="px-1"><i class="fas fa-plus" v-b-modal.noteEntryModal></i></span>							
							button 
						</div>

						<table v-else class="table esi-table table-bordered table-striped">
							<thead>
								<tr>
									<!--<td style="width:50px">Note ID</td>-->
									<!--<td>Tutor Info</td>-->
									<td>Note</td> 
									<td>Actions </td>
								</tr>
							</thead>
							<tbody>
								<tr :id="'note_' + note.note_id" v-for="note in notes" :key="note.id">
									<!--<td> {{ note.note_id }}</td>-->									
									<!--<td class="w-25 p-1" v-html="createImage(note.tutor_photo) + createTutorNameWrapper(note.tutor_name) "> </td>-->

									<td class="text-left p-3">
										<div v-html="note.note"></div>										
										<div class="text-secondary pt-1"> Author : {{ note.tutor_name }}</div>
										<div class="text-secondary pt-1"> Last Updated : {{ dateFormatter(note.updated_at) }}</div>
									</td>

									<td class="w-25 p-1">

										<div v-if="tutorinfo.id == note.tutor_id || tutorinfo.user_type == 'ADMINISTRATOR' ">
											<a href="#" @click.prevent="showEditMemberNoteModal(note); "> EDIT </a> | 
											<a href="#" @click.prevent="showMsgBox(note); "> DELETE </a>
										</div>
										<div v-else>
											<strike>EDIT</strike> | 
											<strike>DELETE</strike>
										</div>

									</td>
								</tr>
							</tbody>
						</table>	
								
					</div>
				</div>
					
			</div>

			<b-pagination v-model="currentPage" @input="changePage(currentPage)" :total-rows="rows" :per-page="perPage" aria-controls="tutor-notes"></b-pagination>


			<!--Note Modal -->
			<b-modal id="noteEntryModal" title="Add Member Notes" size="xl" @show="cleanUpEntryForm">
				<div class="row">
					<div class="col-12">
						<textarea id="message" v-model="note" class="form-control form-control-sm" required></textarea>
					</div>
				</div>

				<template #modal-footer>
					<div id="addNoteFooter" class="w-100">
						<b-button variant="primary" size="sm" class="float-right" @click="saveNote">Save Note</b-button>
					</div>

					<div id="addNoteSpinner" style="display:none">
						<b-button variant="primary" size="sm" class="float-right mr">
							<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
							Loading...
						</b-button>					
					</div>

				</template>
			</b-modal>
			<!--[END NOTE MODAL] -->

			<!--Note Modal -->
			<b-modal id="noteEditModal" title="Edit Member Notes" size="xl">
				<div class="row">
					<div class="col-12 text-center">
						<textarea id="editNote" v-model="noteEdited" class="form-control form-control-sm edit" required></textarea>						
					</div>
				</div>

				<template #modal-footer>
					<div id="editNoteFooter" class="edit">
						<b-button variant="primary" size="sm" class="float-right" @click="updateNote">Update Note</b-button>
					</div>

					<div id="editNoteSpinner" style="display:none">
						<b-button variant="primary" size="sm" class="float-right mr">
							<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
							Loading...
						</b-button>					
					</div>
				</template>

			</b-modal>
			<!--[END NOTE MODAL] -->

		</div>
	</div>
</template>

<script>

import Moment from "moment-timezone";

export default {
  name: "member-notes-component",
  components: {},
  props: {
    tutorinfo: Object,
    memberinfo: Object,
    api_token: String,
    csrf_token: String,
  },
  data() {
    return {
		note:  "",
		noteEditedData: "",
		noteEdited: "",

		highlightID: null,


		rows: 1,
		perPage: 1,
		currentPage: 1,
		notes: [],
		fields: [
		{
			key: "tutorPhoto",
			label: "Tutor",
			formatter: (value) => {
			return this.createImage(value);
			},
		},
		{
			key: "notes",
			label: "Notes",
		},
      ],
    };
  },
  mounted: function () 
  {
    this.getNotes(this.currentPage);

	
  },
  methods: {
	
	showMsgBox(note) {
		this.box = ''
		this.$bvModal.msgBoxConfirm('Please confirm that you want to delete this note.', {
			title: 'Please Confirm',
			size: 'med',
			buttonSize: 'sm',
			okVariant: 'danger',
			okTitle: 'YES',
			cancelTitle: 'NO',
			footerClass: 'p-2',
			hideHeaderClose: false,
			centered: true
		})
		.then(value => {
			this.box = value

			if (value == true ) {
				this.deleteNote(note.note_id)
			}
		})
		.catch(err => {
			// An error occurred
		})
	},	
	addHighlight(id) {
		$('#note_'+ id).addClass('table-success');
		this.$forceUpdate();
		setTimeout(() => { this.removeHighlight(id)}, 5000);
	},
	removeHighlight(id) 
	{
		$('#note_'+ id).removeClass('table-success');  
	},
  	createTutorNameWrapper(name) {
	  	return "<div class='p3'>"+ name + "</div>";
	},
  	changePage(page) 
	{
	  	this.getNotes(page);
		if (this.currentPage > 1) {
			console.log(" > 1")
			this.removeHighlight(this.highlightID);
			this.highlightID = null;
		}
	},
    createImage(value) {
      let imageURL = this.formatStorageURL(value);
      return "<img src='" + imageURL + "' fluid alt='' style='width:75px'></img>";
    },
    formatStorageURL(fileURL) {
      return (window.location.protocol + "//" + window.location.host + "/storage/" + fileURL);
    },

	showEditMemberNoteModal(noteEditedData) 
	{
		this.noteEditedData = noteEditedData;
		this.$bvModal.show('noteEditModal');
		this.noteEdited = noteEditedData.note;
	},	

    saveNote() 
	{
		if (this.note.trim() == "") 
		{
			$('#noteEntryModal').find('.modal-content').find('.modal-body').find('.alert').remove()
			$('#noteEntryModal').find('.modal-content').find('.modal-body').prepend("<div class='alert alert-danger small'>Don't be shy, Please add a message </div>");		
			return false;
		}

		$('#addNoteSpinner').show();
		$('#addNoteFooter').hide();	

      	axios.post("/api/saveNote?api_token=" + this.api_token, {
          method: "POST",
          memberID: this.memberinfo.user_id,
		  tutorID: this.tutorinfo.id,
          note: this.note,
        }).then((response) => {
			if (response.data.success === true) 
			{
				$('#addNoteFooter').show();
				$('#addNoteSpinner').hide();
				//rewind page to ONE
				
				this.currentPage = 1;
				this.$bvModal.hide('noteEntryModal');

				//add this to highlight when redraw of data table (get notes)
				this.highlightID = response.data.note.id;

				this.$nextTick(() => {
					this.getNotes(1);
				});

			} else {
				alert ("error")
			}
        })
        .catch(function (error) {
        	console.log("Error " + error);
        });
    },	
	dateFormatter(date) 
	{
		return Moment(date).tz('japan').format('YYYY年 MM月 D日 HH:mm'); 
	},
	updateNote() 
	{

		if (this.noteEdited.trim() == "") 
		{
			$('#noteEditModal').find('.modal-content').find('.modal-body').find('.alert').remove()
			$('#noteEditModal').find('.modal-content').find('.modal-body').prepend("<div class='alert alert-danger small'>Don't be shy, Please add a message </div>");		
			return false;
		}
		
		$('#editNoteSpinner').show();
		$('#editNoteFooter').hide();

		console.log(this.noteEditedData);

      	axios.post("/api/updateNote?api_token=" + this.api_token, {
			method: "POST",
			noteID: this.noteEditedData.note_id,
			memberID: this.memberinfo.user_id,
			tutorID: this.tutorinfo.id,
			note: this.noteEdited,
        }).then((response) => {

			if (response.data.success === true) 
			{

				this.highlightID = this.noteEditedData.note_id;

				$('#editNoteFooter').show();
				$('#editNoteSpinner').hide();

				this.getNotes(this.currentPage);
				this.$bvModal.hide('noteEditModal');

			} else {
				alert ("error")
			}
        })
        .catch(function (error) {
        	console.log("Error " + error);
        });

	},

	deleteNote(id) {

      	axios.post("/api/deleteNote?api_token=" + this.api_token, {
			method: "POST",
			noteID: id,
        }).then((response) => {
			if (response.data.success === true) 
			{	
				this.getNotes(this.currentPage);

			} else {
				alert ("error")
			}
        })
        .catch(function (error) {
        	console.log("Error " + error);
        });


	},
    cleanUpEntryForm() {
      this.note = "";
    },

    getNotes(page) 
	{
      axios.post("/api/getMemberNotes?page="+ page +"&api_token=" + this.api_token, {
          method: "POST",
          tutorID: this.tutorinfo.id,
          memberID: this.memberinfo.user_id,
        })
        .then((response) => {
        	if (response.data.success === true) 
			{
				this.rows = response.data.notes.total;
				this.perPage = response.data.notes.per_page;
				this.currentPage = response.data.notes.current_page;
        		this.notes = response.data.notes.data;
				this.$nextTick(() => {	
					this.addHighlight(this.highlightID);					
				});
          	} else {
            	//@todo: HIGHLIGHT error
          	}
        })
        .catch(function (error) {
          console.log("Error " + error);
        });
    },
  },
  computed: {},
  updated: function () {},

};
</script>

<style scoped>
.member-file-wrapper {
  background-color: #f1f1f4;
  display: inline-block;
  padding: 5px;
  width: 93%;
  margin-right: 8px;
}
</style>