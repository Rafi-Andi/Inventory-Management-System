<script setup>
import axios from 'axios'
import { onMounted, ref } from 'vue'
const formItem = ref({
  name: '',
  category_id: '',
  description: ''
})

const dataCategory = ref(null)
const fetchingCategory = async () => {
  const response = await axios.get('http://127.0.0.1:8000/api/categories', {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })

  dataCategory.value = response.data.data
}

onMounted(() => {
  fetchingCategory()
})
const isLoading = ref(false)

const handleItem = async () => {
  try {
    isLoading.value = true
    const response = await axios.post('http://127.0.0.1:8000/api/items', formItem.value, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    })
    alert('berhasil tambah')
    console.log(response)
  } catch (error) {
    console.log(error)
    alert('error')
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="container-fluid">
    <div class="page-header">
      <h1>Create Item</h1>
    </div>
    <hr />

    <form class="form-container" @submit.prevent="handleItem()">
      <div class="mb-3">
        <label for="name" class="form-label">Item Name</label>
        <input v-model="formItem.name" type="text" class="form-control" id="name" placeholder="Enter item name" />
      </div>

      <div class="mb-3">
        <label for="Item" class="form-label">Item</label>
        <select v-model="formItem.category_id" class="form-select" id="Item">
          <option value="" selected disabled>Selected Category</option>
          <option v-for="(item, index) in dataCategory" :key="index" :value="item?.id" selected>{{ item?.name }}</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea
        v-model="formItem.description"
          class="form-control"
          id="description"
          rows="3"
          placeholder="Enter item description"
        ></textarea>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="index.html" class="btn btn-outline-secondary">Cancel</a>
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

.form-control,
.form-select {
  padding: 10px 12px;
  border: 1px solid #dee2e6;
  border-radius: 4px;
}

.form-control:focus,
.form-select:focus {
  border-color: #0d6efd;
  box-shadow: none;
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
