<template>
  <div class="register-form-card">
    <h2 class="text-center">Join With Runstar</h2>
    <p class="lead text-center">
      Create your account to start using the dashboard
    </p>

    <form
      class="form-regi"
      @submit.prevent="submitForm"
      enctype="multipart/form-data"
    >
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
        <input type="password" v-model="form.password_confirmation" required />
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

      <!-- Profile Picture -->
      <div class="field">
        <label>Profile Picture</label>
        <input type="file" @change="handleFile" accept="image/*" />
      </div>

      <button type="submit" class="btn btn-primary">Create Account</button>

      <div class="meta">
        Already have an account?
        <router-link to="/login">Sign in</router-link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();

// Form state
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

// Validation errors
const errors = reactive({});

// Handle file upload
function handleFile(event) {
  form.profile_photo = event.target.files[0];
}

// Submit form
async function submitForm() {
  const formData = new FormData();
  Object.keys(form).forEach((key) => {
    formData.append(key, form[key]);
  });

  // Reset errors
  Object.keys(errors).forEach((key) => (errors[key] = null));

  try {
    const res = await axios.post("http://localhost:8000/register", formData, {
      headers: { "Content-Type": "multipart/form-data" },
    });

    // Successful registration
    alert("Account created successfully!");
    router.push("/login"); // redirect to login page
  } catch (err) {
    if (err.response?.status === 422) {
      // Validation errors from backend
      Object.assign(errors, err.response.data.errors);
    } else {
      alert(err.response?.data?.message || "Something went wrong!");
    }
  }
}
</script>

<style scoped>
/* Define Global Styles for the Container (Assuming this component sits alone on a page) */
.register-form-card {
  /* Using a slightly darker background for contrast */
  background: var(--section-bg, #fffffc);
  border-radius: var(--radius-lg, 18px);
  padding: 36px 40px; /* Increased padding for better breathing room */
  box-shadow: var(--card-shadow, 0 8px 30px rgba(15, 15, 15, 0.12));
  width: 100%;
  max-width: 600px; /* Adjusted max-width */
  margin: 40px auto;
  animation: enter 0.6s cubic-bezier(0.2, 0.9, 0.2, 1) both;
  box-sizing: border-box;
}

h2 {
  font-weight: 800;
  color: var(--main-color, #ff0000);
  margin-bottom: 8px;
}

p.lead {
  color: var(--muted, #6b6b6b);
  margin-bottom: 24px; /* Increased margin below description */
  font-size: 15px;
}

/* Form Layout */
.form-regi {
  display: flex;
  flex-direction: column;
  gap: 18px; /* Increased gap between fields */
}

.field {
  display: flex;
  flex-direction: column;
  gap: 6px; /* Slightly reduced gap between label and input */
}

label {
  font-size: 13px;
  font-weight: 600;
  color: var(--muted, #6b6b6b);
}

/* Input and Textarea Styles */
input,
textarea {
  width: 100%;
  height: 48px;
  border-radius: var(--radius-md, 10px);
  border: 1px solid #ff0000; /* Clean, subtle border */
  padding: 12px 14px;
  font-size: 15px;
  outline: none;
  background: #fff;
  transition: all 0.2s;
  box-sizing: border-box; /* Crucial for responsive layout */
}

textarea {
  height: 100px; /* Slightly taller */
  resize: vertical;
}

/* Focus State - Smart Design */
input:focus,
textarea:focus {
  /* Using main color for border and second color for subtle shadow */
  border-color: var(--main-color, #ff0000);
  box-shadow: 0 0 0 3px rgba(120, 1, 22, 0.15); /* Soft, colored focus ring */
}

/* File Input specific override */
input[type="file"] {
  padding: 10px 14px; /* Slightly smaller padding for file input appearance */
}

/* Button Styles */
.btn {
  height: 48px;
  padding: 0 20px;
  border-radius: 12px;
  border: none;
  cursor: pointer;
  font-weight: 600;
  font-size: 16px;
  transition: transform 0.1s ease-in-out, opacity 0.2s;
}

.btn:hover {
  transform: translateY(-2px);
  opacity: 0.95;
}

.btn-primary {
  /* Using your red gradient for high impact */
  background: linear-gradient(
    90deg,
    var(--main-color, #ff0000),
    var(--second-color, #780116)
  );
  color: #fff;
  box-shadow: 0 10px 30px rgba(120, 1, 22, 0.25); /* Deeper shadow for the CTA */
  margin-top: 6px;
}

/* Error and Meta */
.error {
  color: var(--main-color, #ff0000); /* Use main color for error text */
  font-size: 13px;
  font-weight: 500;
}

.meta {
  margin-top: 16px;
  text-align: center;
  font-size: 14px;
  color: var(--muted);
}

.meta a {
  color: var(
    --second-color,
    #780116
  ); /* Use the darker second color for links */
  text-decoration: none;
  font-weight: 700;
}

/* Animation */
@keyframes enter {
  from {
    transform: translateY(10px);
    opacity: 0;
  }

  to {
    transform: none;
    opacity: 1;
  }
}

/* Responsive */
@media (max-width: 650px) {
  .register-form-card {
    padding: 24px;
    margin: 0; /* Remove top margin on small screens */
    border-radius: 0;
    box-shadow: none; /* Remove shadow for full-screen mobile look */
    max-width: 100%;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  h2 {
    margin-top: 20px;
  }
}
</style>
