<template>
    <div class="container mt-3">
      <h3>Accessories</h3>
      <table class="table table-striped" v-if="accessories.length">
        <thead>
          <tr>
            <th>Name</th>
            <th>Quantity</th>
            <th>Price (₹)</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in accessories" :key="item.id">
            <td>{{ item.name }}</td>
            <td>{{ item.qty }}</td>
            <td>₹{{ item.price }}</td>
          </tr>
        </tbody>
      </table>
      <p v-else>Loading...</p>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  
  const accessories = ref([])
  
  onMounted(async () => {
    try {
      const res = await fetch('/api/accessories')
      accessories.value = await res.json()
    } catch (err) {
      console.error('Error fetching accessories:', err)
    }
  })
  </script>
  