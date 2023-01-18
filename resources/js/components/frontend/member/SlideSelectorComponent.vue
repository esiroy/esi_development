<template>
    <div class="slideSelectorContainer">

        <div class="slideSelectorWrapper">
            <b-modal ref="slideSelectorModal"  @show="getLessonsList" title="Please preview a lesson for your student" header-bg-variant="primary" header-text-variant="white" size="lg" hide-footer no-close-on-esc>
                <!--
                <template #modal-header>
                    <div class="mx-auto">
                        <h4 class="text-center text-white">Please preview a lesson for your student</h4>
                    </div>    
                </template>
                -->

                <div id="slideSelection">
                    <h5 class="text-maroon">Please preview and select a Lesson </h5>
              
                    <hr/>

                    Select Lesson 
                    <b-form-select id="lessonSelector" v-model="lessonSelectedFolderID" :options="lessonOptions" v-on:change="getOptionSelected('lessonSelector')"></b-form-select>

                    <!--[START] - (NEW! 2023) PREVIEW: Lesson Image gallery for the selected lesson-->
                    <div class="mt-3" v-if="folder !== null">
                        <div class="pt-2">You have selected:</div>                
                        <div>Lesson Name: <strong>{{ folder.folder_name }} </strong></div>
                        <div>Lesson Description: {{ folder.folder_description }}</div>                 
                        <div class="container pt-4" v-if="files != null">
                            <div class="row">
                                <div class="col-2" v-for="file in files" v-bind:key="file.id">                            
                                    <img :src="getBaseURL(file.path)" class="img-fluid  cursor-pointer" @click="imageViewer(getBaseURL(file.path))"/>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center">
                            <span class="text-danger">No Images found for this slides</span>
                        </div>

                    </div>
                    <div v-else class="mt-3 text-center">
                        <div class="text-danger">Please select a folder</div>
                    </div>
                    <!--[END] PREVIEW: Lesson Image gallery for the selected lesson-->                      
               
                    <div class="my-5 text-center">
                        <button class="btn btn-success" @click="startNewSlide()">
                            Choose Slide and Start Lesson
                        </button>
                    </div>            
                </div>
            </b-modal>

        </div>

        <div id="image-viewer-container" class="container-fluid">
            <b-modal id="modalImageViewer"  title="Image Preview" ok-only>
                <div v-if="imageURL !== null">
                    <img :src="imageURL" class="img-fluid">
                </div>
            </b-modal>
        </div>
                        
    </div>
</template>

<style lang="scss" scoped>
    .slideSelectorContainer{
        .slideSelectorWrapper {
            color: #000;
        }
    }
</style>

<script>
 export default {
    name: "slideSelectorComponent",
    props: {
        csrf_token: String,		
        api_token: String,  
        isBroadcaster: {
            type: [Boolean],
            required: true        
        },           

        reservation: Object,
        folder_id: {
            type: [String, Number],
            required: true        
        },        
    },
    data() {
        return {

            //client ID
            lessonID: null,
            userID : null,

            //Lesson Options
            lessonOptions: [],

            //Selected lesson Options
            selectedOption: null,

            //Model lesson Value
            lessonSelected: null,
            lessonSelectedFolderID: null,

            //files
            files: null,
            folder: null,

            imageURL: null            
        }
    },
    mounted() { 

        if (this.lessonSelectedFolderID == null) {
        
            this.lessonSelectedFolderID = this.$props.folder_id;

            this.getLessonSelectedPreviewByID(this.lessonSelectedFolderID )

        }
        
    },
    methods: {      
        openSlideSelector(lessonID, userID) {
        
            //MEMBER INFO
            this.lessonID = lessonID;
            this.userID     = userID;

            this.$refs['slideSelectorModal'].show();
        },
        closeSlideSelector() {
            this.$refs['slideSelectorModal'].hide();
        },
        async updateSlideFolder() {

            //@todo: update the selected folder from

            axios.post("/api/updateSelectedLesson?api_token=" + this.api_token,
            {
                'method'        : "POST",
                'lessonID'      : this.lessonID,
                'userID'        : this.userID,
                'folderID'      : this.lessonSelectedFolderID

            }).then(response => {

                    if (response.data.success == true) {
                    
                        this.$parent.openNewSlideMaterials();

                    } else {                   

                        alert (response.data.message);
                        
                    }
                
            });           

        },
        async startNewSlide() {

            this.updateSlideFolder();
            
        },
        getBaseURL(path) {
            return window.location.origin + "/" +path
        },
        imageViewer(imageURL) {

            this.imageURL = imageURL;

            this.$bvModal.show('modalImageViewer');
        },        
        getFolderOptions(FolderName, folders, hierarchy) 
        {   
                if (hierarchy == 0) 
                {
                    this.lessonOptions = [{
                        value: null,
                        html: "Please select lesson",       
                        label: "Please select lesson",
                        description:  "Please select lesson"                            
                    }];                
                }

                folders.forEach((folder) => { 

                    let folderOptionName = null;

                    if (FolderName !== null) {
                        folderOptionName = FolderName + " ====> " + folder.name;
                    } else {
                        folderOptionName = folder.name;                    
                    }


                    this.lessonOptions.push({                  
                        id              : folder.id,
                        name            : folder.name,
                        label           : folderOptionName,
                        html            : folderOptionName,
                        description     : folder.description,                             
                        value           : folder.id
                    });    
                       
                    if (folder.children.length >= 1) 
                    {
                        this.getFolderOptions(folderOptionName, folder.children, hierarchy + 1);
                    }

                });
                
        },        
        getLessonsList() 
        {
            axios.post("/api/get_folders?api_token=" + this.api_token, 
            {
                method          : "POST",
                lessonID         : this.selectedLessonID,
                //public_folder_id : null,
            }).then(response => {

                if (response.data.success == true) {
                    
                    this.getFolderOptions(null, response.data.folders, 0);
                }
            });
        },
        getOptionSelected(targetID) 
        {
            let selectedID = document.getElementById(targetID).value;
            this.getLessonSelectedPreviewByID(selectedID);

            let select = document.getElementById(targetID);
            let selectedIndex = select.selectedIndex;
            this.selectedOption = this.lessonOptions[selectedIndex];  

            return this.selectedOption;
        },        

        getLessonSelectedPreviewByID(lessonSelectedFolderID) 
        {

            
                axios.post("/api/getLessonSelectedPreview?api_token=" + this.api_token, 
                {
                    method                  : "POST",
                    //userID                  : this.member.userid,
                    userID                  : this.reservation.member_id,
                    lessonID                : this.selectedLessonID,
                    lessonSelectedFolderID  : lessonSelectedFolderID

                }).then(response => {

                    if (response.data.success == true) 
                    {        
                        this.folder = response.data.folder;           
                    
                        //determine the file
                        if (response.data.files.length == 0) {
                            this.files = null;
                            this.$forceUpdate();
                        } else {
                            this.files = response.data.files;
                            this.$forceUpdate();
                        }

                    } else {
                        //@note:  nullify files to null to make the notication appear
                        this.files = null;
                        this.folder = null;   
                        this.$forceUpdate();  
                    }
                });

            },             
    }
 }
 </script>