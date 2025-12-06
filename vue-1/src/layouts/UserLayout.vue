<script setup>
    import Navbar from '../components/Navbar.vue';
    import Footer from '../components/Footer.vue';
</script>

<template>
    <Navbar></Navbar>
        <div>
            <router-view />
        </div>
    <Footer></Footer>
</template>

<style>
     
   
</style>