<template>
  <div class="container">
    <div class="row">
      <div class="col-3">
        <div class="card">
          <div class="card-header">Standard Fields</div>
          <div class="card-body">
            <draggable
              class="dragArea list-group"
              :list="list1"
              :group="{ name: 'people', pull: 'clone', put: false }"
              :clone="cloneItem"
              @change="log"
            >
                <button
                type="button"
                class="btn btn-light my-1"
                v-for="element in list1"
                :key="element.id"
                >{{ element.name }}</button>
            </draggable>
          </div>
        </div>
      </div>

      <div class="col-8">
        <div class="card">
          <div class="card-header">Create Form</div>
          <div class="card-body">

                <draggable class="dragArea list-group" :list="list2" group="people" @change="log">

                 
                    <div class="py-1" v-for="element in list2" :key="element.id">

                        <div v-if="element.type === 'input' ">
                            <div class="card">
                                <div class="card-header">Input Text Field</div>
                                <div class="card-body"><input class="form-control" type="text" :name="element.name"></div>
                            </div>
                        </div>


                        <div v-else-if="element.type === 'select' ">

                           <div class="card">
                                <div class="card-header">Select</div>
                                <div class="card-body">

                                    <select class="form-control" :id="element.id">
                                        <option v-for="value in element.values" :key="value">
                                            {{ value }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>



                    </div>

                </draggable>

          </div>
        </div>


      </div>
    </div>

    <div class="row">
      <rawDisplayer class="col-6" :value="list1" title="List 1" />

      <rawDisplayer class="col-6" :value="list2" title="List 2" />
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";
let idGlobal = 8;
export default {
  name: "custom-clone",
  display: "Custom Clone",
  order: 3,

  data() {
    return {
      list1: [
        {   
            id: 1, 
            name: "Input Text Field", 
            type: "input", 
            inputType: 'text' 
        },
        {
            id: 2,
            name: 'select',
            type: 'select',
            label: 'Skills',
            model: 'skills',
            values: ['Javascript', 'VueJS', 'CSS3', 'HTML5']
        },
      ],
      list2: [ 
      ]
    };
  },
  methods: {
    log: function(evt) {
      window.console.log(evt);
    },
    cloneItem({ id, name, type, values}) {
      return {
        id: idGlobal++,
        name: `${name}`,
        type: `${type}`,
        values: values,
      };
    }
  }
};
</script>
<style scoped></style>