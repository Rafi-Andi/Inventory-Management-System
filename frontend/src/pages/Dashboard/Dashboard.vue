<script setup>
import router from '@/router';

const role = localStorage.getItem('role')
const token = localStorage.getItem('token')
if (!token) {
  router.push({ name: 'Login' })
}
if (token && role) {
  if (role === 'admin' || role === 'manager') {
    router.push({ name: 'IndexPurchase' })
  } else if (role === 'staff') {
    router.push({ name: 'IndexCategory' })
  }
}
const logout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('role')
  router.push({name: 'Login'})
}
</script>

<template>
  <div class="container">
    <div class="sidebar">
      <div class="sidebar-header">
        <h3 class="logo">Dashboard {{ role }}</h3>
        <button class="toggle-btn" aria-label="Toggle Sidebar">☰</button>
      </div>

      <ul class="nav-links">
        <router-link
          v-if="role === 'admin' || role === 'manager'"
          :to="{ name: 'IndexPurchase' }"
          active-class="active"
        >
          Purchase
        </router-link>
        <router-link active-class="active" :to="{ name: 'IndexCategory' }"> Category </router-link>
        <router-link active-class="active" :to="{ name: 'IndexWarehouse' }">
          Warehouse
        </router-link>
        <router-link active-class="active" :to="{ name: 'IndexItem' }"> Item </router-link>
        <router-link active-class="active" :to="{ name: 'IndexSupplier' }"> Supplier </router-link>
        <div class="sidebar-footer">
          <h5 style="cursor: pointer; color: lightcoral;" @click="logout()" >Logout</h5>
        </div>
      </ul>

    </div>

    <div class="main-content">
      <router-view></router-view>
    </div>
  </div>
</template>

<style scoped>
.container {
  padding: 0;
  margin: 0;
  display: flex;
  min-height: 100vh;
}

.sidebar {
  width: 250px;
  background-color: #2c3e50; /* Warna gelap untuk sidebar */
  color: #ecf0f1; /* Warna teks terang */
  padding: 20px 0;
  display: flex;
  flex-direction: column;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease; /* Transisi untuk animasi saat diperkecil */
  flex-shrink: 0; /* Mencegah sidebar menyusut */
}

/* Header dan Logo */
.sidebar-header {
  padding: 0 20px 20px 20px;
  border-bottom: 1px solid #34495e;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo {
  font-size: 1.5em;
  font-weight: bold;
  color: #3498db; /* Warna aksen untuk logo */
}

.toggle-btn {
  display: none; /* Sembunyikan tombol di desktop */
  background: none;
  border: none;
  color: #ecf0f1;
  font-size: 1.5em;
  cursor: pointer;
}

/* Daftar Navigasi */
.nav-links {
  list-style: none;
  flex-grow: 1; /* Memungkinkan daftar mengisi ruang tengah */
  padding-top: 10px;
}

.nav-links li {
  margin-bottom: 5px;
}

.nav-links a {
  display: flex;
  align-items: center;
  padding: 10px 20px;
  text-decoration: none;
  color: #bdc3c7;
  font-size: 1.1em;
  transition:
    background-color 0.2s,
    color 0.2s;
}

/* Efek Hover */
.nav-links a:hover {
  background-color: #34495e;
  color: #ecf0f1;
}

/* Status Aktif */
.nav-links a.active {
  background-color: #3498db; /* Warna aksen untuk item aktif */
  color: #ffffff;
  font-weight: bold;
}

.nav-links a.active:hover {
  background-color: #2980b9; /* Warna sedikit lebih gelap saat hover pada aktif */
}

/* Ikon */
.icon {
  margin-right: 15px;
  font-size: 1.2em;
  width: 20px; /* Lebar tetap untuk ikon agar rata */
  text-align: center;
}

/* Garis Pemisah */
.separator {
  height: 1px;
  background-color: #34495e;
  margin: 15px 20px;
}

/* Footer (Keluar) */
.sidebar-footer {
  padding: 10px 20px;
  border-top: 1px solid #34495e;
}

.sidebar-footer a {
  color: #e74c3c; /* Warna merah untuk "Keluar" */
  text-decoration: none;
  display: block;
  padding: 5px 0;
  transition: color 0.2s;
}

.sidebar-footer a:hover {
  color: #c0392b;
}

/* 2. STYLING KONTEN UTAMA */
.main-content {
  flex-grow: 1; /* Memungkinkan konten utama mengisi ruang sisa */
  padding: 20px;
  overflow-y: auto;
}

/* 3. RESPONSIVITAS (Media Queries) */
@media (max-width: 768px) {
  .sidebar {
    /* Sidebar default disembunyikan di layar kecil */
    position: fixed;
    left: -250px;
    height: 100%;
    z-index: 1000; /* Pastikan di atas konten lain */
  }

  .sidebar-header {
    justify-content: space-between;
  }

  .toggle-btn {
    display: block; /* Tampilkan tombol di layar kecil */
  }

  /* CSS untuk Sidebar saat ditampilkan (perlu JavaScript) */
  /* .sidebar.open {
        left: 0;
    } */

  .main-content {
    margin-left: 0; /* Konten utama mengambil lebar penuh */
  }
}
</style>
