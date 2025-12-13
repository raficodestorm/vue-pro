<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "../api/axios"; // ← তোমার axios instance

const router = useRouter();

// ========== Auth State ==========
const user = ref(null);

onMounted(async () => {
  // try {
  //   const res = await axios.get("/");
  //   user.value = res.data;
  // } catch (e) {
  //   user.value = null;
  // }
});

// ========== Logout ==========
const logout = async () => {
  await api.post("/logout");
  localStorage.clear();
  router.push("/");
};


// ========== Login Modal ==========
const openLogin = () => {
  const event = new CustomEvent("open-login-modal");
  window.dispatchEvent(event);
};
</script>

<template>
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <RouterLink class="navbar-brand" to="/">
        <span>RunStar</span>
      </RouterLink>

      <button
        class="navbar-toggler text-light border-0"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-lg-center">
          <li class="nav-item">
            <router-link class="nav-link" to="/">Home</router-link>
          </li>

          <li class="nav-item">
            <router-link class="nav-link" to="/book-ticket"
              >Book Ticket</router-link
            >
          </li>

          <!-- Services Dropdown -->
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="servicesMenu"
              role="button"
              data-bs-toggle="dropdown"
            >
              Services
            </a>

            <ul class="dropdown-menu">
              <li>
                <router-link class="dropdown-item" to="/routes"
                  >Bus Routes</router-link
                >
              </li>
              <li>
                <router-link class="dropdown-item" to="/offers"
                  >Offers</router-link
                >
              </li>

              <!-- Submenu -->
              <li class="dropdown-submenu">
                <a class="dropdown-item dropdown-toggle" href="#">Support</a>
                <ul class="dropdown-menu">
                  <li>
                    <router-link class="dropdown-item" to="/contact"
                      >Contact Us</router-link
                    >
                  </li>
                  <li>
                    <router-link class="dropdown-item" to="/faq"
                      >FAQ</router-link
                    >
                  </li>
                  <li>
                    <router-link class="dropdown-item" to="/live-chat"
                      >Live Chat</router-link
                    >
                  </li>
                </ul>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <router-link class="nav-link" to="/my-bookings"
              >My Bookings</router-link
            >
          </li>

          <!-- Authenticated User -->
          <li v-if="user" class="nav-item dropdown user-dropdown ms-lg-3">
            <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown">
              <img
                :src="user.profile_photo_url"
                style="
                  border-radius: 50%;
                  border: 1px solid gray;
                  width: 35px;
                  height: 35px;
                "
              />
              {{ user.fullname }}
            </a>

            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <router-link class="dropdown-item" to="/profile"
                  >My Profile</router-link
                >
              </li>
              <li>
                <router-link class="dropdown-item" to="/profile/edit"
                  >Edit Profile</router-link
                >
              </li>
              <li>
                <router-link class="dropdown-item" to="/dashboard"
                  >Dashboard</router-link
                >
              </li>
              <li><hr class="dropdown-divider" /></li>
              <li>
                <button @click="logout" class="dropdown-item text-danger">
                  Logout
                </button>
              </li>
            </ul>
          </li>

          <!-- Guest User -->
          <li v-else class="nav-item dropdown user-dropdown ms-lg-3">
            <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown">
              <img
                src="/svg/user.svg"
                style="
                  border-radius: 50%;
                  border: 1px solid gray;
                  width: 35px;
                  height: 35px;
                "
              />
              User
            </a>

            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <router-link class="dropdown-item" :to="{ name: 'Register' }">
                  Registration
                </router-link>
              </li>
              <li><hr class="dropdown-divider" /></li>
              <li>
                <a
                  class="dropdown-item text-danger"
                  href="javascript:void(0)"
                  @click="openLogin"
                >
                  Login
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<style scoped>
/* =================== google-font-Roboto & Ek-mesirri =================== */
@import url("https://fonts.googleapis.com/css2?family=El+Messiri:wght@400..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap");
/* font-family: "Roboto", sans-serif; */
/* font-family: "El Messiri", sans-serif; */
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
  --max-width: 1200px;
  --text: #e6eef6;
  --glass: rgba(255, 255, 255, 0.02);
}

.container-fluid {
  --bs-gutter-x: 0 !important;
}
h1,
h2,
h3,
h4,
h5 {
  font-family: "El Messiri", sans-serif;
  font-weight: 800;
  color: var(--second-color);
}
/* ---------------------------------- navbar style ----------------------------------------- */
.navbar {
  background-color: #fffffccb;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  padding: 0;
}

.navbar-brand {
  font-weight: 700;
  padding: 0;
  color: var(--section-bg-color);
  font-size: 2.1rem;
  letter-spacing: 1px;
}

.navbar-brand span {
  font-family: "El Messiri", sans-serif;
  color: var(--second-color);
  padding: 0;
  border-radius: 8px;
  position: relative;
  display: inline-block;
  background: linear-gradient(90deg, #800000, #ff5f15, #800000);
  background-size: 200%;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: glowShift 3s ease-in-out infinite;
  transition: transform 0.4s ease, text-shadow 0.4s ease;
}

/* subtle glowing animation */
@keyframes glowShift {
  0%,
  100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

.navbar-brand span:hover {
  transform: scale(1.05);
  animation: glowShift 0.8s ease-in-out infinite;
}

.navbar-nav .nav-link {
  color: var(--section-bg-color);
  font-weight: 500;
  margin: 0 10px;
  transition: all 0.3s ease;
  border-radius: 6px;
}

.navbar-nav .nav-link:hover {
  background: var(--section-bg-color);
  color: white;
  font-weight: 600;
}

/* Dropdowns */
.dropdown-menu {
  border: none;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
  border-radius: 10px;
  background-color: #fff;
  animation: dropdownFade 0.3s ease;
}

@keyframes dropdownFade {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.dropdown-item {
  color: var(--second-color);
  font-weight: 500;
  transition: 0.3s;
}

.dropdown-item:hover {
  background-color: var(--main-color);
  color: #fff;
  border-radius: 6px;
}

/* Submenu Style */
.dropdown-submenu {
  position: relative;
}

.dropdown-submenu .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -5px;
  display: none;
  border-radius: 10px;
}

.dropdown-submenu:hover > .dropdown-menu {
  display: block;
}

/* User Dropdown */
.user-dropdown img {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 8px;
  border: 2px solid var(--section-bg-color);
}

.user-dropdown .dropdown-toggle {
  color: var(--section-bg-color);
  font-weight: 500;
  display: flex;
  align-items: center;
  text-decoration: none;
  transition: 0.3s;
}

.user-dropdown .dropdown-toggle:hover {
  color: var(--section-bg-color);
  opacity: 0.9;
}

@media (max-width: 992px) {
  .navbar-nav .nav-link {
    text-align: center;
    margin: 6px 0;
  }
  .user-dropdown {
    justify-content: center;
  }
  .dropdown-submenu .dropdown-menu {
    position: static;
    margin-left: 1rem;
    display: none;
  }
  .dropdown-submenu.show > .dropdown-menu {
    display: block;
  }
}
</style>
