<template>
  <div class="container data-manager">
    <div class="row g-2 form">
      <div class="col-auto">
        <Field v-for="(value,label, index) in fields" :key="`${label}-${index}`" :label="label" :value="value"
               v-on:input="onUpdateFieldValue"></Field>
        <button class="btn btn-primary" v-on:click="submit" :disabled="lockForm">Push</button>
      </div>
      <div class="col-auto">
        <div class="row g-2">
          <div class="col-auto">
            <label>
              Name:
              <input type="text" v-model="newFieldLabel"/>
            </label>
          </div>
        </div>
        <div class="row g-2">
          <div class="col-auto">
            <label>
              Init Value:
              <input type="text" v-model="newFieldValue"/>
            </label>
          </div>
        </div>
        <button class="btn btn-danger" v-on:click="addNewField" v-show="!!newFieldLabel && !!newFieldValue">Add new
          Field
        </button>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4">
        <strong>Datetime</strong>
      </div>
      <div class="col-sm-4">
        <strong>Push</strong>
      </div>
      <div class="col-sm-4">
        <strong>Result</strong>
      </div>
    </div>
    <div class="row data" v-for="item in results" :key="item.id">
      <div class="col-sm-4">
        {{ item.created_at.date }}
      </div>
      <div class="col-sm-4">
        <ul>
          <li class="row data" v-for="(value, field) in item.old_data"><strong>{{ field }}:</strong>
            {{ value }}
          </li>
        </ul>
      </div>
      <div class="col-sm-4">
        <ul>
          <!-- todo: component DRY -->
          <li class="row data" v-for="(value, field) in item.new_data"><strong>{{ field }}:</strong>
            {{ value }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import Field from "./Field";

export default {
  components: {Field},
  data() {
    return {
      lockForm: false,
      results: [],
      fields: { // sample fields
        "GlobalCaseId": "HHYUP-28-7IDKFA",
        "PowerLines": "280YO-I",
        "Voltage": 385.2,
        "Power": 199344
      },
      newFieldLabel: '',
      newFieldValue: ''
    };
  },
  async mounted() {
    const response = await fetch("/api/")
    if (response.status >= 200 && response.status <= 299) {
      const data = await response.json();
      this.setupFields(data[0].new_data)
      this.results = data
    } else {
      console.log(response.status, response.statusText);
    }
  },
  methods: {
    setupFields(data) {
      Object.entries(data).forEach(([key, value]) => this.fields[key] = value);
    },
    addNewField() {
      this.onUpdateFieldValue(this.newFieldLabel, this.newFieldValue)
      this.newFieldLabel = this.newFieldValue = ''
    },
    onUpdateFieldValue(label, value) {
      this.fields[label] = isNaN(value) ? value : +value;
    },
    submit() {
      this.lockForm = true
      this.pushData(this.fields).then((data) => {
        if (data) {
          this.results = [data].concat(this.results)
          this.setupFields(data.new_data)
        }
        this.lockForm = false;
      });
    },
    pushData: async (data) => {
      const response = await fetch("/api/", {
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        method: "POST",
        credentials: 'include',
        body: JSON.stringify(data)
      })
      if (response.status >= 200 && response.status <= 299) {
        return response.json();
      } else {
        console.log(response.status, response.statusText)
      }
    }
  },
};
</script>

<style>

</style>