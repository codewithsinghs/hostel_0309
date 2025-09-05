<template>
  <section>
    <h1>Welcome to RNTU Hostel</h1>
    <p>{{ description }}</p>
  </section>
</template>

<script>
export default {
  data() {
    return {
      description: ''
    }
  },
  mounted() {
    fetch('/api/home')
      .then(res => res.json())
      .then(data => {
        this.description = data.message
      })
  }
}
</script>
