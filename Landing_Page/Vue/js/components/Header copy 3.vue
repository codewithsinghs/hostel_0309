<script setup>
import { ref, onMounted, onUnmounted } from "vue";

const searchQuery = ref("");

// simple search handler
const search = () => {
  alert(`Searching for: ${searchQuery.value}`);
};

// reactive scroll behavior
const isVisible = ref(true);
let lastScrollY = 0;
let timeoutId = null;

const handleScroll = () => {
  const currentScrollY = window.scrollY;

  if (currentScrollY > lastScrollY) {
    // scrolling down → hide header
    isVisible.value = false;
  } else {
    // scrolling up → show header
    isVisible.value = true;
  }

  lastScrollY = currentScrollY;

  // show header if scrolling stops
  clearTimeout(timeoutId);
  timeoutId = setTimeout(() => {
    isVisible.value = true;
  }, 250);
};

onMounted(() => {
  window.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
  window.removeEventListener("scroll", handleScroll);
  clearTimeout(timeoutId);
});
</script>

<template>
  <header
    class="main-header"
    :class="{ 'header-hidden': !isVisible }"
  >
    <nav class="navbar navbar-expand-lg nav-container">
      <!-- Left side -->
      <div class="nav-container-left">
        <a href="/" class="">
          <div class="navbar-brand register-header-logo">
            <img
              src="../../front/img/main-logo.png"
              alt="RNTU Logo"
              class="logo"
            />
          </div>
        </a>

        <div class="search-bar">
          <input
            type="text"
            placeholder="Type Something"
            v-model="searchQuery"
          />
          <button @click="search">
            <img
              src="https://img.icons8.com/ios-filled/20/000000/search--v1.png"
              alt="Search"
            />
          </button>
        </div>
      </div>

      <!-- Hamburger -->
      <button
        class="hamburger"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#headercollape"
        aria-controls="headercollape"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span></span><span></span><span></span>
      </button>

      <!-- Right side -->
      <div
        class="collapse navbar-collapse justify-content-end"
        id="headercollape"
      >
        <div class="nav-container-right">
          <ul class="nav-links">
            <li><a href="#hero-section" class="nav-link">Home</a></li>
            <li><a href="#support-section" class="nav-link">Services</a></li>
            <li><a href="#hostel-list-section" class="nav-link">Hostels</a></li>
          </ul>

          <div class="nav-btns">
            <button class="btn-custom btn-register">
              <a href="{{ route('guest.register') }}">Registration</a>
            </button>
            <button class="btn-custom btn-register">
              <a href="#">Login</a>
            </button>
          </div>
        </div>
      </div>
    </nav>
  </header>
</template>

<style scoped>
.main-header {
  position: sticky;
  top: 0;
  z-index: 1050;
  background: #fff;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, opacity 0.3s ease;
}

/* Hide header smoothly */
.header-hidden {
  transform: translateY(-100%);
  opacity: 0;
}

.nav-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.8rem 1.5rem;
}

.logo {
  max-height: 50px;
  transition: transform 0.3s;
}

.logo:hover {
  transform: scale(1.05);
}

.search-bar {
  display: flex;
  align-items: center;
  margin-left: 20px;
}

.search-bar input {
  border: 1px solid #ddd;
  padding: 6px 10px;
  border-radius: 20px 0 0 20px;
  outline: none;
}

.search-bar button {
  border: 1px solid #ddd;
  border-left: none;
  padding: 6px 12px;
  background: #f8f9fa;
  border-radius: 0 20px 20px 0;
  cursor: pointer;
}

.nav-links {
  list-style: none;
  display: flex;
  gap: 20px;
  margin: 0;
  padding: 0;
}

.nav-links .nav-link {
  color: #333;
  font-weight: 500;
  text-decoration: none;
  transition: color 0.2s;
}

.nav-links .nav-link:hover {
  color: #007bff;
}

.btn-custom {
  margin-left: 10px;
  background: #007bff;
  border: none;
  color: #fff;
  border-radius: 20px;
  padding: 6px 15px;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-custom a {
  color: #fff;
  text-decoration: none;
}

.btn-custom:hover {
  background: #0056b3;
}
</style>
