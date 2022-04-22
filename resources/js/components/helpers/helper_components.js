    Vue.component('button-counter', {
        data: function () {
            return {
                count: 1
            }
        },
        template: '<button v-on:click="count++"> Next {{ count }} </button>'
    });


    Vue.component('question-text', {
        data: function () {
            return {
                count: 1
            }
        },
        template: '<div> question </div>'
    });