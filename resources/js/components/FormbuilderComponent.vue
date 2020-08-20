<template>
<div class="row">

    <div class="col-3">
        <div class="card">
            <div class="card-header">Standard Fields</div>
            <div class="card-body">
                <draggable class="dragArea list-group" :list="list1" :group="{ name: 'people', pull: 'clone', put: false }" :clone="cloneItem" @change="log">
                    <button type="button" class="btn btn-light my-1" v-for="element in list1" :key="element.id">{{ element.label }}</button>
                </draggable>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card">
            <div class="card-header">Create Form</div>
            <div class="card-body">
                <draggable class="dragArea list-group" :list="list2" group="people" @change="log">
                    <div class="py-1" v-for="element in list2" :key="element.id">

                        <div v-if="element.type === 'input' ">

                            <div class="card">
                                <div class="card-header">
									Input Text Field
									<div class="open">
									</div>
								</div>
                                <div class="card-body">
									<input class="form-control" type="text" :name="element.name">
								</div>
                            </div>
                        </div>

                        <div v-else-if="element.type === 'select' ">
                            <div class="card">
                                <div class="card-header">Select</div>
                                <div class="card-body">

                                    <select class="form-control" :id="element.id">
                                        <option v-for="value in element.values" :key="value" :value="value">{{ value }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div v-else-if="element.type === 'radio' ">
                            <div class="card">
                                <div class="card-header">Select</div>
                                <div class="card-body">

                                    <form action="/action_page.php">
                                        <input type="radio" id="male" name="gender" value="male"> <label for="male"> Male </label><br>
                                        <input type="radio" id="female" name="gender" value="female"> <label for="female"> Female </label><br>
                                        <input type="radio" id="other" name="gender" value="other"> <label for="other"> Other </label><br><br>
                                        <!--<input type="submit" value="Submit">-->
                                    </form>

                                </div>
                            </div>
                        </div>

                    </div>
                </draggable>
            </div>
        </div>
    </div>

    <div class="col-3">
        <div class="card">
            <div class="card-header">Options</div>
            <div class="card-body">
                <button class="btn btn-primary">
                    Save
                </button>
            </div>
        </div>
    </div>

    <!--Display Info-->
    <div class="row">
        <rawDisplayer class="col-6" :value="list1" title="Fields" />
        <rawDisplayer class="col-6" :value="list2" title="Form Display Json" />
    </div>
    <!--[End Display Info]-->

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
            list1: [{
                    id: 1,
                    name: "Input Text Field",
                    type: "input",
                    label: "Input",
                    inputType: 'text'
                },
                {
                    id: 2,
                    name: 'select',
                    type: 'select',
                    label: 'Select',
                    model: 'skills',
                    values: ['Javascript', 'VueJS', 'CSS3', 'HTML5']
                },
                {
                    id: 3,
                    name: 'radio',
                    type: 'radio',
                    label: 'Radio',
                    model: 'radio',
                    values: ['Option 1', 'Option 2']
                },
            ],
            list2: []
        };
    },
    methods: {
        log: function (evt) {
            window.console.log(evt);
        },
        cloneItem({
            id,
            name,
            type,
            values
        }) {
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
