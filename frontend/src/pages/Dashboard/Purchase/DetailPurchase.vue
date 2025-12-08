<script setup>
import { useRoute } from 'vue-router'
import axios from 'axios'
import { onMounted, ref } from 'vue'
import router from '@/router'
import formatTanggalIndo from '@/lib/formatDate'

const role = localStorage.getItem('role')

if (role !== 'admin' && role !== 'manager') {
  router.push({ name: 'Login' })
}

const route = useRoute()
const id = route.params.id
console.log(id)
const dataPurchase = ref(null)

const fetchingPurchase = async () => {
  const response = await axios.get(`http://127.0.0.1:8000/api/purchase-orders/${id}`, {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })

  dataPurchase.value = response.data.purchase_order
  console.log(dataPurchase.value)
}

const handleApprove = async (id) => {
  try {
    const response = await axios.post(
      `http://127.0.0.1:8000/api/purchase-orders/${id}/approve`,
      {},
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      },
    )
    alert('berhasil')
    dataPurchase.value.status = 'received'
  } catch (error) {
    alert(error.response.data.message)
  }
}
const handleReject = async (id) => {
  try {
    const response = await axios.post(
      `http://127.0.0.1:8000/api/purchase-orders/${id}/reject`,
      {},
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      },
    )
    alert('berhasil')
    dataPurchase.value.status = 'cancelled'
  } catch (error) {
    alert('error')
    console.log(error)
  }
}

onMounted(() => {
  fetchingPurchase()
})
</script>

<template>
  <div class="container-fluid">
    <div class="page-header">
      <div style="margin-bottom: 2px">
        <router-link :to="{ name: 'IndexPurchase' }" class="btn-back">&larr; Back</router-link>
        <h1>PURCHASE-{{ id }}</h1>
        <span class="badge" :class="dataPurchase?.status">{{ dataPurchase?.status }}</span>
      </div>
      <div>
        <button
          @click="handleApprove(dataPurchase.id)"
          v-if="role === 'admin' && dataPurchase?.status === 'pending'"
          class="btn btn-success"
          style="margin-right: 2px"
        >
          Approve
        </button>
        <button
          @click="handleReject(dataPurchase.id)"
          v-if="role === 'admin' && dataPurchase?.status === 'pending'"
          class="btn btn-danger"
        >
          Reject
        </button>
      </div>
    </div>
    <hr />

    <div class="row mb-4">
      <div class="col-md-6">
        <div class="info-card">
          <h6>Order Information</h6>
          <table class="info-table">
            <tr>
              <td>Supplier</td>
              <td>: {{ dataPurchase?.supplier?.name }}</td>
            </tr>
            <tr>
              <td>Requester:</td>
              <td>{{ dataPurchase?.user?.name }}</td>
            </tr>
            <tr>
              <td>Date</td>
              <td>: {{ formatTanggalIndo(dataPurchase?.created_at) }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <h5 class="section-title">Order Items</h5>
    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>Item Name</th>
          <th>Category Id</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in dataPurchase?.purchase_order_items" :key="index">
          <td>1</td>
          <td>{{ item?.name }}</td>
          <td>{{ item?.category_id }}</td>
          <td>{{ item?.description }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>
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
  color: white;
}
</style>
