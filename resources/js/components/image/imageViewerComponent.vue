<template>
    <div>
        <!-- Vue Bootstrap Modal for Image Preview -->
        <b-modal v-model="isModalOpen" size="xl" title="Image Preview" centered  hide-footer > 
            
            <!--
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Image Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>    
            
            <div class="custom-btn-close text-right">
                <b-icon icon="x-circle-fill" font-scale="1.75" @click="closeModal" aria-hidden="true"></b-icon>
            </div>
            -->
            <div class="modal-body text-center">
                <img :src="modalImageUrl" alt="Image Preview" class="img-fluid" />
            </div>

        </b-modal>

    </div>
</template>


<script>
export default {
    data() {
        return {
            isModalOpen: false,
            modalImageUrl: '',
        };
    },
    methods: {
        openModal(url) {
            this.modalImageUrl = url; // Set the image URL
            this.isModalOpen = true; // Open the modal
        },
        closeModal() {
            this.isModalOpen = false; // Close the modal
            this.modalImageUrl = ''; // Clear the image URL
        },

    }
};
</script>

  
<style scoped>
/* Add your modal CSS styles here */

.custom-btn-close {
    position: absolute;
    top: -5px;
    right: -8px;
    color: #0074bc;
    cursor: pointer;
}

.modalImageViewer {
    /* Style for the modalImageViewer background */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999;
}

.modalImageViewer-content {
    /* Style for the modalImageViewer content container */
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    position: relative;
    /* Relative positioning for absolute button */
    text-align: center;
    /* Center the content */
}

.modalImageViewer-close {
    /* Style for the close button */
    cursor: pointer;
    font-size: 32px;
    color: black;
    /* Make the close button black */
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 10px;
    /* Make the close button bigger */
    border-radius: 50%;
    /* Make it round */
}

.modalImageViewer img {
    /* Style for the displayed image */
    max-width: 100%;
    max-height: 80vh;
}


</style>