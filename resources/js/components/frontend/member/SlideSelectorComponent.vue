<template>
    <div class="slideSelectorContainer">

        <div class="slideSelectorWrapper">

            <b-modal id="modalSelectLesson" ref="slideSelectorModal"  @show="getLessonsList"
                title="Select a lesson for your student" 
                header-bg-variant="primary" header-text-variant="white" size="lg" hide-footer no-close-on-esc>            


                <div v-if="hasSelected == true">
                    <div class="alert alert-success" role="alert">
                        You selected a new lesson. (レッスン教材の選択が完了しました）

                    </div>                                    
                </div>

                <div v-if="hasSelected == false">
                    <div id="slideSelection" v-if="isSearching == false">


                        <div id="lesson-instructions" class="row mb-2">
                            <div class="col-4">
                                <h5 class="text-maroon">
                                    Please select a Lesson 
                                </h5>              
                                <div class="font-weight-bold">前回のレッスンコースを継続する場合、入力は必要ありません。</div>
                            </div>
                            <div class="col-8">
                                <div class="float-right">
                                    <b-button variant="primary" @click="openSearchUI()">
                                        <b-icon icon="search" aria-hidden="true"></b-icon>
                                    </b-button>

                                    <b-button variant="primary" @click="selectCategory(parentID)" v-if="parentID !== null">
                                        <b-icon icon="arrow-return-left" aria-hidden="true"></b-icon>
                                    </b-button>
                                </div>
                            </div>
                        </div>

                        <div v-if="currentFolder !== null">
                            <!--[start] Lesson Card -->
                            <div class="card">

                                <!-- [start] Lesson Information  -->
                                <div class="card px-2">
                                    <div class="row">
                                        <div class="col-6">
                                            <div id="lesson-information" class="py-3">
                                                <h4 class="card-title h3">{{ currentFolder.folder_name }}</h4>
                                                <p  class="card-text small text-secondary">{{ currentFolder.folder_description }}</p>

                                                <div id="button-lesson-select" v-if="folderCategories.length > 0 ">
                                                    <div class="alert alert-primary" role="alert">
                                                        <span class="text-success">Please select a category</span>
                                                    </div> 
                                                </div>

                                                <div v-else id="more-lessons-container" class="text-secondary">
                                                    <div class="alert alert-primary" role="alert">
                                                        <span class="text-success">Please select a lesson</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-6">

                                            <div id="lessons-container" class="accordion my-4" v-if="rows >= 1" >
                                                <b-table  id="lesson-table" ref="lesson-table"
                                                    :items="lessons" 
                                                    :fields="fields"                                                
                                                    :per-page="perPage"
                                                    :current-page="currentPage"
                                                    :striped="false"
                                                    :hover="true"
                                                    borderless
                                                    :outlined="false"
                                                    :class="'no-padding'"
                                                >

                                                
                                                    <template #cell(actions)="row" >

                                                        <b-card no-body>
                                                            <b-card-header header-tag="header" class="p-0" role="tab">
                                                                <b-button-group block class="w-100">        
                                                                    <b-button block variant="primary" @click="getLessonImages(row.index, row.item.id)">
                                                                        {{ row.item.folder_name }} 
                                                                    </b-button>
                                                                    <b-button variant="success" size="sm" class="w-25" @click.prevent="selectNewLesson(row.item.id)">                                                               
                                                                        <div class="small text-center"> Select Lesson</div>              
                                                                    </b-button>                                                                    
                                                                </b-button-group>
                                                            </b-card-header>                                                                
                                                            <b-collapse v-model="isCollapsed[row.index]" id="my-collapse-id">
                                                                <b-card-body v-if="folder_images[row.index]">
                                                                    <div class="row">
                                                                        <div class="col-4" v-for="(images, imageIndex) in folder_images[row.index]" 
                                                                                :key="'folder_images_'+row.index+'_'+imageIndex">
                                                                            <img class="img-fluid cursor-pointer" :src="getBaseURL(images.path)"  @click.prevent="imageViewer(getBaseURL(images.path))">   
                                                                        </div>
                                                                    </div>
                                                                </b-card-body>
                                                            </b-collapse>
                                                        </b-card>
                                                        

                                                    </template>
                                                </b-table>

                                        

                                            
                                                <div class="mt-3">                                                     
                                                    <!--<b-pagination v-model="currentPage" :total-rows="rows" :input="openPage('page-' +currentPage)" :per-page="perPage" :limit="10"></b-pagination>-->
                                                    <b-pagination 
                                                        v-model="currentPage" 
                                                        :total-rows="rows" 
                                                        :per-page="perPage" 
                                                        :limit="5"
                                                        @input="clearFolderImages"
                                                        aria-controls="lesson-listings"
                                                        >
                                                    </b-pagination>
                                                </div>

                                                <!--
                                                <div class="col-12 cursor-pointer" v-for="lesson in lessons" :key="'lesson_'+lesson.id" @click="selectNewLesson(lesson.id)">
                                                    <div class="card mb-3 border-primary cursor-pointer">
                                                        <div class="card-header bg-primary">
                                                            <span class="text-white">
                                                                {{ lesson.folder_name }}
                                                            </span>
                                                        </div>
                                                        <div class="card-body card-body-minimum">
                                                            <p class="card-text small">
                                                                {{ lesson.folder_description }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                -->

                                            </div>

                                            <!--
                                            <div v-else>
                                                <div class="text-center my-4">
                                                    <span class="small text-maroon">No Lessons for this category</span>
                                                </div>
                                            </div>-->
                                        </div>

                                    </div>

                                </div>
                                <!-- [end] Lesson Information  -->                           
                            </div>
                            <!--[end] Lesson Card-->
                        </div>

                            

                        <!-- Categories -->
                        <div class="mt-3" v-if="folderCategories.length >= 1">
                            <fieldset class="border p-2">

                                <legend  class="w-auto text-primary">
                                    <span v-if="parentID !== null"> Related Categories</span>
                                    <span v-else>Lesson Categories</span>
                                </legend>

                                <div id="folder-categories" class="row"  >
                                    <div class="col-3 cursor-pointer" v-for="category in folderCategories" :key="'category_'+category.id" @click="selectCategory(category.id)">
                                        <div class="card mb-3 border-primary cursor-pointer">
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
                            </fieldset>
                        </div>
                        <!--[end] Categories -->                  

                    </div>

                    <div v-else-if="isSearching == true">

                        <div id="lesson-instructions" class="row mb-2">
                            <div class="col-4">
                                <h5 class="text-maroon">Search </h5>              
                            </div>
                            <div class="col-8">
                                <div class="float-right">
                                    <b-button variant="primary" @click="closeSearchUI()">
                                        <b-icon icon="x" aria-hidden="true"></b-icon>
                                    </b-button>
                                </div>
                            </div>
                        </div>

                        <div role="group" class="input-group">
                            <!--[start] Seach Icon-->
                            <div class="input-group-prepend">
                                <div class="input-group-text">                            
                                    <b-icon icon="search" aria-hidden="true"></b-icon>                              
                                </div>
                            </div> 
                            <!--[end] Search Icon -->                        
                            <!--[start] Seach Keyword -->
                            <input id="bv-icons-table-search" type="search" v-model="searchKeyword" 
                                @blur="handleSearch()"  @keyup="handleSearch()" @change="handleSearch()" @clear:append="handleSearch()" @click="handleSearch()"
                                autocomplete="off" aria-controls="bv-icons-table-result" 
                                class="form-control"
                            >
                            <!--[end] Search Keyword-->
                        </div>

                        <!--[start] search results -->

                        <fieldset class="border p-2 mt-2">

                            <legend class="w-auto text-primary">                              
                                <span>Search Results</span>
                            </legend>

                            <div class="container">
                                <div class="row mt-1" v-if="searchResults.length >= 1">
                                    <div class="col-3 cursor-pointer" v-for="category in searchResults" :key="'search_'+category.id"  @click="selectCategory(category.id)">
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
                                <div v-else class="col-12 text-center">
                                    <div class="py-4">
                                        <span class="text-danger small">No results found</span>
                                    </div>
                                </div>
                            </div>

                        </fieldset>
                    
                        <!--[end] -->

                    </div>
                </div>

            </b-modal>

        </div>

        <div id="image-viewer-container" class="container-fluid">
            <b-modal id="modalImageViewer" size="xl" title="Image Preview" ok-only>
                <div v-if="imageURL !== null">
                    <img :src="imageURL" class="img-fluid">
                </div>
            </b-modal>
        </div>       

        <!--

        <div id="image-viewer-container" class="container-fluid">

            <b-modal id="modalImageViewer"  title="Image Preview" ok-only>
                <div v-if="imageURL !== null">
                    <img :src="imageURL" class="img-fluid">
                </div>
            </b-modal>

        </div>
        -->
                        
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
        folder_id: Number,
        reservation: Object,
        csrf_token: String,		
        api_token: String,  
        is_broadcaster: Boolean
    },
    data() {
        return {

          
            isModalMaximized: false,

            isSearching: false,
            searchTimeout: null,
            searchKeyword: null,
            searchResults: [],

            //Carousel sliders
            slide: 0,
            sliding: null,

            //Lesson Selected ID
            selectedLessonID: null,

            //[list]
            currentFolderID: null,
            currentFolder: null,
            parentID: null,
       
            folderCategories: [],
            files: [],
            imageURL: null,         

            //client ID
            lessonID: null,
            userID : null,
       

            //Model lesson Value
            lessonSelected: null,
            lessonSelectedFolderID: null,



            currentSelectedFolder: null,
            currentSelectedFiles: [],

            //Lesson list
            hasSelected: false,
            selectSuccessMessage: null,

            currentPage: null,
            rows: 0,
            perPage: 5,               
            fields: [
                //{ key: 'folder_name', label: 'Lesson', sortable: true, sortDirection: 'desc' },
                { key: 'actions', label: 'Actions' }
            ],                


            //accordion images
            folder_images: [],
            isCollapsed: [],             

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
        maximizeModal() {
            this.isModalMaximized = !this.isModalMaximized;
        },

        showCollapse(index) 
        {             
            for (var i = 0; i <= this.perPage; i++) 
            {                    
                if (i == index)  {                    
                    if (this.isCollapsed[index] == false) {
                        this.$set(this.isCollapsed, index, true)
                    } else {                          
                        this.$set(this.isCollapsed, index, false) //hide
                    } 
                } else {                        
                    this.$set(this.isCollapsed, i, false) //hide
                }                
            }                
        },    
        getLessonImages(index, folderID) {
            this.showCollapse(index) 
            axios.post("/api/getLessonImages?api_token=" + this.api_token, 
            {
                method                  : "POST",
                folderID                : folderID,
            }).then(response => {

                if (response.data.success == true) 
                {       
                    this.$set(this.folder_images, index, response.data.files)
                    this.$forceUpdate();

                } else {
                    
                }
            });
        },     
        clearFolderImages() {
            this.folder_images = [];
            for (var i = 0; i <= this.perPage; i++) {  
                this.$set(this.isCollapsed, i, false);
            }
        },
            
        openSearchUI() {
            this.isSearching = true;
        },
        closeSearchUI() {
            this.isSearching = false;
        },
        handleSearch() {            
            const str = ''+ this.searchTimeout + ''; 
            const trimmedStr = str.trim(); 
            const isEmpty = trimmedStr.length === 0;

            if (isEmpty) {
              //@todo: add empty message
            } else {
                clearTimeout(this.searchTimeout);
                // Start a new timeout to delay the search by half a second
                this.searchTimeout = setTimeout(this.search, 500);
            }
        },
        search() {
            console.log("searching...");

            axios.post("/api/searchFolders?api_token=" + this.api_token, 
            {
                method          : "POST",                
                searchKeyword          : this.searchKeyword,
               
            }).then(response => {

                if (response.data.success == true) {       
        
                    this.searchResults = response.data.folders;

                } else {                
                    //alert ("Error, we can't do a search on your keyword, please try again later")
                    this.searchResults = [];
                    this.$forceUpdate();
                }
            });
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
        selectNewLesson(folderID) {        
            this.lessonSelectedFolderID = folderID;
            this.startNewSlide();
        },
        selectCategory(id) {
            this.isSearching = false;
            if (id == 0) {                
                this.selectedLessonID = null; 
            } else {
                this.selectedLessonID = id; 
            }
            this.getLessonsList();
        },
        getLessonsList() {

            this.hasSelected = false;

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
                    this.lessons            = response.data.lessons;
                    this.rows               = response.data.lesson_rows; 
                    this.files              = response.data.files;

                    //this.lessons            = response.data.lessons.data; (lazy loading)

                    this.$forceUpdate();

                } else {
                
                    alert ("Error, we can't get your list of lesson, please try again later")
                }
            });
        },
        onSlideStart(slide) {
            this.sliding = true
        },
        onSlideEnd(slide) {
            this.sliding = false
        },
        async startNewSlide() 
        {
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

            axios.post("/api/updateSelectedLesson?api_token=" + this.api_token,
            {
                'method'        : "POST",                
                'userID'        : this.userID,
                'lessonID'      : this.lessonID,
                'folderID'      : this.lessonSelectedFolderID
            }).then(response => {

                if (response.data.success == true) {

                    console.log("updateSlideFolder", response.data)

                    this.hasSelected = true;

                    setTimeout(() => {
                        this.$parent.openNewSlideMaterials(response.data.newFolderID);
                        this.$bvModal.hide('modalSelectLesson');
                    }, 3000);
                   
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

                if (response.data.success == true) {        
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
        }
    }
 }
 </script>

 <style scoped>
    .card-body-minimum {
        min-height: 100px;
    }
 </style>