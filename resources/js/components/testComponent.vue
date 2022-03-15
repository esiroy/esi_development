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
        let words = string.split(" "); 
        for (let i = 0; i < words.length; i++) {
            words[i] = words[i][0].toUpperCase() + words[i].substr(1);
        }
        return words.join(" ");
    } 
  }
}
</script>

<template>
  <h1>{{ ucwords(message)  }}</h1>
</template>