<template>
  <div v-if="show" class="modal-overlay" @click.self="close">
    <div class="modal-card enter">
      <h2 class="text-center">Join With Runstar</h2>
      <p class="lead text-center">
        Create your account to start using the dashboard
      </p>

      <form class="form-regi" @submit.prevent="submitForm">
        <!-- Fullname -->
        <div class="field">
          <label>Fullname</label>
          <input v-model="form.fullname" required />
          <div class="error" v-if="errors.fullname">{{ errors.fullname }}</div>
        </div>

        <!-- Username -->
        <div class="field">
          <label>Username</label>
          <input v-model="form.username" required />
          <div class="error" v-if="errors.username">{{ errors.username }}</div>
        </div>

        <!-- Email -->
        <div class="field">
          <label>Email</label>
          <input type="email" v-model="form.email" required />
          <div class="error" v-if="errors.email">{{ errors.email }}</div>
        </div>

        <!-- Password -->
        <div class="field">
          <label>Password</label>
          <input type="password" v-model="form.password" required />
          <div class="error" v-if="errors.password">{{ errors.password }}</div>
        </div>

        <!-- Confirm Password -->
        <div class="field">
          <label>Confirm Password</label>
          <input
            type="password"
            v-model="form.password_confirmation"
            required
          />
        </div>

        <!-- Phone -->
        <div class="field">
          <label>Phone</label>
          <input v-model="form.phone" />
        </div>

        <!-- Address -->
        <div class="field">
          <label>Address</label>
          <textarea v-model="form.address"></textarea>
        </div>

        <!-- Photo -->
        <div class="field">
          <label>Profile Picture</label>
          <input type="file" @change="handleFile" accept="image/*" />
        </div>

        <button type="submit" class="btn-primary">Create Account</button>

        <div class="meta">
          Already have an account?
          <a @click="goLogin">Sign in</a>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive } from "vue";

// Props
const props = defineProps({
  show: Boolean,
});

// Emits
const emit = defineEmits(["close", "openLogin"]);

// Form data
const form = reactive({
  fullname: "",
  username: "",
  email: "",
  password: "",
  password_confirmation: "",
  phone: "",
  address: "",
  profile_photo: null,
});

// Errors
const errors = reactive({});

// Close modal
function close() {
  emit("close");
}

// Switch to Login modal
function goLogin() {
  emit("openLogin");
}

// Handle file upload
function handleFile(e) {
  form.profile_photo = e.target.files[0];
}

// Submit form
async function submitForm() {
  const formData = new FormData();

  Object.keys(form).forEach((key) => {
    formData.append(key, form[key]);
  });

  try {
    const res = await fetch("http://localhost:8000/register", {
      method: "POST",
      body: formData,
    });

    const data = await res.json();

    if (!res.ok) {
      Object.assign(errors, data.errors || {});
      return;
    }

    alert("Account created successfully!");
    close();
  } catch (err) {
    console.error(err);
  }
}
</script>

<style scoped>
/* Modal Background */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.55);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 99999;
}

/* Modal Card */
.modal-card {
  background: white;
  border-radius: var(--radius-lg);
  padding: 28px;
  width: 90%;
  max-width: 600px;
  box-shadow: var(--card-shadow);
}

/* Keep your same CSS */
p.lead {
  color: var(--muted);
  margin-bottom: 18px;
}

.form-regi {
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.field {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

label {
  font-size: 13px;
  color: var(--muted);
}

input,
textarea {
  height: 48px;
  border-radius: var(--radius-md);
  border: 1px solid rgba(16, 16, 16, 0.06);
  padding: 12px 14px;
  font-size: 15px;
  background: #fff;
}

textarea {
  height: 90px;
}

.btn-primary {
  background: linear-gradient(90deg, var(--main-color), var(--second-color));
  color: white;
  height: 48px;
  border-radius: 12px;
  border: none;
  cursor: pointer;
  font-size: 15px;
  font-weight: 600;
  margin-top: 5px;
}

.error {
  color: #b32;
  font-size: 13px;
}

.meta {
  margin-top: 10px;
  text-align: center;
}
.meta a {
  color: var(--main-color);
  font-weight: 600;
  cursor: pointer;
}

.enter {
  animation: enter 0.4s ease-out both;
}

@keyframes enter {
  from {
    transform: translateY(8px);
    opacity: 0;
  }
  to {
    opacity: 1;
    transform: none;
  }
}
</style>
