<script setup>
import { ref } from 'vue'
import axios from 'axios'
import router from '@/router'
const formLogin = ref({
  email: '',
  password: '',
})

const isLoading = ref(false)

const handleLogin = async () => {
  try {
    isLoading.value = true
    const response = await axios.post('http://127.0.0.1:8000/api/auth/login', formLogin.value)
    alert('berhasil login')
    console.log(response)
    const role = response.data.data.user.role
    localStorage.setItem('role', role)
    localStorage.setItem('token', response.data.data.token)
    if(role === 'admin' || role === 'manager'){
      router.push({name: 'IndexPurchase'})
    }

    router.push({name: 'IndexCategory'})
  } catch (error) {
    console.log(error)
    alert('error')
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="container">
    <div class="login-card">
      <h1>Welcome Back</h1>
      <p class="subtitle">Login to your Inventory System account</p>

      <form @submit.prevent="handleLogin()">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input v-model="formLogin.email" type="email" class="form-control" id="email" placeholder="Enter your email" />
        </div>

        <div class="mb-4">
          <label for="password" class="form-label">Password</label>
          <input
          v-model="formLogin.password"
            type="password"
            class="form-control"
            id="password"
            placeholder="Enter your password"
          />
        </div>

        <button type="submit" class="btn btn-primary btn-login">Login</button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.container {
  background-color: #f5f5f5;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.login-card {
  background: #fff;
  border-radius: 8px;
  padding: 40px;
  width: 100%;
  max-width: 420px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.login-card h1 {
  font-size: 28px;
  font-weight: 600;
  margin-bottom: 8px;
}

.login-card .subtitle {
  color: #6c757d;
  margin-bottom: 30px;
}

.form-label {
  font-weight: 500;
  margin-bottom: 6px;
}

.form-control {
  padding: 12px;
  border: 1px solid #dee2e6;
  border-radius: 6px;
}

.form-control:focus {
  border-color: #0d6efd;
  box-shadow: none;
}

.btn-login {
  width: 100%;
  padding: 12px;
  background-color: #0d6efd;
  border: none;
  border-radius: 6px;
  font-weight: 500;
}

.btn-login:hover {
  background-color: #0b5ed7;
}

.register-link {
  text-align: center;
  margin-top: 20px;
}

.register-link a {
  color: #0d6efd;
  text-decoration: none;
}

.register-link a:hover {
  text-decoration: underline;
}
</style>
