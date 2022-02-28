<template>
  <div class="row g-2">
    <div class="col-auto">
      <label>
        {{ label }}
        <input type="text" :name="label" v-model="model" />
      </label>
    </div>
  </div>
</template>

<script>
export default {
  name: "Field",
  props: {
    label: {
      type: String,
      required: true,
    },
    value: {
      type: String|Number,
      required: true,
    },
  },
  computed: {
    model: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit('input', this.label, value);
      },
    },
  },
}
</script>

<style scoped>

</style>