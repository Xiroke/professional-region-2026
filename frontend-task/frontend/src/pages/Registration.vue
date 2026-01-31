<script setup>

import Input from "../components/Input.vue";
import {useRouter} from "vue-router";

const router = useRouter()

const handleSubmit = () => {
  router.push({name: 'Login'})
}

</script>

<template>
  <div class="container">
    <form @submit="handleSubmit">
      <h3>Регистрация</h3>
      <Input type="email" placeholder="Email" />
      <Input type="text" placeholder="Имя" />
      <Input type="password" placeholder="Пароль" />
      <button class="btn btn-primary">Зарегистрироваться</button>
    </form>
    <span class="text-muted d-block text-center">Или <RouterLink :to="{name: 'Login'}">Войти</RouterLink></span>
  </div>
</template>

<style scoped>

</style>