<template>
    <div class="slideSelectorContainer">

        <div class="slideSelectorWrapper">

            <b-modal ref="slideSelectorModal"  @show="getLessonsList" title="Select a lesson for your student" header-bg-variant="primary" header-text-variant="white" size="lg" hide-footer no-close-on-esc>
            
                <div id="slideSelection">

                    <h5 class="text-maroon">Please select a Lesson </h5>
              
                    <div class="mb-2">
                        <button class="btn btn-primary mr-4" @click="selectCategory(parentID)" v-if="parentID !== null">
                            Previous Folder
                        </button>
                    </div>

                    <div v-if="currentFolder !== null">
                        <div class="card flex-row flex-wrap">

                            <div class="card-header border-0 w-25">
                                {{ "Root Category"}}
                            </div>
                            <div class="card-block px-2">
                                <h4 class="card-title"> {{ currentFolder.folder_name }}</h4>
                                <p class="card-text">{{ currentFolder.folder_description }}</p>
                                <a href="#" class="btn btn-primary">Select Lesson</a>
                            </div>
                            <div class="w-100"></div>
                            <div class="card-footer w-100 text-muted">
                                Footer stating cats are CUTE little animals
                            </div>
                        </div>
                    </div>


                    <div class="category-files">
                        <div class="file" v-for="file in files" :key="'file_'+ file.id">
                            <img :src="getBaseURL(file.path)" class="img-fluid  cursor-pointer" @click="imageViewer(getBaseURL(file.path))"/>
                        </div>
                    </div>

                    <hr/>

                    <fieldset class="border p-2">

                        <legend  class="w-auto text-primary">
                            <span v-if="parentID !== null"> Related Lesson Categories</span>
                            <span v-else>Lesson Categories</span>
                        </legend>

                        <div id="folder-categories"  class="row" v-if="folderCategories.length >= 1" >

                            <div class="col-3" v-for="category in folderCategories" :key="'category_'+category.id" @click="selectCategory(category.id)">

                                <div class="card mb-3 border-primary">
                                    <div class="card-header bg-primary">
                                        <span class="text-white">
                                            {{ category.folder_name }}
                                        </span>
                                    </div>
                                    <div class="card-body card-body-minimum">
                                        <p class="card-text small">
                                            {{ category.folder_description }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div v-else>
                            <div class="text-center my-4">
                                <span class="small text-maroon">No more lesson category</span>
                            </div>
                        </div>

                    </fieldset>
                    



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

            selectedLessonID: null,

            //[list]
            currentFolderID: null,
            currentFolder: null,
            parentID: null,
       
            folderCategories: [],
            files: [],




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

            imageURL: null,           

            currentSlide: null,
            totalSlide: null,
        }
    },
    mounted() { 

        if (this.$props.folder_id == '' || this.$props.folder_id == 'null' || this.$props.folder_id == null) {                
            this.lessonSelectedFolderID = "null";
        }


        if (this.lessonSelectedFolderID == null) {        
            this.lessonSelectedFolderID = this.$props.folder_id;
            this.getLessonSelectedPreviewByID(this.lessonSelectedFolderID)
        }
        
    },
    methods: {      

        selectCategory(id) {

            if (id == 0) {                
                this.selectedLessonID = null; 
            } else {
                this.selectedLessonID = id; 
            }
            
            this.getLessonsList();
        },
        getLessonsList() {
            axios.post("/api/getLessonFolders?api_token=" + this.api_token, 
            {
                method          : "POST",                
                folderID        : this.selectedLessonID,
                //public_folder_id : null,
            }).then(response => {

                if (response.data.success == true) {

                    this.parentID           = response.data.parentID;
                    this.currentFolder      = response.data.currentFolder;
                    this.folderCategories   = response.data.folderCategories;     
                    this.files              = response.data.files;

                    this.$forceUpdate();

                } else {
                
                    alert ("Error")
                }
            });
        },

        getFolderOptions(FolderName, folders, hierarchy) 
        {   
                if (hierarchy == 0) 
                {
                    this.lessonOptions = [{
                        id: "null",
                        value: "null",
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

        getOptionSelected(targetID) 
        {
            let selectedID = document.getElementById(targetID).value;
            this.getLessonSelectedPreviewByID(selectedID);

            let select = document.getElementById(targetID);
            let selectedIndex = select.selectedIndex;
            this.selectedOption = this.lessonOptions[selectedIndex];  

            return this.selectedOption;
        },
        openSlideSelector(lessonID, userID) {
        

            this.selectedLessonID = null;
            this.parentID  = null;

            //MEMBER INFO
            this.lessonID   = lessonID;
            this.userID     = userID;

            this.$refs['slideSelectorModal'].show();
        },
        closeSlideSelector() {
            this.$refs['slideSelectorModal'].hide();
        },
        async startNewSlide() {

            if ( this.lessonSelectedFolderID !== "null" ) {

                //take the lesson id from the reservation

                this.lessonID   = this.$props.reservation.schedule_id
                this.userID     = this.$props.reservation.member_id;

                this.updateSlideFolder();
            } else {
                alert ("please select a new lesson")
            }
            
            
        },        
        async updateSlideFolder() {

            //@todo: update the selected folder from

            axios.post("/api/updateSelectedLesson?api_token=" + this.api_token,
            {
                'method'        : "POST",
                'lessonID'      : this.lessonID,
                'userID'        : this.userID,
                'folderID'      : this.lessonSelectedFolderID,
                

            }).then(response => {

                if (response.data.success == true) {

                    this.$parent.openNewSlideMaterials(response.data.newFolderID);

                } else {                   

                    alert (response.data.message);
                    
                }
                
            });           

        },
        getBaseURL(path) {
            return window.location.origin + "/" +path
        },
        imageViewer(imageURL) {
            this.imageURL = imageURL;
            this.$bvModal.show('modalImageViewer');
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

 <style scoped>
    .card-body-minimum {
    
        min-height: 100px;
    }

 </style>