<script setup>
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
</script>

<template>
  <div>
    <Header />
    <main>
      <!-- Router decides which page (Home, Contact, etc.) to load -->
      <router-view />
    </main>
    <Footer />
  </div>
</template>
