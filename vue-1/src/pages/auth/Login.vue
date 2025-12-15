<template>
  <div class="login-main">
    <div class="login-form-card enter" aria-labelledby="login-title">

      <!-- Header -->
      <div class="header-box">
        <div>
          <h3 id="login-title">Welcome back</h3>
          <p class="lead">Sign in to continue to your Runstar dashboard</p>
        </div>

        <div class="create-box">
          <small class="note">Not a member?</small>
          <RouterLink to="/register" class="note link-main">Create account</RouterLink>
        </div>
      </div>

      <!-- General Error -->
      <div v-if="generalError" class="alert-error">
        {{ generalError }}
      </div>

      <!-- Login Form -->
      <form @submit.prevent="submitLogin" id="loginForm">

        <!-- Username -->
        <div class="field">
          <label class="label-log">Username or Email</label>
          <input
            type="text"
            class="input-log"
            v-model="form.username"
            placeholder="user@example.com"
            :class="{ 'has-error': errors.username }"
            required
          />
          <p v-if="errors.username" class="error-text">{{ errors.username }}</p>
        </div>

        <!-- Password -->
        <div class="field">
          <label class="label-log">Password</label>
          <div class="password-box">
            <input
              class="input-log pwd-input"
              :type="showPassword ? 'text' : 'password'"
              v-model="form.password"
              placeholder="••••••••"
              :class="{ 'has-error': errors.password }"
              required
            />
            <button
              type="button"
              class="eye-btn"
              @click="togglePassword"
            >
              {{ showPassword ? "🙈" : "👁️" }}
            </button>
          </div>
          <p v-if="errors.password" class="error-text">{{ errors.password }}</p>
        </div>

        <!-- Actions -->
        <div class="actions">
          <label class="remember label-log">
            <input type="checkbox" v-model="form.remember" />
            <span class="chk-text">Remember me</span>
          </label>

          <RouterLink class="note link-forgot" to="/forgot-password">
            Forgot password?
          </RouterLink>
        </div>

        <!-- Submit Button -->
        <button :disabled="loading" class="btn btn-primary" type="submit">
          <span v-if="!loading">Sign in</span>
          <span v-else>Signing in...</span>
        </button>
      </form>
    </div>
  </div>
</template>


<script setup>
import { reactive, ref } from "vue";
import api from "../../api/axios.js";
import { useRouter } from "vue-router";

const router = useRouter();

const loading = ref(false);
const showPassword = ref(false);
const generalError = ref(null);

const form = reactive({
  username: "",
  password: "",
  remember: false,
});

const errors = reactive({
  username: null,
  password: null,
});

function togglePassword() {
  showPassword.value = !showPassword.value;
}

async function submitLogin() {
  loading.value = true;
  generalError.value = null;
  errors.username = null;
  errors.password = null;

  try {
    if (!form.username || !form.password) {
      generalError.value = "Please fill in all fields.";
      loading.value = false;
      return;
    }

    const res = await api.post("/login", {
  username: form.username,
  password: form.password,
});

const { user, role, token } = res.data;

// Store
localStorage.setItem("user", JSON.stringify(user));
localStorage.setItem("role", role);
localStorage.setItem("auth_token", token);

// Set token for all future requests
api.defaults.headers.common["Authorization"] = `Bearer ${token}`;

// 🔥 CLOSE LOGIN MODAL
window.dispatchEvent(new CustomEvent("close-login-modal"));

    // Redirect Based on Role
    switch (role) {
      case "admin":
        router.push("/admin");
        break;
      case "controller":
        router.push("/controller/dashboard");
        break;
      case "manager":
        router.push("/manager/dashboard");
        break;
      default:
        router.push("/user/dashboard");
    }

  } catch (err) {
    if (err.response?.status === 422 && err.response?.data?.errors) {
      Object.assign(errors, err.response.data.errors);
    } 
    else if (err.response?.status === 401) {
      generalError.value = "Invalid username or password.";
    } 
    else {
      generalError.value = "Something went wrong. Please try again.";
    }
  } finally {
    loading.value = false;
  }
}
</script>

  
<style scoped>
/* ASSUMPTION: You have the following variables in your global CSS:
   --main-color, --second-color, --muted, --radius-lg, --card-shadow
*/

.login-main {
  width: 100%;
  max-height: 90vh; /* Changed to min-height for safety */
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #f8f9fa; /* Added a subtle background for the page */
  padding: 15px; /* Prevent card touching edges on mobile */
  box-sizing: border-box;
}

/* Card */
.login-form-card {
  background: white;
  border-radius: var(--radius-lg, 16px); /* Added fallback */
  padding: 20px;
  box-shadow: var(--card-shadow, 0 10px 40px rgba(0, 0, 0, 0.08));
  width: 100%;
  max-width: 500px; /* Better than width: 40% */
}
#login-title {
  text-align: start;
  color: #ff6d00;
}
.lead {
  text-align: start;
}

.header-box {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 20px;
}

.create-box {
  text-align: right;
  font-size: 10px;
  margin-top: 50px;
}

.link-main {
  font-weight: 700;
  color: var(--main-color, #ff6d00);
  text-decoration: none;
}

/* Fields */
#loginForm {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.field {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.label-log {
  font-size: 13px;
  font-weight: 500;
  color: var(--muted, #666);
}

.input-log {
  width: 100%; /* Fixes width issue */
  height: 40px;
  border-radius: 10px;
  border: 1px solid #e0e0e0; /* Neutral start */
  padding: 0 14px; /* Removed vertical padding, let height handle it */
  font-size: 15px;
  outline: none;
  transition: all 0.2s;
  background: #fff;
  box-sizing: border-box; /* Critical */
  color: black;
}

.input-log:focus {
  /* Using your Main Color for focus */
  border-color: var(--main-color, #ff6d00);
  box-shadow: 0 0 0 3px rgba(255, 109, 0, 0.15);
}

.input-log.has-error {
  border-color: #dc3545;
  background-color: #fff8f8;
}

/* Password Box */
.password-box {
  position: relative;
  width: 100%;
  display: flex;
  align-items: center;
}

.pwd-input {
  padding-right: 40px; /* Prevents text hitting the eye icon */
}

.eye-btn {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 4px;
  font-size: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Actions */
.actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: -4px;
}

.remember {
  display: flex;
  align-items: center;
  gap: 6px;
  cursor: pointer;
}

.link-forgot {
  font-size: 13px;
  color: var(--muted, #666);
  text-decoration: none;
}

.link-forgot:hover {
  text-decoration: underline;
}

.btn-primary {
  /* Fallback colors included */
  background: linear-gradient(
    90deg,
    var(--main-color, #ff6d00),
    var(--second-color, #ff9e40)
  );
  color: white;
  height: 40px;
  border-radius: 12px;
  border: none;
  font-weight: 600;
  cursor: pointer;
  transition: opacity 0.2s;
  font-size: 16px;
}

.btn-primary:hover {
  opacity: 0.9;
}

.btn-primary:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.btn-outline {
  border: 1px solid #e0e0e0;
  background: transparent;
  height: 40px;
  border-radius: 12px;
  font-weight: 500;
  color: #333;
  cursor: pointer;
  flex: 1; /* Makes buttons even width */
  transition: background 0.2s;
}

.btn-outline:hover {
  background: #f8f9fa;
}

.divider-log {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 4px 0;
  color: var(--muted, #888);
}

.divider-log span {
  flex: 1;
  height: 1px;
  background: #eee;
}

.socials-log {
  display: flex;
  gap: 12px;
}

.meta {
  display: flex;
  justify-content: space-between;
  margin-top: 10px;
  font-size: 12px;
  color: var(--muted, #888);
}

.meta a {
  color: inherit;
  text-decoration: underline;
}

/* Messages */
.error-text {
  font-size: 12px;
  color: #dc3545;
  margin-top: 2px;
}

.alert-error {
  background-color: #fee2e2;
  color: #dc2626;
  padding: 12px;
  border-radius: 8px;
  font-size: 14px;
  margin-bottom: 16px;
  text-align: center;
}

/* Animation */
.enter {
  animation: enter 0.6s cubic-bezier(0.2, 0.9, 0.2, 1) both;
}

@keyframes enter {
  from {
    transform: translateY(10px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

/* Responsive */
@media (max-width: 480px) {
  .login-main {
    padding: 0;
    align-items: flex-start;
    background: white; /* On mobile full screen, make it white */
  }

  .login-form-card {
    box-shadow: none;
    border-radius: 0;
    padding: 24px;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .actions {
    flex-direction: row; /* Keep row even on mobile usually */
  }
}
</style>
