<template>

	<div id="lesson-selector">

		<b-modal id="modalLessonSelection" title="Select Lesson" header-bg-variant="primary" header-text-variant="white" size="xl" hide-footer>

			<template #modal-header>
 				<h4 class="modal-title">Select Lesson</h4>
				<div class="modal-header-wrapper" v-if="showSearch == false">
					<b-icon v-if="urlArray.length >= 1" icon="arrow-left" size="lg" class="back-icon" @click="goBack"></b-icon>
					<b-icon icon="search" size="lg" class="search-icon" @click="showSearchUI"></b-icon>					
					<b-icon icon="x" size="lg" class="close-icon font-weight-bold" @click="closeModal"></b-icon>
				</div>
				<div class="modal-header-wrapper" v-if="showSearch == true">
					<b-icon icon="arrow-left" size="lg" class="back-icon" @click="hideSearchUI"></b-icon>					
					<b-icon icon="x" size="lg" class="close-icon font-weight-bold" @click="closeModal"></b-icon>
				</div>
			</template>

			<template #modal-footer>
				<div class="footer-button-container">
					<b-button variant="primary" @click="hideLessonSelectionModal">Cancel</b-button>
				</div>
			</template>

			<div class="row">

				<div id="left-container" class="col-3">

					<div class="selected-lesson-container mb-2" v-if="!selectedfolder">
						<fieldset class="border p-2">	
							<legend class="w-auto  small font-weight-bold">You have not selected a lesson </legend>
							<div class="text-center">			
								<div class="small text-muted py-4">
									Please select your lesson
								</div>
							</div>
						</fieldset>


						<!--[start] Note -->
						<fieldset class="border mt-2 p-2">	
							<legend class="w-auto small font-weight-bold">Please Note</legend>
							<div class="text-center small py-4">			
								<span class="small text-danger">
									If you can't select a lesson our system will automatically select your next lesson for you.
								</span>

								<hr class="hr">

								<div class="small text-primary"  v-if="nextLessonFolderName !== null">
									Next Lesson Title: 
									<div>{{ nextLessonFolderName }}</div>
								</div>

								<div class="small text-primary" v-if="nextLessonFolderDescription !== null">
									Description: 
									{{ nextLessonFolderDescription }}									
								</div>
							</div>
						</fieldset>
						<!--[start] Note -->

					</div>
					<div class="selected-lesson-container mb-2" v-if="selectedfolder">
						<fieldset class="border p-2">	
							<legend class="w-auto small font-weight-bold">Your Lesson Selected</legend>
							<div id="selected-lesson-info" class="card">			
								<div :id="selectedfolder.id" class="card-header">
									<span class="small">{{ selectedfolder.folder_name}}</span>
								</div>
								<div class="card-body">
									<div class="desc">
										<div class="font-weight-bold small">Description:</div>
										<span class="small">{{ selectedfolder.folder_description}}</span>	
									</div>

									<hr class="hr text-dark my-2"/>

									<div class="row m-0 p-0">
										<div class="col-4 m-0 p-1" v-for="(file, fileIndex) in selectedFiles" :key="'file-'+fileIndex" >
											<img :src="getBaseURL(file.path) " class="img-fluid rounded img-thumbnail">
										</div>
									</div>
								</div>
							</div>
						</fieldset>
					</div>
				</div>


				<div id="right-container" class="col-9">

					<!--[start] Loader -->
					<div class="loader text-center" v-show="isloadingCategories">
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
					<!--[end] Loader -->

					<div v-if="isViewingSearched == true">

							<!--[start] Breadcrumbs-->
							<div class="row">				
								<div class="col-12 mb-2">
									<div class="bg-light rounded py-1 px-3 mb-2">
										<!--[start] All Categories -->
										<a :href="'#all'" @click.prevent="showAllFolder()">
											<span class="text-primary small">All Categories</span>
										</a>
										<span class="text-secondary" v-show="urlArray.length >= 1"> {{ " > " }} </span>
										<!--[end] All Categories -->

										<!--[start] List Of Categories -->
										<span v-for="(segment, index) in urlArray" :key="'url-'+index">
											<a :href="'#'+segment.folder_name" @click.prevent="jumpToFolder(segment, index)" >
												<span class="text-primary small">{{ segment.formatted_folder_name }}</span>
											</a>
											<span class="text-secondary"  v-if="index >= 0 && index < (urlArray.length -1 ) "> {{ " > " }} </span>
										</span>	
										<!--[end] List Of Categories -->

									</div>
								</div>				
							</div>
							<!--[end] Breadcrumbs-->

					</div>

					<!--[start] Search -->
					<div v-else-if="showSearch == true">

						<fieldset class="border p-2">

							<legend class="w-auto small font-weight-bold text-secondary">Search Lesson</legend>	

							<div class="row">
								<div class="input-group mb-3 col-12 text-center">

									<input id="bv-icons-table-search" type="search" 
										placeholder="Search"
										v-model="searchKeyword" 
										@blur="handleSearch()"  
										@keyup="handleSearch()" 
										@change="handleSearch()" 
										@clear:append="handleSearch()" 
										@click="handleSearch()"
										autocomplete="off" aria-controls="bv-icons-table-result" 
										class="form-control form-control-sm"
									>

									<div class="input-group-append">
										<b-button variant="primary" size="sm" @click="search()">
											<b-icon
												icon="search"
												size="sm"
												class="search-icon"
												
											></b-icon>
										</b-button>							
									</div>
								</div>
							</div>


							<div class="container">
								<div class="row mt-1" v-if="searchResults.length >= 1">	

									<div v-for="(searchCategory, index) in searchResults" :key="index" 
										class="col-12 col-xs-6 col-sm-6 col-md-3 col-lg-2 cursor-pointer" >


										<div :id="'searchCategory-' + searchCategory.id" 
											class="card text-white mb-2" v-b-tooltip.hover 
											:target="'#searchCategory-' + searchCategory.id" 
											@click="viewSearchFolder(searchResults[index])">
											<div class="card-header bg-primary text-ellipsis">
												{{ searchCategory.formatted_folder_name }}
											</div>
											<div class="card-body m-0 p-0" v-if="folderType == 'parent'" >
												<p class="card-text min-height">
													<img :src="getBaseURL(searchCategory.thumb_path)" v-if="searchCategory.isThumbExist == true" class="thumb-image img-fluid" />            
												</p>							
											</div>						
											<div class="card-body m-0 pt-2" v-else>
												<!-- the subfolder (nothin should be here as requested )-->
												<div class="text-dark" text-ellipsis>
													{{ searchCategory.folder_description }}
												</div>
											</div>
										</div>	
				
										<!--[start] search b-tooltip
										<LessonSelectorTooltipComponent 
											ref="LessonSearchTooltip" 
											:target="'searchCategory'"
											:category="searchCategory"
											:api_token="api_token" 
											:csrf_token="csrf_token"
											
										/>				
										[end] search b-tooltip-->
									</div>

								</div>
								<div v-else class="col-12 text-center">
									<div class="py-4">
										<span class="text-danger small">No results found</span>
									</div>
								</div>
							</div>


						</fieldset>

					</div>
					<!--[end] Search -->

					<!--[start] Categories -->
					<div v-else>

						<div class="main-content" v-show="!isloadingCategories">		

							<!--[start] Category Info -->
							<div class="bg-light rounded py-1 px-3 mb-2">
								<span class="small">前回のレッスンコースを継続する場合、入力は必要ありません。</span>
							</div>
							<!--[end] Category Info -->


							<!--[start] Breadcrumbs-->
							<div class="row">				
								<div class="col-12 mb-2">
									<div class="bg-light rounded py-1 px-3 mb-2">
										<!--[start] All Categories -->
										<a :href="'#all'" @click.prevent="showAllFolder()">
											<span class="text-primary small">All Categories</span>
										</a>
										<span class="text-secondary" v-show="urlArray.length >= 1"> {{ " > " }} </span>
										<!--[end] All Categories -->

										<!--[start] List Of Categories -->
										<span v-for="(segment, index) in urlArray" :key="'url-'+index">
											<a :href="'#'+segment.folder_name" @click.prevent="jumpToFolder(segment, index)" >
												<span class="text-primary small">{{ segment.formatted_folder_name }}</span>
											</a>
											<span class="text-secondary"  v-if="index >= 0 && index < (urlArray.length -1 ) "> {{ " > " }} </span>
										</span>	
										<!--[end] List Of Categories -->

									</div>
								</div>				
							</div>
							<!--[end] Breadcrumbs-->

							<!--[start] Message -->
							<transition name="fade">
								<div class="success-message rounded py-1 px-3 mb-2" v-if="showSuccessMessage">

									<div id="message">
										<span class="check-mark">&#10003;</span>
										<span class="small pr-2">You have successfully selected a lesson</span>
										<span class="small">{{'(レッスン教材の選択が完了しました）'}}</span>
									</div>

									<div id="message-exit mt-2">
										<span class="small">This popup window will close in 3 seconds</span>
									</div>

								</div>
							</transition>
							<!--[end] Message -->

							<!--[start] Lesson List -->
							<div id="lesson-list" v-if="lessonRows >= 1" class="row mb-2">
								<div id="lessons-container" class="col-12">
									<fieldset class="border p-2">	
										<legend class="w-auto small font-weight-bold text-secondary">Lessons</legend>					
										<div id="lessons" class="accordion">
											<b-table id="lesson-table" ref="lesson-table" 
												:items="lessons" 							
												:fields="fields"                                                
												:per-page="perPage"
												:current-page="currentPage"
												:striped="false"
												:hover="true"							
												:outlined="true"
												:class="'no-padding'"
												no-header
												thead-class="hidden_header"
												borderless>
												
												<template #cell(actions)="row">
													<b-card no-body>
														<b-card-header header-tag="header" class="p-0" role="tab">
															<b-button-group block class="w-100">        
																<b-button block variant="primary" @click="getLessonImages(row.index, row.item.id)">
																	<span v-ucwords>{{ row.item.folder_name }}</span>
																</b-button>
																<b-button variant="success" size="sm" class="w-25" @click.prevent="selectNewLesson(row.item.id)">                                                               
																	<div class="small text-center"> Select Lesson</div>              
																</b-button>                                                                    
															</b-button-group>
														</b-card-header>                                                                
														<b-collapse v-model="isCollapsed[row.index]" :id="'accordion-'+row.index">
															<b-card-body>
																<div class="loader text-center" v-show="isloadingImages">
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
																<div class="content" v-show="!isloadingImages" @contextmenu="preventRightClick">

																	<div class="row" v-if="folder_images[row.index]">
																	
																		<div class="col-4" v-for="(images, imageIndex) in folder_images[row.index]" :key="'folder_images_'+row.index+'_'+imageIndex">

																			<img v-if="isBook == false" class="img-fluid cursor-pointer" :src="getBaseURL(images.path)"  
																				@click.prevent="imageViewer(imageIndex, folder_images[row.index])">   


																			<img v-if="isBook == true " class="img-fluid" :src="getBaseURL(images.path)">   
																		</div>
																	</div>
																</div>											
															</b-card-body>
														</b-collapse>
													</b-card>
												</template>
											</b-table>					
											<div class="mt-3">    
												<b-pagination 
													v-model="currentPage" 
													:total-rows="lessonRows" 
													:per-page="perPage" 
													:limit="lessonRowLimit"
													@input="clearFolderImages"
													aria-controls="lesson-listings"
													>
												</b-pagination>
											</div>
										</div>
									</fieldset>
								</div>
								
							</div>
							<!--[end] Lesson List -->

							<!--[start] Category List -->
							<fieldset class="border p-2" v-if="folderCategories.length >= 1">	
					
								<!--<legend class="w-auto small font-weight-bold text-secondary" v-show="urlArray.length == 0">Categories</legend>
								<legend class="w-auto small font-weight-bold text-secondary" v-show="urlArray.length >= 1">SubCategories</legend>-->

								<legend class="w-auto small font-weight-bold">Categories</legend>

								<div class="container">
									<div id="categories-list" class="row">		

										<div id="parent-categories" v-for="(category, index) in folderCategories" :key="index" 
											class="col-12 col-xs-6 col-sm-6 col-md-3 col-lg-2 cursor-pointer px-0" >

											<div :id="'category-' + category.id" class="card text-white mb-2" v-b-tooltip.hover :target="'#category-' + category.id" 
											@click="viewFolder(folderCategories[index])">
												<div class="card-header bg-primary text-ellipsis">
													{{ category.formatted_folder_name }}
												</div>
												<div class="card-body m-0 p-0" v-if="folderType == 'parent'" >
													<p class="card-text min-height">
														<img :src="getBaseURL(category.thumb_path)" v-if="category.isThumbExist == true" 
														class="thumb-image img-fluid" />            
													</p>							
												</div>						
												<div class="card-body m-0 pt-2" v-else>
													<!-- the subfolder (nothin should be here as requested )-->
													{{ category.folder_description }}							
												</div>
											</div>

											<!--[start] b-tooltip-->
											<LessonSelectorTooltipComponent 
												ref="LessonSelectorTooltip" 
												:target="'category'"
												:category="category"
												:api_token="api_token" 
												:csrf_token="csrf_token"
												@child-select-folder="viewFolder(category)"
												@child-view-folder="viewFolder(category)"

											/>				
											<!--[end] b-tooltip-->
										</div>
									</div>
								</div>

							</fieldset>			

							<!--[end] Category List -->
						</div>
					</div>
					<!--[end] Categories -->

				</div>
			</div>
		</b-modal>

		<LessonSelectorImageViewerComponent ref="LessonSelectorImageViewer"/>

	</div>
	
</template>

<script>

import LessonSelectorTooltipComponent from './LessonSelectorTooltipComponent.vue';
import LessonSelectorImageViewerComponent from './LessonSelectorImageViewerComponent.vue';

export default {
	name: 'LessonSelectorComponent',
	components: { 
		LessonSelectorTooltipComponent,
		LessonSelectorImageViewerComponent
	},
  	props: {
    	csrf_token: String,
    	api_token: String,
  	},
	directives: {
		ucwords: {
			update: function(el) {
				el.textContent = el.textContent
				.split(' ')
				.map(word => {
					if (word.charAt(0) === word.charAt(0).toUpperCase()) {
					return word;
					} else {
					return word.charAt(0).toUpperCase() + word.slice(1);
					}
				})
				.join(' ');
			}
    	}
	},	
  	data() {
		return {    

			isBook: false,
			showSearch: false,

			isViewingSearched: false,
			viewCurrentLessonIndex: null,
			viewCurrentCategory: null,

			//Next Lesson
			nextLesson: null,
			nextLessonFolderName : null,
			nextLessonFolderDescription : null,

			searchTimeout: null,
			searchKeyword: null,
			searchResults: [],			

			showSuccessMessage: false,

			folderCategories: [],
			
			urlArray: [],
			folderType: 'parent',

			currentPage: null,
			viewCurrentPage: null,
			lessonRowLimit: 5,

			lessonRows: 0,
			perPage: 5,               
			fields: [
				//{ key: 'folder_name', label: 'Lesson', sortable: true, sortDirection: 'desc' },
				{ key: 'actions', label: 'Actions' }
			],   

			//accordion images
			folder_images: [],
			isCollapsed: [],

			currentSelectedCategory: null,

			isloadingCategories: false,
			isloadingImages: false,

			//current
			tutor: null,
			member: null,
			reservation: null,
			
			lessonSelectedFolderID : null,
			memberSelectedLesson : null,			
			selectedfolder: null,
			selectedFiles: null,

		}
  },
  mounted() {
    window.lessonSelectorComponent = this;   
  },
  methods: {
	
    showLessonSelectionModal(tutor, member, reservation) {
		this.isViewingSearched = false;
	
		this.tutor           = JSON.parse(tutor);
		this.reservation     = JSON.parse(reservation); 
		this.member          = JSON.parse(member);


		console.log(this.tutor)
		console.log(this.member)
		console.log(this.reservation)
				

		this.getMemberLessonSelected(this.reservation, this.member);

		/** this will be called from member /index */
		if (!this.currentSelectedCategory) {
			this.showAllFolder();			
		} 
      	this.$bvModal.show("modalLessonSelection");      

    },

	/** [START] - IMAGE VIEWER */
	imageViewer(index, images) {
		this.$refs['LessonSelectorImageViewer'].showImageViewer(index, images);
	}, 
	/** [END] - IMAGE VIEWER */
    preventRightClick(event) {
      event.preventDefault();
    },
	showSearchUI() {	
		this.isViewingSearched = false;
		this.showSearch = true;
	},	
	hideSearchUI() {
		this.isViewingSearched = false;
		this.showSearch = false;
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

		// Clear previous search results
		this.searchResults = [];

		axios.post("/api/searchFolders?api_token=" + this.api_token, {
			method: "POST",
			searchKeyword: this.searchKeyword,
		})
		.then((response) => {
			if (response.data.success == true) {
				this.searchResults = response.data.folders;
			} else {
				// Handle error case
				this.searchResults = [];
				this.$forceUpdate();
				// alert("Error, we can't do a search on your keyword, please try again later");
			}
		});
		
	},
	viewFolder(category) {	
		console.log(category);
		this.urlArray.push(category);
		this.getLessonsList(category.id);
		this.currentSelectedCategory = category;

		//[NEW!] force select page
		this.currentPage = this.viewCurrentPage;



	},	
	viewSearchFolder(category) {	

		//reset the view page/ lesson
		this.viewCurrentPage 	= null;
		this.viewCurrentLessonIndex 	= null;
		


		//we will deactive seach
		this.showSearch = false;
		this.isViewingSearched = false;	
		
	

		if (category.subcategoryCounter >= 1) {

			this.urlArray = category.parentFolders;
			this.viewFolder(category);
		} else {

			//this is for lesson
			this.viewCurrentCategory = category;
			let parentLength = category.parentFolders.length - 2;		
			
	
			//@note: this will force open the lesson
			this.viewCurrentPage 	= Math.ceil((category.order_id) / this.perPage);

			//calculate index
			let currentIndex =  category.order_id - ((this.viewCurrentPage-1)* this.perPage)
			this.viewCurrentLessonIndex 	= currentIndex;

			this.viewFolder(category.parentFolders[parentLength]);

			
		}	
	},	
    getLessonsList(selectedLessonID) {

		this.isloadingCategories = true;

		axios.post("/api/getLessonFolders?api_token=" + this.api_token, {
			method: "POST",
			folderID: selectedLessonID,
			//public_folder_id : null,
		}).then((response) => {

			this.isloadingCategories = false;

			if (response.data.success == true) {
				this.parentID = response.data.parentID;
				this.currentFolder = response.data.currentFolder;
				this.folderType = response.data.folderType;
				this.folderCategories = response.data.folderCategories;
				this.lessons = response.data.lessons;
				this.lessonRows = response.data.lesson_rows;
				this.files = response.data.files;
				//this.lessons            = response.data.lessons.data; (lazy loading)
				

				this.$nextTick(() => { 

					if (this.viewCurrentPage && this.viewCurrentLessonIndex) {
						this.getLessonImages(this.viewCurrentLessonIndex -1, this.viewCurrentCategory.id)
					
					}					
				}); 



				this.$forceUpdate();

			} else {
				alert(
					"Error, we can't get your list of lesson, please try again later"
				);
			}
        });
    },	
	goBack() {
	
		this.isViewingSearched = false;
		this.showSearch = false;

		let urlLength = this.urlArray.length -1;
		console.log(urlLength);

		if (urlLength == 0 ) {
			this.showAllFolder();		
		} else if (urlLength >= 1) {
			let category = this.urlArray[this.urlArray.length - 2];
			this.jumpToFolder(category, this.urlArray.length - 2);
		}
	},
    closeModal() {
      this.$bvModal.hide("modalLessonSelection");     
    },
	getMemberLessonSelected(reservation, member) {

		console.log("reservation", reservation.schedule_id);
		console.log("member", member.userid);

		axios.post("/api/getMemberLessonSelected?api_token=" + this.api_token, 
		{
			method          : "POST",
			lessonID        : reservation.schedule_id,
			userID          : member.userid,			
		}).then(response => {

			if (response.data.success == true) {				

				this.selectedfolder = response.data.selectedfolder;
				this.selectedFiles = response.data.selectedFiles;

				this.memberSelectedLesson = response.data.memberSelectedLesson;
				this.lessonSelectedFolderID =  response.data.memberSelectedLesson.folder_id;
				this.$forceUpdate();

			} else {  	
				this.nextLesson =  JSON.parse(response.data.nextLesson);

				this.nextLessonFolderName = this.nextLesson.folder_name
				this.nextLessonFolderDescription = this.nextLesson.folder_description

				this.$forceUpdate()	
			}
		});
	},
	selectNewLesson(folderID) {        
		this.lessonSelectedFolderID = folderID;
		this.saveOptionSelected(folderID);
	},	
	saveOptionSelected(folderID) {

		axios.post("/api/saveSelectedLessonSlideMaterial?api_token=" + this.api_token, 
		{
			method          : "POST",
			userID          : this.member.userid,
			lessonID        : this.reservation.schedule_id,
			folderID        : folderID,

		}).then(response => {

			if (response.data.success == true) {

				this.showSuccessMessage = true;
				this.selectedfolder = response.data.selectedfolder;
				this.selectedFiles = response.data.selectedFiles;

				this.$forceUpdate()		

				setTimeout(() => {
					this.showSuccessMessage = false;

					//(added August 2023)
					if (typeof this.$parent.openNewSlideMaterials === 'function') {
						// The method exists in the parent component
						console.log('openNewSlideMaterials exists in the parent component');
						this.$parent.openNewSlideMaterials(response.data.newFolderID);

					} else {
						// The method does not exist in the parent component (this will not fire)
						// Standalone: This is method was used in member and tutor 
						console.log('openNewSlideMaterials does not exist in the parent component');
					}
					
					this.$bvModal.hide("modalLessonSelection");      

				}, 3000);
				
			} else {
				alert (response.data.message);
			}
		});

	},  	
	showAllFolder() {
		this.urlArray = [];	
		this.getLessonsList();
	},	
	jumpToFolder(category, index) {
		this.isViewingSearched = false;
		this.showSearch = false;

		let limit = this.urlArray.length - index;
		for (let i = 1; i <= (limit); i++) {		
			this.urlArray.pop();
		}		
		this.viewFolder(category);
	},
    getBaseURL(path) {
      	return window.location.origin + "/" + path;
    },

	selectFolder(id) {
		this.$bvModal.hide("modalLessonSelection");
	},
	getLessonImages(index, folderID) {

		console.log(index + " get lesson image", folderID);

		this.isBook = false;

		this.isloadingImages = true;
		

		axios.post("/api/getLessonImages?api_token=" + this.api_token, 
		{
			method                  : "POST",
			folderID                : folderID,
		}).then(response => {

			if (response.data.success == true) {   
				this.isloadingImages = false;
				this.isBook = response.data.is_book;
				this.$set(this.folder_images, index, response.data.files)
				this.$forceUpdate();

				this.showCollapse(index);

			} else {
				console.log("error getting lesson images")				
			}
		});
	}, 	
	clearFolderImages() {
		this.folder_images = [];
		for (var i = 0; i <= this.perPage; i++) {  
			this.$set(this.isCollapsed, i, false);
		}
	},	
	
	showAlert(text) {
		alert (text);
	},
	showCollapse(index) 
	{             
		for (var i = 0; i <= this.perPage; i++) 
		{                    
			if (i == index)  {                    
				if (this.isCollapsed[index] == false) {

					console.log("showing ==> ", index)
					this.$set(this.isCollapsed, index, true)
				} else {                          
					this.$set(this.isCollapsed, index, false) //hide
				} 
			} else {                        
				this.$set(this.isCollapsed, i, false) //hide
			}                
		}                
	},
	hideLessonSelectionModal() {
		this.$bvModal.hide("modalLessonSelection");
	},				




  },
};


</script>

<style scoped>
.back-icon {
  	cursor: pointer;
	margin: 0px 6px 0px;
	font-size: 18px;

}

.search-icon {
    cursor: pointer;
    margin: 0px 6px 0px;
    font-size: 18px;
}

.close-icon {
    cursor: pointer;
    margin: 0px 6px 0px;
    font-size: 18px;
}

.modal-title {
  text-align: center;
  flex-grow: 1; /* Allow title to occupy remaining space */
  width: 100%;
}

.modal-header-wrapper {
  display: flex;
  align-items: center;
  justify-content: flex-end; /* Align icons to the right */
 
}

.modal-content {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.min-height {
	min-height: 100px
}

.text-ellipsis {
  font-size: 9pt;
  white-space: normal;
  overflow: hidden;
  text-overflow: ellipsis;
  min-height: 50px;
}


.text-ellipsis::before {
  content: "";
  display: block;
}

.f-title, .f-desc  {
	font-size: 10pt;
}

.f-desc .hr {
	margin: 5px 0px 5px;
	border-top: 1px dotted #666;
	padding: 0px 5px 0px;
	margin: 10px 5px 10px;
}


.cursor-pointer {
	cursor: pointer;
}

.card-body-minimum {
	min-height: 100px;
}   

.thumb-preview {
	display: inline;

}

/*Show Message Transition */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}

.success-message {
	display: block;
	align-items: center;
	justify-content: center;
	padding: 10px;
	background-color: #dff0d8;
	color: #3c763d;
	font-weight: bold;
	text-align: center;
}

.message {
	text-align: center;
	margin: auto;
	width: 100%;
	}

.check-mark {
  margin-right: 5px;
}


</style>


<style>

#modalLessonSelection fieldset {
  border: 1px solid #0074d961 !important;
  border-radius: 5px;
}

#modalLessonSelection fieldset legend {
    color: #0070d6ab !important;
}


#modalLessonSelection .custom-tooltip .tooltip-inner {
  min-width: 420px;
  padding-bottom: 15px;
}

#modalLessonSelection .modal-header .modal-title {
	font-size: 15px;
    text-align: center;
    width: 100%;
}

#modalLessonSelection .image .img-thumbnail {
	width:300px;
}

.hidden_header {
  display: none;
}

</style>