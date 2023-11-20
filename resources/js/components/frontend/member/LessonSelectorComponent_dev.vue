<template>

  <div :class="'selector'">

  	<b-modal id="modalLessonSelection" title="Select Lesson"  header-bg-variant="primary" header-text-variant="white" size="xl">



		<div class="image-gallery d-flex flex-wrap">

			<div class="image-wrapper" v-for="category in folderCategories" :key="'category-' + category.id">

				<img :src="getBaseURL(category.thumb_path)"  v-if="category.isThumbExist == true" class="constrained-image">

				<div class="button-wrapper">
					<button class="gallery-button">View Image</button>
				</div>

			</div>	
		</div>


		<!--

      	<div class="d-flex flex-wrap align-items-end">

			<div v-for="category in folderCategories" :key="'category-' + category.id">

				<div class="image" v-if="category.isThumbExist == true">
					<img :src="getBaseURL(category.thumb_path)" class="img-thumbnail" @load="handleImageLoad(category.thumb_path)">
				</div>

				<b-button
					:id="'category-' + category.id"
					class="card p-2 m-2 "
					variant="primary"
					v-b-tooltip.hover
					target="'#category-' + category.id">
					<div class="text-container">
						<div class="text-ellipsis">
						{{category.folder_name}}
						</div>
					</div>
				</b-button>

				<b-tooltip :target="'category-' + category.id" custom-class="custom-tooltip" placement="bottom" :variant="'dark'">
					<div class="text-left">
						<div class="f-title">
							<span class="font-weight-bold">Title:</span>
							<div class="">{{ category.folder_name }}</div>
							</div>
							<div class="f-desc" v-if="category.folder_description !== null">
							<div class="hr"></div>
							<span class=" font-weight-bold">Description:</span>
							<div>
								<span class="" v-if="category.folder_description !== null">{{ category.folder_description }}</span>
								<span class="" v-else>~ no folder description ~</span>
							</div>
						</div>
					</div>
				</b-tooltip>
			</div>
      	</div>
		-->
		
    </b-modal>



  </div>
</template>

<script>
export default {
  name: 'LessonSelectorComponent',
  props: 
  {
      csrf_token: String,		
      api_token: String,
  },  
  data() {
    return {
      // Data properties go here
	  showFullText: false,
	  folderCategories: null,
    };
  },
  methods: { 
	handleImageLoad(imageSrc) {
		console.log('Image exists:', imageSrc);
		// You can perform additional actions here if needed
	},
	getBaseURL(path) {
		return window.location.origin + "/" +path
	},   
    showLessonSelectionModal() {      
      this.$bvModal.show('modalLessonSelection');  
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
  },
  computed: {
    // Computed properties go here
  },
  mounted() {

    window.lessonSelectorComponent = this;

    console.log("LessonSelectorComponent Loaded...")
  
  },
  // Other component lifecycle hooks go here
};
</script>

<style scoped>
.card {
  width: 120px;
  display: flex;
  flex-direction: row;
  background-color: #0074d9ce;
  border: 1px solid #0059a6b1;
  color: #fff;
  border-radius: 4px;
  padding: 16px;
  box-sizing: border-box;
}

.text-container {
  position: relative;
  max-width: 100px; /* Adjust the width as needed */
}

.hover-container {
  transition: background-color 0.3s ease;
}

.hover-container:hover {
  background-color: #0060b3;
  color: #fff
}

.text-ellipsis {
	font-size: 9pt;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.clickable {
  cursor: pointer;
}

.f-title {
	font-size: 10pt;
}

.f-desc  {
	font-size: 10pt;
}

.f-desc .hr {
	margin: 5px 0px 5px;
	border-top: 1px solid #ccc;
	padding: 0px 5px 0px;
}
</style>

<style>
#modalLessonSelection .custom-tooltip .tooltip-inner {
  min-width: 360px;
}

#modalLessonSelection.modal-header .modal-title {
	font-size: 15px;
    text-align: center;
    width: 100%;
}

#modalLessonSelection .image .img-thumbnail {
	width:300px;
}



.image-wrapper {
  width: 150px; /* Adjust the width as needed */
  height: 150px; /* Adjust the height as needed */
  overflow: hidden;
}

.constrained-image {
  max-width: 100%;
  max-height: 100%;
}
</style>