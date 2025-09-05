<template>
    <div class="p-3">
      <h2>Accessories</h2>
  
      <table v-if="items.length" class="table table-striped">
        <tr v-for="item in items" :key="item.id">
          <td>{{ item.name }}</td>
          <td>{{ item.qty }}</td>
          <td>₹{{ item.price }}</td>
        </tr>
      </table>
  
      <p v-else>No accessories available.</p>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return {
        items: []
      };
    },
    mounted() {
      axios.get('/api/accessories')
        .then(response => {
          this.items = response.data;
        })
        .catch(error => console.error(error));
    }
  }
  </script>
  