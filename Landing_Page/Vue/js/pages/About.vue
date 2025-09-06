<template>
    <div class="container py-5">
      <h2>Contact Us</h2>
      <form class="mt-4">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" class="form-control" placeholder="Your Name">
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" placeholder="Your Email">
        </div>
        <div class="mb-3">
          <label class="form-label">Message</label>
          <textarea class="form-control" rows="4" placeholder="Write your message"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send</button>
      </form>
    </div>
  </template>
  
  <script setup>
  // Later you can handle form submission using Axios
  </script>
  