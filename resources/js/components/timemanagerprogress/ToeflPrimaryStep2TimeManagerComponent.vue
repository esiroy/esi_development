<template>
  <div id="timeManager-TOEFL_Primary_Step_2" class="d-none">
    <div class="message"></div>

    <form name="form-timemanager-TOEFL_Primary_Step_2" id="form-timemanager-TOEFL_Primary_Step_2">
    
      <div class="row pt-2">
        <div :class="this.size.leftColumn">
          <div class="pl-2 small">
            <span class="text-danger">&nbsp;</span> Course
          </div>
        </div>
        <div :class="this.size.rightColumn">
          <input
            type="text"
            id="course"
            :value="this.content.courseTextValue"
            disabled
            name="course"
            :class="this.size.select + ' form-control form-control-sm '"
          />
        </div>
      </div>

      <div class="row pt-2">
        <div :class="this.size.leftColumn">
          <div class="pl-2 small">
            <span class="text-danger">&nbsp;</span> Date
          </div>
        </div>
        <div :class="this.size.rightColumn">
          <datepicker
            id="startDate"
            name="startDate"
            v-model="date"
            :value="date"
            :format="dateFormatter"
            :placeholder="'Select Date'"
            :input-class="[
              this.size.select + ' form-control form-control-sm bg-white',
              { 'is-invalid': submitted && $v.date.$error },
            ]"
            :disabledDates="disabledDates"
            :language="ja"
          ></datepicker>

          <div v-if="submitted && !$v.date.required" class="invalid-feedback">
            date required
          </div>
        </div>
      </div>

      <div class="minutes-entry row pt-2">
        <div :class="this.size.leftColumn">
          <div class="pl-2 small">
            <span class="text-danger">*</span> Listening
          </div>
        </div>
        <div :class="this.size.rightColumn">
          <input
            v-on:keyup="getTotalMinutes"
            id="listening"
            name="listening"
            v-model="data.listening"
            :class="this.size.select + ' form-control form-control-sm'"
          />
        </div>
      </div>

      <div class="minutes-entry row pt-2">
        <div :class="this.size.leftColumn">
          <div class="pl-2 small">
            <span class="text-danger">*</span> Reading
          </div>
        </div>
        <div :class="this.size.rightColumn">
          <input
            v-on:keyup="getTotalMinutes"
            id="writing"
            name="writing"
            v-model="data.reading"
            :class="this.size.select + ' form-control form-control-sm'"
          />
        </div>
      </div>

      <div class="row pt-2">
        <div :class="this.size.leftColumn">
          <div class="pl-2 small"><span class="text-danger">*</span> Total</div>
        </div>
        <div :class="this.size.rightColumn">
          <input
            type="text"
            id="total"
            disabled
            name="total"
            v-model="data.total"
            :class="this.size.select + ' form-control form-control-sm '"
          />
        </div>
      </div>
    </form>
  </div>
</template>


<script>
import * as Moment from "moment";
import Datepicker from "vuejs-datepicker";
import { en, ja } from "vuejs-datepicker/dist/locale";

export default {
  name: "TOEFL_Primary_Step_2ScoreComponent",
  data() {
    return {
      ja: ja,
      en: en,
      submitted: false,
      date: "",
      disabledDates: {
        from: new Date(Date.now() + 8640000)
      },
      data: {
        reading: "",
        listening: "",
        total: "",
      },
    };
  },
  components: {
    Datepicker,
  },
  props: {
    size: Object,
    content: Object,
  },
  methods: {
    getDate() {
      return this.date;
    },
    getMinutesData() {
      return this.data;
    },
    dateFormatter(date) {
      let fdate = Moment(date).format("YYYY年 MM月 D日");
      return fdate;
    },
    getTotalMinutes() {
      this.data.total = this.$parent.$options.methods.getTotalMinutes(
        this.content.course
      );
    },
  },
  computed: {},
  updated: function () {},
  mounted: function () {
    // console.log("TOEFL_Primary_Step_2 mounted")
  },
};
</script>


