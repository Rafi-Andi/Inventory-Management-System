<script setup>
import axios from 'axios'
import { onMounted, ref } from 'vue'

const form = ref({
  supplier_id: '',
  from_warehouse_id: '',
  items: [
    {item_id: '', quantity: 1}
  ]
})
function addRow() {
  form.value.items.push({ item_id: '', quantity: 1 })
}

function removeRow(index) {
  form.value.items.splice(index, 1)
}

const dataSupplier = ref(null)
const fetchingSupplier = async () => {
  const response = await axios.get('http://127.0.0.1:8000/api/suppliers', {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })

  dataSupplier.value = response.data.suppliers
}

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

const dataItem = ref(null)
const fetchingItem = async () => {
  const response = await axios.get('http://127.0.0.1:8000/api/items', {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })

  dataItem.value = response.data.data.data
  console.log(response)
}

const handlePO = async () => {
  try {
    console.log(form.value)
    const response = await axios.post('http://127.0.0.1:8000/api/purchase-orders', form.value, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    })

    console.log(response)

    alert('berhasil')
  } catch (error) {
    alert('error')
    console.log(error)
  }
}

onMounted(() => {
  fetchingWarehouse()
  fetchingSupplier()
  fetchingItem()
})
</script>

<template>
  <div class="container-fluid">
    <div class="page-header">
      <h1>Create Purchase Order</h1>
    </div>
    <hr />

    <form class="form-container" @submit.prevent="handlePO()">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="supplier" class="form-label">Supplier</label>
          <select class="form-select" id="supplier" v-model="form.supplier_id">
            <option value="" selected disabled>Select supplier</option>
            <option v-for="(item, index) in dataSupplier" :value="item.id" :key="index">{{ item.name }}</option>
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label for="warehouse" class="form-label">From Warehouse</label>
          <select class="form-select" id="warehouse" v-model="form.from_warehouse_id">
            <option value="" selected disabled>Select warehouse</option>
            <option v-for="(item, index) in dataWarehouse" :key="index" :value="item.id">{{ item.name }}</option>
          </select>
        </div>
      </div>

      <hr />

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="section-title mb-0">Order Items</h5>
        <button type="button" @click="addRow()" id="addItemBtn" class="btn btn-primary">
          Add Item
        </button>
      </div>
      <table id="itemsTable" class="table table-sm">
        <tbody>
          <tr v-for="(row, index) in form.items" :key="index">
            <td>
              <select v-model="row.item_id" class="form-select form-select-sm">
                <option value="" disabled>Select item</option>
                <option v-for="(item, index) in dataItem" :key="index" :value="item.id">{{ item.name }}</option>
              </select>
            </td>

            <td>
              <input type="number" v-model="row.quantity" class="form-control form-control-sm" min="1" />
            </td>

            <td>
              <button type="button" @click="removeRow(index)" class="btn btn-danger btn-sm">
                Remove
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Submit Request</button>
        <a href="index.html" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</template>

<style scoped>
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
