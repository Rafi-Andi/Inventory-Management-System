<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";

const role = localStorage.getItem("role");

const dataItem = ref([]);
const categories = ref([]);

const search = ref("");
const categoryId = ref("");
const currentPage = ref(1);
const perPage = ref(5);
const lastPage = ref(1);

const fetchCategories = async () => {
  const res = await axios.get("http://127.0.0.1:8000/api/categories", {
    headers: {
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    },
  });
  categories.value = res.data.data;
};

const fetchItems = async () => {
  const response = await axios.get("http://127.0.0.1:8000/api/items", {
    headers: {
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    },
    params: {
      search: search.value,
      category_id: categoryId.value,
      per_page: perPage.value,
      page: currentPage.value,
    },
  });

  dataItem.value = response.data.data.data;

  console.log(response)
  lastPage.value = response.data.data.last_page;
};

const filter = () => {
  currentPage.value = 1;
  fetchItems();
};

const resetFilter = () => {
  search.value = "";
  categoryId.value = "";
  currentPage.value = 1;
  fetchItems();
};

const goToPage = (page) => {
  currentPage.value = page;
  fetchItems();
};

const nextPage = () => {
  if (currentPage.value < lastPage.value) {
    currentPage.value++;
    fetchItems();
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    fetchItems();
  }
};

const handleDelete = async (id) => {
  try {
    await axios.delete(`http://127.0.0.1:8000/api/items/${id}`);
    fetchItems();
    alert("Berhasil hapus item");
  } catch (error) {
    alert("Gagal hapus item");
  }
};

onMounted(() => {
  fetchItems();
  fetchCategories();
});
</script>

<template>
  <div class="container-fluid">
    <div class="page-header">
      <h1>Manage Items</h1>
      <router-link v-if="role === 'admin'" :to="{ name: 'CreateItem' }" class="btn btn-primary">Create Item</router-link>
    </div>
    <hr />

    <div class="filter-card p-4 mb-4 bg-light rounded-3 shadow-sm">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Search Item</label>
          <input type="text" v-model="search" placeholder="Search by name..." class="form-control" />
        </div>

        <div class="col-md-4">
          <label class="form-label">Category</label>
          <select class="form-select" v-model="categoryId">
            <option value="">All Categories</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
        </div>

        <div class="col-md-4 d-flex align-items-end gap-2">
          <button class="btn btn-primary w-100" @click="filter">Filter</button>
          <button class="btn btn-secondary w-100" @click="resetFilter">Reset</button>
        </div>
      </div>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Category</th>
          <th>Description</th>
          <th v-if="role === 'admin'">Action</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(item, index) in dataItem" :key="item.id">
          <td>{{ item.name }}</td>
          <td>{{ item.category.name }}</td>
          <td>{{ item.description }}</td>
          <td v-if="role === 'admin'">
            <router-link :to="{name: 'EditItem', params: {id: item?.id, name: item?.name, category_id: item.category_id, description: item.description}}" class="btn btn-warning btn-sm me-2">Edit</router-link>
            <button class="btn btn-danger btn-sm" @click="handleDelete(item.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <nav class="mt-4">
      <ul class="pagination justify-content-center">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <button class="page-link" @click="prevPage">Previous</button>
        </li>

        <li
          class="page-item"
          v-for="page in lastPage"
          :key="page"
          :class="{ active: page === currentPage }"
        >
          <button class="page-link" @click="goToPage(page)">{{ page }}</button>
        </li>

        <li class="page-item" :class="{ disabled: currentPage === lastPage }">
          <button class="page-link" @click="nextPage">Next</button>
        </li>
      </ul>
    </nav>
  </div>
</template>

<style scoped>
body {
  background-color: #fff;
}

.filter-card {
  border: 1px solid #ddd;
}

.container-fluid {
  padding: 30px 40px;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.table thead th {
  background-color: #f8f9fa;
  font-weight: 500;
}

.table tbody tr:hover {
  background-color: #f8f9fa;
}

.btn-sm {
  padding: 5px 15px;
  font-size: 13px;
}
</style>