<script setup>
defineProps({
  'name': String
})
const model = defineModel()
</script>

<template>
  <input :name="name" v-model="model" class="form-control" v-bind="$attrs">
</template>

<style scoped>

</style>