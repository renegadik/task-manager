<template>
    <div class="p-8 max-w-md mx-auto">
      <h1 class="text-2xl font-bold mb-4">Вход</h1>
      <form @submit.prevent="login">
        <input v-model="email" type="email" placeholder="Email" class="input" />
        <input v-model="password" type="password" placeholder="Пароль" class="input mt-2" />
        <button type="submit" class="btn mt-4">Войти</button>
        <p v-if="error" class="text-red-600 mt-2">{{ error }}</p>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import api from '../api'
  import { useUserStore } from '../stores/user'
  import { useRouter } from 'vue-router'
  
  const email = ref('')
  const password = ref('')
  const error = ref('')
  const router = useRouter()
  const userStore = useUserStore()
  
  const login = async () => {
    error.value = ''
    try {
      await api.get('/sanctum/csrf-cookie') // получаем XSRF куку
      await api.post('/login', { email: email.value, password: password.value })
      const res = await api.get('/user')
      userStore.setUser(res.data)
      router.push('/') // редирект после входа
    } catch (err) {
      error.value = 'Неверные данные'
      console.error(err)
    }
  }
  </script>
  
  <style scoped>
  .input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
  }
  .btn {
    background: #111;
    color: white;
    padding: 10px;
    border-radius: 6px;
  }
  </style>
  