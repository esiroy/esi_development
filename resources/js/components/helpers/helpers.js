export const Helpers = {
  str_capitalize: function(param) {
    return this.capitalizeFirstLetter(param)
  },
  capitalizeFirstLetter(string) {
    let words = this.removeUnderscore(string);
    let newString = words.charAt(0).toUpperCase() + words.slice(1); 
    return newString.trim(); 
  },
  removeUnderscore(string) {
      let wordArray = string.split("_");
      let words = wordArray.join(" ");
      return words;
  },
  async getURL(url, data) {

     try {
       let response = await axios({
            'url': url,
            'method': 'POST',
            'timeout': 8000,
            'headers': {
                'Content-Type': 'application/json',
            },
			data
        })

        if(response.status == 200){
            // test for status you want, etc
            console.log(response.status)
			
        }     
        // Don't forget to return something   
		return response
        
    }
    catch (err) {
        console.log(err);
    }


  }
};