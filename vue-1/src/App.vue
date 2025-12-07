<template>
  <!-- All normal pages -->
  <router-view />

  <!-- GLOBAL LOGIN MODAL -->
  <LoginModal />
</template>

<script setup>
import LoginModal from "./components/LoginModal.vue";
</script>

<style>
#app {
  max-width: 100%;
  padding: 0;
  text-align: start;
}
body {
  padding: 0;
  margin: 0;
}
:root {
  --main-color: #ff0000;
  --second-color: #780116;
  --bg-color: #fffffc;
  --back-color: #edf6f9af;
  --text-color: #220901;
  --light-hover: #ff0101ca;
  --muted: #9aa6b2;
  --radius: 12px;
  --gap: 24px;
  --text: #e6eef6;
  --glass: rgba(255, 255, 255, 0.02);
}
</style>
