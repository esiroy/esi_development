<script>
export default {
  name: "testComponent",
  data() {
    return {
      message: 'this is the test of the words that gets upper case every thing!',
      u_message: 'this_is_the_test_of_the_words_that_gets_upper_case_every thing!',
      objectKeys: "ThisIsAKeyThatIsMergedTogetherAndNeedsToBeSplitted"
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
    },
    removeUnderscore(string) {
      let wordArray = string.split("_");
      let words = wordArray.join(" ");
      return words;
    },    
    capitalizeFirstLetter(string) {
        let words = this.removeUnderscore(string);
        let newString = words.charAt(0).toUpperCase() + words.slice(1); 
        return newString.trim(); 
    },   
    FormatObjectKey(string) {
      let wordArray = string.split(/(?=[A-Z])/);
      let words = wordArray.join(" ");
      return words;
    },
  }
}
</script>

<template>
  <div>
    <h1>1. {{ ucwords(message)  }}</h1>
    <h1>2. {{ capitalizeFirstLetter(u_message)  }}</h1>
    <h1>2. {{ FormatObjectKey(objectKeys)  }}</h1>
   
  </div>
</template>