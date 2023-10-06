<template>
  <div>
    
    <b-modal id="tutor-disconnected-modal" content-class="esi-modal" title="Member Disconnected">
  
      {{ countdown }}

    </b-modal>



  </div>
</template>

<script>
export default {
  data() {
    return {
      showModal: false,
      countdown: 15, // Initial countdown time in seconds
      timer: null,
    };
  },
  computed: {
    formattedTime() {
      const minutes = Math.floor(this.countdown / 60);
      const seconds = this.countdown % 60;
      return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    },
  },
  methods: {
    openModal() {
      this.showModal = true;
      this.$bvModal.show('tutor-disconnected-modal');

      console.log(this.timer);
      
      this.startCountdown();
    },
    closeModal() {
      this.showModal = false;
      this.$bvModal.show('tutor-disconnected-modal');
      this.resetCountdown();
    },
    startCountdown() {
      this.timer = setInterval(() => {
        if (this.countdown > 0) {
          this.countdown--;
        } else {
          this.showModal = false;
          clearInterval(this.timer);
        }
      }, 1000);
    },
    resetCountdown() {
      clearInterval(this.timer);
      this.countdown = 15; // Reset countdown to initial value
    },
  },
};
</script>

<style scoped>
/* Styling for the modal */
.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1;
}

.modal-content {
  background-color: #fff;
  text-align: center;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  padding: 20px;
  border-radius: 5px;
}

.close {
  position: absolute;
  top: 10px;
  right: 10px;
  cursor: pointer;
}

.timer {
  font-size: 24px;
  margin: 20px 0;
}
</style>