<script setup>
import axios from 'axios'
import { onMounted, ref } from 'vue'

const role = localStorage.getItem('role')

const dataCategory = ref(null)
const fetchingCategory = async () => {
  const response = await axios.get('http://127.0.0.1:8000/api/categories', {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })
  dataCategory.value = response.data.data

  console.log(dataCategory.value)
}

onMounted(() => {
  fetchingCategory()
})

const handleEdit = async (id) => {
  try {
    const newName = prompt('masukkan nama baru')
    console.log(newName)
    const response = await axios.put(
      `http://127.0.0.1:8000/api/categories/${id}`,
      {
        name: newName,
      },
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      },
    )
    const index = dataCategory.value.findIndex((category) => (category.id = id))

    dataCategory.value[index].name = newName
    alert('berhasil edit')
  } catch (error) {
    console.log(error)
    alert('gagal edit')
  }
}

const handleDelete = async (id) => {
  try {
    const response = await axios.delete(
      `http://127.0.0.1:8000/api/categories/${id}`,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      },
    )
    const index = dataCategory.value.findIndex(category => (category.id === id))
    
    console.log(index)
    dataCategory.value.splice(index, 1)
    alert('berhasil hapus')

  } catch (error) {
    console.log(error)
    alert('gagal hapus')
  }
}
</script>

<template>
  <div class="container-fluid">
    <div class="page-header">
      <h1>Manage Categories</h1>
      <router-link :to="{name: 'CreateCategory'}" class="btn btn-primary" router v-if="role === 'admin'">Create Category</router-link>
    </div>
    <hr />

    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>Name</th>
          <th>Items Count</th>
          <th v-if="role === 'admin'">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in dataCategory" :key="index">
          <td>{{ index + 1 }}</td>
          <td>{{ item?.name }}</td>
          <td>{{ item?.items_count }}</td>
          <td v-if="role === 'admin'">
            <router-link :to="{name: 'EditCategory', params: {id: item.id, name: item.name}}" href="#" class="btn btn-warning btn-sm">Edit</router-link>
            <a href="#" class="btn btn-danger btn-sm" @click="handleDelete(item.id)">Delete</a>
          </td>
        </tr>
      </tbody>
    </table>
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
