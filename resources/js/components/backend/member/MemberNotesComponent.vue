<template>
  <div id="MemberNoteComponent" class="MemberNoteComponent">
    <div class="NoteHolder">
      <div id="notes" class="notes">
        <div class="row" v-for="(note, index) in notes" :key="index">
          <div class="col-1">
            <img :src="note.tutor.photo" class="img-fluid" />
          </div>
          <div class="col-3">{{ note.tutor.name }}</div>
          <div class="col-7">{{ note.contents.message }}</div>
        </div>
      </div>

      <div class="row">
        <div class="col-9">
          <textarea id="w3review" name="" rows="2" cols="50"></textarea>
        </div>
        <div class="col-3">
          <b-button size="sm" @click="showEntryForm">Send</b-button>
        </div>
      </div>

      <b-button size="sm" variant="dark" pill>
        <b-icon-chat-square-text></b-icon-chat-square-text>
        <small>New Note</small>
      </b-button>
    </div>
  </div>
</template>

<script>
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
      notes: [],
      message: [],
      users: [],
      files: [],
      notes: [
        {
          tutor: {
            id: 1,
            name: "tutor 1",
            photo:"http://localhost:8000/storage/user_images/tutors/original/1641392439.png",
          },
          member: { id: 1, name: "member 1" },
          contents: { id: 1, message: "TEST 1", date: "1-1-300" },
        },
        {
          tutor: {
            id: 2,
            name: "tutor 1",
            photo:"http://localhost:8000/storage/user_images/tutors/original/1641392439.png",
          },
          member: { id: 2, name: "member 2" },
          contents: { id: 1, message: "TEST 2", date: "1-2-300" },
        },
        {
          tutor: {
            id: 3,
            name: "tutor 1",
            photo:"http://localhost:8000/storage/user_images/tutors/original/1641392439.png",
          },
          member: { id: 3, name: "member 4" },
          contents: { id: 2, message: "TEST 3", date: "1-3-300" },
        },
        {
          tutor: {
            id: 4,
            name: "tutor 1",
            photo:"http://localhost:8000/storage/user_images/tutors/original/1641392439.png",
          },
          member: { id: 4, name: "member 5" },
          contents: { id: 3, message: "TEST 4", date: "1-4-300" },
        },
      ],
    };
  },
  mounted: function () 
  {
	console.log(this.tutorinfo);
	this.getNotes();

  },  
  methods: {
    showEntryForm() {
      this.message = "";
    },
	saveNote() {
	      axios.post("/api/saveNotes?api_token=" + this.api_token, {
          method: "POST",
          sender_id: this.memberinfo.userid,
          message: this.message,
        })
        .then((response) => {
          if (response.data.success === true) {
          } else {
            //@todo: HIGHLIGHT error
          }
        })
        .catch(function (error) {
          console.log("Error " + error);
        });
	},
    getNotes() {
      axios.post("/api/getMemberNotes?api_token=" + this.api_token, {
          method: "POST",
		  tutorInfo: this.tutorInfo.id,
          memberID: this.memberinfo.user_id
        })
        .then((response) => {
          if (response.data.success === true) {
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