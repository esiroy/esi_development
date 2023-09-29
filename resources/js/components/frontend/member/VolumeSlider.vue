<template>


    <div class="wrapper">

        <input id="volume" v-model="value"  @input="$emit('input', value)" type="range" min="1" max="100" step="1" />
        <label class="visually-hidden" for="volume">Volume</label>
    </div>

</template>

<script>
export default {
  data() {
    return {
        value: Number, // For two-way data binding
    }    
  },
};
</script>

<style scoped>

.wrapper {
  position: relative;
  margin: 1rem;
}

input[type=range] {
  -webkit-appearance: none; 
  max-width: 7rem;
  width: 100%; 
  background: transparent; 
}

input[type=range]:focus {
  outline: none;
}

input[type=range]::-webkit-slider-runnable-track {
  height: 0.9rem;
  margin: 0;
  width: 100%;
  cursor: pointer;
  background: #999;
  background: linear-gradient(
    to bottom right, 
    transparent 50%,
    #999 50%
  );
}

input[type=range]::-moz-range-track {
  height: 0.9rem;
  margin: 0;
  width: 100%;
  cursor: pointer;
  background: #999;
  background: linear-gradient(
    to bottom right, 
    transparent 50%,
    #999 50%
  );
}

input[type=range]::-ms-track {
  height: 0.9rem;
  margin: 0;
  width: 100%;
  cursor: pointer;
  color: transparent;
  background: #999;
  border: 0;
  background: linear-gradient(
    to bottom right, 
    transparent 50%,
    #999 50%
  );
}

input[type=range]::-ms-fill-lower {
 background: transparent;
}

input[type=range]::-webkit-slider-thumb {
  -webkit-appearance: none;
  height: 1.5rem;
  width: 0.5rem;
  background: #ffffff;
  border: 1px solid;
  margin-top: -5px;
  border-radius: 3px;
  cursor: pointer;
}

input[type=range]:focus::-webkit-slider-thumb {
  box-shadow: 0px 0px 7px 3px #0065c4;
}

input[type=range]::-moz-range-thumb {
  -webkit-appearance: none;
  height: 1.5rem;
  width: 0.5rem;
  background: #ffffff;
  border: 1px solid;
  border-radius: 3px; 
  cursor: pointer;
  margin-top: 0;
}

input[type=range]:focus::-moz-range-thumb {
  box-shadow: 0px 0px 7px 3px #0065c4;
}

input[type=range]::-ms-thumb {
  -webkit-appearance: none;
  height: 0.75rem;
  width: 0.5rem;
  background: #ffffff;
  border: 1px solid;
  margin-top: 0;
  border-radius: 3px;
  cursor: pointer;
}

input[type=range]:focus::-ms-thumb {
  box-shadow: 0px 0px 7px 3px #0065c4;
}

.visually-hidden { 
    position: absolute !important;
    height: 1px; 
    width: 1px;
    overflow: hidden;
    clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
    clip: rect(1px, 1px, 1px, 1px);
    white-space: nowrap; /* added line */
}
</style>