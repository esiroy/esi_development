<script>
export default {
  name: "testComponent",
  data() {
    return {
      message: 'Hello World!'
    }
  },
  methods: {
    ucwords(string) {
        return string.toLowerCase().replace(/(?<= )[^\s]|^./g, a=>a.toUpperCase())  
    }  
  }
}
</script>

<template>
  <h1>{{ ucwords(message)  }}</h1>
</template>