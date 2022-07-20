export const URLHelpers = {

    async getURL(url, data) {

        let errorMessage = null;



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

            if (response.status == 200) {
                return {
                    'success': true,
                    'status': response.status,
                    'response': response
                }
            }

        } catch (err) {
            errorMessage = err
        } finally {
            return {
                'success': false,
                'error': errorMessage,
            }
        }


    }
};