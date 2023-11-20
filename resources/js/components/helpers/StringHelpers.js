export const StringHelpers = {
  str_capitalize: function(param) {
    return this.capitalizeFirstLetter(param)
  },
  capitalizeFirstLetter(string) {
    let newString = string.charAt(0).toUpperCase() + string.slice(1); 
    return newString.trim(); 
  },
  removeUnderscore(string) {
      let wordArray = string.split("_");
      let words = wordArray.join(" ");
      return words;
  }
};