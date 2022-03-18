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
<<<<<<< HEAD
        return string.toLowerCase().replace(/(?<= )[^\s]|^./g, a=>a.toUpperCase())  
    },
    FormatObjectKey(string) {
        let newString = string.charAt(0).toUpperCase() + string.slice(1);
        newString = newString.replace(/_/g, " ")

        //add space before big letters
        return newString.replace(/([A-Z])/g, ' $1').trim(); 
    },
    capitalizeFirstLetter(string) {
        let newString = string.charAt(0).toUpperCase() + string.slice(1);    
        newString = newString.replace(/_/g, " ")       
        return newString.trim(); 
    },    
=======
        let words = string.split(" "); 
        for (let i = 0; i < words.length; i++) {
            words[i] = words[i][0].toUpperCase() + words[i].substr(1);
        }
        return words.join(" ");
    } 
>>>>>>> 09042ab482730a12e15bb80626a93efd34ac259a
  }
}
</script>

<template>
  <div>
    <h1>1. {{ ucwords(message)  }}</h1>
    <h1>2. {{ FormatObjectKey(message)  }}</h1>
    <h1>3. {{ FormatObjectKey(message)  }}</h1>
  </div>
</template>