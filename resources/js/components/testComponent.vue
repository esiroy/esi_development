<script>
export default {
  name: "testComponent",
  data() {
    return {
      message: 'this is the test of the words that gets upper case every thing!'
    }
  },
  methods: {
  
    /*
    ucwords(string) {
        return string.toLowerCase().replace(/(?<= )[^\s]|^./g, a=>a.toUpperCase())  
    },
    FormatObjectKey(string) {
        let newString = string.charAt(0).toUpperCase() + string.slice(1);
        newString = newString.replace(/_/g, " ")

        //add space before big letters
        return newString.replace(/([A-Z])/g, ' $1').trim(); 
    },
    */
    convertUnderscoreToSpaces() {

    },
    capitalizeFirstLetter(string) {
        let newString = string.charAt(0).toUpperCase() + string.slice(1);  
        //newString = newString.replace(/_/g, " ")               
        return newString.trim(); 
    },
  }
}
</script>

<template>
  <div>
    <h1>1. {{ capitalizeFirstLetter(message)  }}</h1>
   
  </div>
</template>