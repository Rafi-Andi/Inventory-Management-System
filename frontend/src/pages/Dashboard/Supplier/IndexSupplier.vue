<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';

const dataSupplier = ref(null)
const fetchingSupplier = async () => {
  const response = await axios.get('http://127.0.0.1:8000/api/suppliers', {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })

  dataSupplier.value = response.data.suppliers
}

onMounted(()=>{
    fetchingSupplier()
})
</script>
<template>
  <div class="container-fluid">
    <div class="page-header">
      <h1>Manage Suppliers</h1>
    </div>
    <hr />

    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Address</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in dataSupplier" :key="index">
          <td>{{ index+1 }}</td>
          <td>{{ item.name }}</td>
          <td>{{ item.phone }}</td>
          <td>{{ item.address }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
