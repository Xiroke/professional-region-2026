<script setup>
import {inject} from "vue";

defineProps({
  name: String
})

const errors = inject('errors')
</script>

<template>
<div class="invalid-feedback">
  {{errors?.[name]?.[0]}}
</div>
</template>

<style scoped>

</style>