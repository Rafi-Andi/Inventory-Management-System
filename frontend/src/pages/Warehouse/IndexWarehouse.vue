<script setup>
import router from '@/router'
import axios from 'axios'
import { onMounted, ref } from 'vue'

const role = localStorage.getItem('role')
const dataWarehouse = ref(null)
const fetchingWarehouse = async () => {
  const response = await axios.get('http://127.0.0.1:8000/api/warehouses', {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })

  dataWarehouse.value = response.data.data
  console.log(response)
}

const detailWarehouse = ref(null)
const fetchDetailWarehouse = async () => {
  const response = await axios.get('http://127.0.0.1:8000/api/warehouse-stock', {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })

  detailWarehouse.value = response.data.data[0]
  console.log(detailWarehouse.value)
}

onMounted(() => {
  if (role === 'admin') {
    fetchingWarehouse()
  }

  fetchDetailWarehouse()
})
</script>

<template>
  <div class="container-fluid">
    <div class="page-header">
      <h1>Manage Warehouse</h1>
    </div>
    <hr />

    <div class="row" v-if="role === 'admin'">
      <div class="col-md-4 mb-4" v-for="(item, index) in dataWarehouse" :key="index">
          <div class="card warehouse-card">
            <div class="card-body">
              <h5 class="card-title">{{ item.name }}</h5>
              <p class="card-text">{{ item.location }}</p>
              <span class="item-count">{{ item.items_count }}</span>
            </div>
          </div>
      </div>
    </div>

    <div v-if="role !== 'admin'">
      <div class="page-header">
        <div>
          <h1>{{ detailWarehouse?.warehouse?.name }}</h1>
          <p class="warehouse-address">{{ detailWarehouse?.warehouse?.location }}</p>
        </div>
      </div>
      <hr />

      <h5 class="section-title">Items in Warehouse</h5>
      <table class="table">
        <thead>
          <tr>
            <th>No</th>
            <th>Item Name</th>
            <th>Stock</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in detailWarehouse?.items" :key="index">
            <td>{{ index+1 }}</td>
            <td> {{ item?.name }}</td>
            <td>{{ item?.stock }}</td>
          </tr>
        </tbody>
      </table>
    </div>
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
  align-items: flex-start;
}

.page-header h1 {
  font-size: 24px;
  font-weight: 600;
  margin: 0;
}

.btn-back {
  color: #6c757d;
  text-decoration: none;
  font-size: 14px;
  display: inline-block;
  margin-bottom: 8px;
}

.btn-back:hover {
  color: #0d6efd;
}

.warehouse-address {
  color: #6c757d;
  margin: 5px 0 0 0;
}

hr {
  border-color: #dee2e6;
  margin: 20px 0 30px 0;
}

/* Card Styles */
.card-link {
  text-decoration: none;
  color: inherit;
}

.warehouse-card {
  border: 1px solid #dee2e6;
  border-radius: 8px;
  transition: box-shadow 0.2s;
}

.warehouse-card:hover {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.warehouse-card .card-title {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 8px;
}

.warehouse-card .card-text {
  color: #6c757d;
  font-size: 14px;
  margin-bottom: 12px;
}

.warehouse-card .item-count {
  background-color: #e9ecef;
  padding: 4px 10px;
  border-radius: 4px;
  font-size: 13px;
  color: #495057;
}

/* Table Styles */
.section-title {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 15px;
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

/* Modal Styles */
.modal-header {
  border-bottom: 1px solid #dee2e6;
}

.modal-footer {
  border-top: 1px solid #dee2e6;
}

.modal-title {
  font-weight: 600;
}
</style>
