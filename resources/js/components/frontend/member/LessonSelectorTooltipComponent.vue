<template>
	<div id="tooltop-wrapper">
		<b-tooltip :target="target + '-' + category.id" 
		custom-class="custom-tooltip" placement="bottom" :variant="'dark'">
			<div id="tooltip-info-container" class="text-left">
				<div class="f-title">
					<span class="font-weight-bold">Title:</span>
					<div class="">{{ category.formatted_folder_name }}</div>
				</div>
				
				<div class="f-desc" v-if="category.folder_description !== null">
					<div class="hr"></div>
					<span class="font-weight-bold">Description:</span>
					<div>
						<span class="" v-if="category.folder_description !== null">{{ category.folder_description }}</span>
						<span class="" v-else>~ no folder description ~</span>
					</div>
				</div>

				<div class="f-preview" v-if="category.isThumbExist == true">
					<div class="hr"></div>
					<span class="font-weight-bold">Preview:</span>
					<div>
						<img :src="getBaseURL(category.thumb_path)" v-if="category.isThumbExist == true" class="thumb-image img-fluid" />  
					</div>
				</div>
				
				<div class="f-options text-center mt-2">
					<b-button variant="primary" @click="selectFolder(category.id)" size="sm" v-if="category.subcategoryCounter == 0">
						<span class="small">Select Folder</span>
					</b-button>
					<b-button variant="success" @click="viewFolder(category)" size="sm" v-else>
						<span class="small">View Folder</span>
					</b-button>
				</div>
				
			</div>
		</b-tooltip>
	</div>
</template>

<script>
export default {
	name: 'LessonSelectorTooltipComponent',
	props: {
		target: String,
		csrf_token: String,
		api_token: String,
		category: Object,
	},	
	data() {
		return {
			message: 'Hello, Vue!'
		};
	},
	methods: {
    	viewFolder(category) {
      		this.$emit('child-view-folder', category);
    	},	
    	selectFolder(category) {
      		this.$emit('child-select-folder', category);
    	},
		getBaseURL(path) {
			return window.location.origin + "/" + path;
		},
	}
};
</script>

<style lang="scss" scoped>

</style>>