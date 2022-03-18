<script>
export default {
  name: "testComponent",
  data() {
    return {
      message: 'this is the test of the words that gets upper case every thing!'
    }
  },
  methods: {
    ucwords(string) {
        return string.toLowerCase().replace(/(?<= )[^\s]|^./g, a=>a.toUpperCase())  
    },
   
    convertUnderscoreToSpaces() {

    },
    capitalizeFirstLetter(string) {
        let newString = string.charAt(0).toUpperCase() + string.slice(1); 
        return newString.trim(); 
    },
  }
}
</script>

<template>
  <div>
    <h1>1. {{ ucwords(message)  }}</h1>
   
  </div>
</template>