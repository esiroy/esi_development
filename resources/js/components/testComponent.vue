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
      let wordArray = string.split(" ");

      for (var i = 0; i < wordArray.length; i++) {
        wordArray[i] = wordArray[i].charAt(0).toUpperCase() + wordArray[i].slice(1);
      }

      let words = wordArray.join(" ");

      return words;

    }
  }
}
</script>

<template>
  <div>
    <h1>1. {{ ucwords(message)  }}</h1>
   
  </div>
</template>