<template>
  <div v-if="show" class="modal-backdrop" @click="close">
    <div class="modal-box" @click.stop>
      <button class="close-btn" @click="close">×</button>

      <!-- YOUR FULL LOGIN COMPONENT -->
      <Login />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import Login from "../pages/auth/Login.vue"; // ← your existing login.vue

const show = ref(false);

// handlers (important to keep reference)
const openModal = () => {
  show.value = true;
};

const closeModal = () => {
  show.value = false;
};

onMounted(() => {
  window.addEventListener("open-login-modal", openModal);
  window.addEventListener("close-login-modal", closeModal);
});

onBeforeUnmount(() => {
  window.removeEventListener("open-login-modal", openModal);
  window.removeEventListener("close-login-modal", closeModal);
});

function close() {
  show.value = false;
}
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.65);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
  animation: fadeIn 0.3s;
}

.modal-box {
  width: 95%;
  max-width: 500px;
  background: white;
  padding: 0;
  border-radius: 14px;
  overflow: hidden;
  animation: scaleIn 0.25s;
  position: relative;
}

.close-btn {
  position: absolute;
  right: 12px;
  top: 10px;
  font-size: 28px;
  background: none;
  border: none;
  cursor: pointer;
  color: #666;
  z-index: 10;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes scaleIn {
  from {
    transform: scale(0.92);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}
</style>
