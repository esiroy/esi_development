<template>

    
    <div class="tutor-slide-notes-container">

        <div class="toggleNoteContainer w-100">
            <div id="toggleNoteViewer" @click="toggleNoteVisibility" class="btn bg-lightblue rounded-left">
                <!-- HIDE NOTES -->
                <span v-if="hideSlideNotes == true">
                    <b-icon icon="caret-down" aria-hidden="true" font-scale="1"></b-icon>
                </span>

                <!-- SHOW NOTES -->
                <span v-if="hideSlideNotes == false">
                    <b-icon icon="caret-up" aria-hidden="true" font-scale="1"></b-icon>
                </span>
            </div>
        </div>
        
        <div id="tutor-slide-notes" class="bg-lightblue">

            <div v-if="hideSlideNotes == false && loaded == false" class="text-center">
                Loading Notes...
            </div>

            <div v-if="this.note == ''">
                <span class="text-danger small"  v-if="hideSlideNotes == true">no available notes for this current slide</span>
            </div>
            <div v-else>
                <span v-html="this.note" v-if="hideSlideNotes == true"></span>
            </div>

        </div>
        
    </div>
   
</template>

<style scoped>

    .tutor-slide-notes-container {    
        position: sticky;
        margin: auto;
        bottom: 0px;
        width: 50%;
    }


    .toggleNoteContainer {    
        text-align: center;
    }

    #toggleNoteViewer {
        background: #009fd9;
        color: white;
        text-align: center;
        position: relative;
        top: 7px;
        font-size: 16px;
        padding: 0px;
        width: 120px;
        height: 22px        
    }

    #tutor-slide-notes {
        border-radius: 10px 10px 0px 0px;
        text-align: center;

        border-top: 8px solid;
        border-left: 8px solid;
        border-right: 8px solid;
       
        margin: auto;
        font-size: 12px;
        padding: 5px;
        background-color: #fff;
        border-color: #009fd9;
        max-height: 250px;
        overflow-y: scroll;
        padding:12px;     
    }

</style>

<script>
export default {
    name: "TutorSlideNotesComponent",
    components: {},
    props: {
        csrf_token: String,		
        api_token: String 
    },    
    data() {
        return {
            loaded: false,
            note: null,
            notes: [],
            hideSlideNotes: false,  
        }
    },
    mounted() {
    
        
    },
    methods: {
        toggleNoteVisibility() {        
            if (this.hideSlideNotes == true) this.hideNotes()
            else 
                this.showNotes();
        },
        loadNotes(notes) {
            this.notes = notes;           
            this.showNotes();
            this.loaded = false;
        },
        showNotes() {
            this.hideSlideNotes = true
            document.getElementById("tutor-slide-notes").style.padding = "12px";
        },
        hideNotes() {
            this.hideSlideNotes = false;
            document.getElementById("tutor-slide-notes").style.padding = "0px";
        },
        viewNote(index) {
            this.note = this.notes[index];
            this.loaded = true;
            this.$forceUpdate();
        }
    }    
}
</script>