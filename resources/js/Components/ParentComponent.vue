<template>
    <Dashboard :route="'students.store'" />
  </template>
  
  <script>
  import Dashboard from '@/js/profile/Dashboard.vue';
  
  export default {
    components: {
      Dashboard,
    },
  };
  </script>