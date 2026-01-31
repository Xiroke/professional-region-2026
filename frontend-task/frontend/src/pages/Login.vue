<script setup>

import Input from "../components/Input.vue";
import {useRouter} from "vue-router";

const router = useRouter()
const handleSubmit = () => {
  router.push({name: 'Main'})
}
</script>

<template>
  <div class="container">
    <form @submit="handleSubmit">
      <h3>Авторизация</h3>
      <Input type="email" placeholder="Email" />
      <Input type="password" placeholder="Пароль" />
      <button class="btn btn-primary">Войти</button>
    </form>
    <span class="text-muted d-block text-center">Или <RouterLink :to="{name: 'Registration'}">Зарегистрироваться</RouterLink></span>
  </div>
</template>

<style scoped>

</style>