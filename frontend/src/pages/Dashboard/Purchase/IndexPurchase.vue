<script setup>
import formatTanggalIndo from '@/lib/formatDate'
import router from '@/router'
import axios from 'axios'
import { onMounted, ref } from 'vue'

const role = localStorage.getItem('role')

if (role !== 'admin' && role !== 'manager') {
  router.push({ name: 'Login' })
}

const dataPurchase = ref(null)

const fetchingPurchase = async () => {
  const response = await axios.get('http://127.0.0.1:8000/api/purchase-orders', {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })

  dataPurchase.value = response.data.data
}
const fetchingMyPurchase = async () => {
  const response = await axios.get('http://127.0.0.1:8000/api/my-purchase-orders', {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })

  dataPurchase.value = response.data.purchase_orders
  console.log(response)
}

onMounted(() => {
  if (role === 'admin') {
    fetchingPurchase()
  }

  if (role === 'manager') {
    fetchingMyPurchase()
  }
})
</script>

<template>
  <div class="container-fluid">
    <div class="page-header">
      <div>
        <h1>Halo</h1>
        <p class="subtitle">Welcome back to Inventory System</p>
        <router-link
          :to="{ name: 'CreatePurchase' }"
          class="btn btn-primary"
          router
          v-if="role === 'manager'"
          >Create Purchase</router-link
        >
      </div>
    </div>
    <hr />

    <h5 class="section-title">Recent Purchase Orders</h5>
    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>Supplier</th>
          <th v-if="role === 'admin'">Warehouse</th>
          <th>Requester</th>
          <th>Date</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in dataPurchase" :key="index">
          <td>{{ index + 1 }}</td>
          <td>{{ item?.supplier?.name }}</td>
          <td v-if="role === 'admin'">{{ item?.to_warehouse?.name }}</td>
          <td>{{ item?.user?.name }}</td>
          <td>{{ formatTanggalIndo(item?.created_at) }}</td>
          <td>
            <span class="badge status" :class="item?.status">{{ item?.status }}</span>
          </td>
          <td>
            <router-link
              :to="{ name: 'DetailPurchase', params: { id: item.id } }"
              class="btn btn-info btn-sm"
              >Detail</router-link
            >
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

.pending {
  background-color: yellow;
  color: black;
}
.approved {
  background-color: green;
}
.received {
  background-color: #0d6efd;
}
.cancelled {
  background-color: red;
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

hr {
  border-color: #dee2e6;
  margin: 20px 0 30px 0;
}

/* Table Styles */
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

.btn-sm {
  padding: 5px 12px;
  font-size: 13px;
}

.btn-info {
  color: #fff;
  background-color: #17a2b8;
  border: none;
}

.btn-info:hover {
  color: #fff;
  background-color: #138496;
}

/* Badge Styles */
.badge {
  font-weight: 500;
  padding: 5px 10px;
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

.form-control-sm,
.form-select-sm {
  padding: 6px 10px;
}

.section-title {
  font-size: 16px;
  font-weight: 600;
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

/* Detail Page Styles */
.info-card {
  background-color: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 6px;
  padding: 20px;
  height: 100%;
}

.info-card h6 {
  font-weight: 600;
  margin-bottom: 15px;
  color: #495057;
}

.info-table {
  width: 100%;
}

.info-table td {
  padding: 5px 0;
  font-size: 14px;
}

.info-table td:first-child {
  width: 100px;
  color: #6c757d;
}

.notes-text {
  font-size: 14px;
  color: #495057;
  margin: 0;
}
</style>
