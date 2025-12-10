<template>
  <div class="login-main">
    <div class="login-form-card enter" aria-labelledby="login-title">
      <div class="header-box">
        <div>
          <h3 id="login-title">Welcome back</h3>
          <p class="lead">Sign in to continue to your Runstar dashboard</p>
        </div>

        <div class="create-box">
          <small class="note">Not a member?</small>
          <div>
            <RouterLink to="/register" class="note link-main">
              Create account
            </RouterLink>
          </div>
        </div>
      </div>

      <div v-if="generalError" class="alert-error">
        {{ generalError }}
      </div>

      <form @submit.prevent="submitLogin" id="loginForm">
        <div class="field">
          <label class="label-log">Username or Email</label>
          <input
            type="text"
            class="input-log"
            v-model="form.login"
            placeholder="user@example.com"
            :class="{ 'has-error': errors.login }"
            required
          />
          <p v-if="errors.login" class="error-text">{{ errors.login }}</p>
        </div>

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
              id="togglePwd"
              @click="togglePassword"
              :aria-label="showPassword ? 'Hide password' : 'Show password'"
              class="eye-btn"
            >
              {{ showPassword ? "🙈" : "👁️" }}
            </button>
          </div>
          <p v-if="errors.password" class="error-text">{{ errors.password }}</p>
        </div>

        <div class="actions">
          <label class="remember label-log">
            <input type="checkbox" v-model="form.remember" />
            <span class="chk-text">Remember me</span>
          </label>

          <RouterLink class="note link-forgot" to="/forgot-password">
            Forgot password?
          </RouterLink>
        </div>

        <button :disabled="loading" class="btn btn-primary" type="submit">
          <span v-if="!loading">Sign in</span>
          <span v-else>Signing in...</span>
        </button>

        <div class="divider-log">
          <span></span>
          <small>or continue with</small>
          <span></span>
        </div>

        <div class="socials-log">
          <button type="button" class="btn btn-outline">Google</button>
          <button type="button" class="btn btn-outline">Apple</button>
        </div>

        <div class="meta">
          <div class="note">
            By signing in you accept our <a href="#">Terms</a>
          </div>
          <div><a href="#" class="help-link">Need help?</a></div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
  import { reactive, ref } from "vue";
  import axios from "../../api/axios";
  import { useRouter } from "vue-router";
  
  const router = useRouter();
  
  const loading = ref(false);
  const showPassword = ref(false);
  const generalError = ref(null);
  
  const form = reactive({
    username: "", // changed from "login" to "username"
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
  
    errors.username = null;
    errors.password = null;
    generalError.value = null;
  
    try {
      if (!form.username || !form.password) {
        generalError.value = "Please fill in all fields.";
        loading.value = false;
        return;
      }
  
      // Laravel POST /api/login
      const res = await axios.post("/login", {
        username: form.username,
        password: form.password,
      });
  
      // Save returned user + role
      const user = res.data.user;
      const role = res.data.role;
  
      // Store for session
      localStorage.setItem("user", JSON.stringify(user));
      localLocalIgnore("role", role);
  
      // Optional: If in future you add token, store it
      if (res.data.token) {
        localStorage.setItem("auth_token", res.data.token);
      }
  
      // Redirect by role
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
      console.error(err);
  
      // Validation errors
      if (err.response?.status === 422 && err.response?.data?.errors) {
        Object.assign(errors, err.response.data.errors);
      }
      // Invalid credentials
      else if (err.response?.status === 401) {
        generalError.value = "Invalid username or password.";
      }
      // Other errors
      else {
        generalError.value =
          err.response?.data?.message ||
          "Something went wrong. Please try again.";
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
