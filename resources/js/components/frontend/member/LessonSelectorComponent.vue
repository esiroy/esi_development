<template>

	<div id="lesson-selector">

		<b-modal id="modalLessonSelection" title="Select Lesson" header-bg-variant="primary" header-text-variant="white" size="xl" hide-footer>

			<template #modal-footer>
				<div class="footer-button-container">
					<b-button variant="primary" @click="hideLessonSelectionModal">Cancel</b-button>
				</div>
			</template>

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
			<div class="main-content" v-show="!isloadingCategories">		


				<!--[start] Breadcrumbs-->
				<div class="row">				
					<div class="col-12 mb-2">
						<div class="bg-light p-2">
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
													<div class="content" v-show="!isloadingImages">
														<div class="row" v-if="folder_images[row.index]">
															<div class="col-4" v-for="(images, imageIndex) in folder_images[row.index]" 
																	:key="'folder_images_'+row.index+'_'+imageIndex">
																<img class="img-fluid cursor-pointer" :src="getBaseURL(images.path)"  @click.prevent="imageViewer(getBaseURL(images.path))">   
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
										:limit="5"
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

					<div id="categories-list" class="row">			
						<div id="parent-categories" v-for="(category, index) in folderCategories" :key="index" 
							class="col-12 col-xs-6 col-sm-6 col-md-3 col-lg-2 cursor-pointer" >

							<div :id="'category-' + category.id" class="card text-white mb-2" v-b-tooltip.hover target="'#category-' + category.id" @click="viewFolder(category)">
								<div class="card-header bg-primary text-ellipsis">
									{{ category.formatted_folder_name }}
								</div>
								<div class="card-body m-0 p-0" v-if="folderType == 'parent'" >
									<p class="card-text min-height">
										<img :src="getBaseURL(category.thumb_path)" v-if="category.isThumbExist == true" class="thumb-image img-fluid" />            
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
								:category="category"
								:api_token="api_token" 
								:csrf_token="csrf_token"/>				
							<!--[end] b-tooltip-->
						</div>
					</div>

				</fieldset>			

				<!--[end] Category List -->
			</div>

		</b-modal>

	</div>
	
</template>

<script>

import LessonSelectorTooltipComponent from './LessonSelectorTooltipComponent.vue';

export default {
	name: 'LessonSelectorComponent',
	components: { 
		LessonSelectorTooltipComponent
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
			folderCategories: [],
			
			urlArray: [],
			folderType: 'parent',

			currentPage: null,
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
			
    	}
  },
  mounted() {
    window.lessonSelectorComponent = this;
    console.log("LessonSelectorComponent Loaded...");
  },
  methods: {
	
    showLessonSelectionModal() {
		/** this will be called from member /index */
		if (!this.currentSelectedCategory) {
			this.showAllFolder();			
		}
      	this.$bvModal.show("modalLessonSelection");      	
    },
	showAllFolder() {
		this.urlArray = [];	
		this.getLessonsList();
	},	
	jumpToFolder(category, index) {
		let limit = this.urlArray.length - index;
		for (let i = 1; i <= (limit); i++) {		
			this.urlArray.pop();
		}		
		this.viewFolder(category);
	},
    getBaseURL(path) {
      	return window.location.origin + "/" + path;
    },
	viewFolder(category) {
		this.urlArray.push(category);
		this.getLessonsList(category.id);
		this.currentSelectedCategory = category;
	},
	selectFolder(id) {
		this.$bvModal.hide("modalLessonSelection");
	},
	getLessonImages(index, folderID) {		

		this.isloadingImages = true;
		this.showCollapse(index);

		axios.post("/api/getLessonImages?api_token=" + this.api_token, 
		{
			method                  : "POST",
			folderID                : folderID,
		}).then(response => {

			if (response.data.success == true) {   
				this.isloadingImages = false;
				this.$set(this.folder_images, index, response.data.files)
				this.$forceUpdate();

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
				this.$forceUpdate();
			} else {
				alert(
					"Error, we can't get your list of lesson, please try again later"
				);
			}
        });
    },
  },
};


</script>

<style scoped>



.min-height {
	min-height: 100px
}

.text-ellipsis {
	font-size: 9pt;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
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