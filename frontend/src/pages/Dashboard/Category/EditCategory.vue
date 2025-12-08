<script setup>
import router from '@/router'
import axios from 'axios'
import { ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const id = route.params.id
const name = route.params.name
const formCategory = ref({
  name: name,
})

const isLoading = ref(false)

const handleEdit = async () => {
  try {
    isLoading.value = true
    const response = await axios.put(
      `http://127.0.0.1:8000/api/categories/${id}`,
      formCategory.value,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      },
    )
    alert('berhasil edit')
    formCategory.value.name = ''
    router.push({name: 'IndexCategory'})
  } catch (error) {
    console.log(error)
    alert(error.response.data.message)
  } finally{
    isLoading.value = false
  }
}
</script>

<template>
  <div class="container-fluid">
    <div class="page-header">
      <h1>Create Category</h1>
    </div>
    <hr />

    <form class="form-container" @submit.prevent="handleEdit()">
      <div class="mb-3">
        <label for="name" class="form-label">Category Name</label>
        <input
          type="text"
          v-model="formCategory.name"
          class="form-control"
          id="name"
          placeholder="Enter category name"
        />
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">{{ isLoading ? "Loading.." : "Edit" }}</button>
        <router-link :to="{ name: 'IndexCategory' }" class="btn btn-outline-secondary"
          >Cancel</router-link
        >
      </div>
    </form>
  </div>
</template>

<style scoped>
body {
  background-color: #fff;
}

.container-fluid {
  padding: 30px 40px;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.page-header h1 {
  font-size: 24px;
  font-weight: 600;
  margin: 0;
}

hr {
  border-color: #dee2e6;
  margin: 20px 0 30px 0;
}

.table {
  border: 1px solid #dee2e6;
}

.table thead th {
  background-color: #f8f9fa;
  border-bottom: 1px solid #dee2e6;
  font-weight: 500;
  padding: 12px 15px;
}

.table tbody td {
  padding: 12px 15px;
  vertical-align: middle;
}

.table tbody tr:hover {
  background-color: #f8f9fa;
}

.btn-warning {
  color: #fff;
  background-color: #ffc107;
  border: none;
}

.btn-warning:hover {
  color: #fff;
  background-color: #e0a800;
}

.btn-danger {
  border: none;
}

.btn-sm {
  padding: 5px 15px;
  font-size: 13px;
}

/* Form Styles */
.form-container {
  max-width: 100%;
  margin-top: 30px;
}

.form-label {
  font-weight: 500;
  margin-bottom: 6px;
}

.form-control {
  padding: 10px 12px;
  border: 1px solid #dee2e6;
  border-radius: 4px;
}

.form-control:focus {
  border-color: #0d6efd;
  box-shadow: none;
}

.form-control[readonly] {
  background-color: #f8f9fa;
}

.form-actions {
  margin-top: 30px;
}

.form-actions .btn {
  padding: 8px 20px;
}

.form-actions .btn-outline-secondary {
  margin-left: 10px;
}
</style>
