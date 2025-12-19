<script setup>
import AdminNavbar from "../components/AdminNavbar.vue";
import ControllerSidebar from "../components/ControllerSidebar.vue";
</script>

<template>
<div class="mool">
  <ControllerSidebar></ControllerSidebar>

  <main>
    <AdminNavbar></AdminNavbar>
    <div>
      <router-view />
    </div>
  </main>
</div>
</template>

<style>
body {
  display: block !important;
  place-items: unset !important;
}
.mool {
  color: #220901;
  /* background-color: var(--back-color); */
  line-height: 1.5rem;
  display: grid;
  grid-template-columns: auto 1fr;
  min-width: 100vh;
}
/* .page-main-div{
    z-index: 9999;
} */
h1,
h2,
h3,
h4,
h5 {
  font-family: "El Messiri", sans-serif;
  font-weight: 800;
}
main {
  padding: 0;
  background-color: transparent;
  min-height: 100vh;
  border-left: 0.5px solid var(--main-color);
}
</style>
