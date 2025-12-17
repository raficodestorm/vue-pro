<template>
  <nav class="navbar sticky-top" :id="headerId">
    <div class="container-fluid d-flex align-items-center justify-content-between">
      <!-- Search Form -->
      <form class="d-flex" role="search" @submit.prevent="handleSearch">
        <input
          v-model="searchQuery"
          class=" bg-none form-control "
          type="search"
          placeholder="Search"
          aria-label="Search"
        />
        <button class="btn bg-none" type="submit">Search</button>
      </form>

      <!-- Panel Name -->
      <div class="col-auto ms-auto">
        <h4 class="panel">{{ userRole }} panel</h4>
      </div>

      <!-- Notification + Profile -->
      <div class="col-auto ms-auto d-flex align-items-center gap-3">
        <!-- Notification Icon -->
        <div class="position-relative">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="28px"
            width="28px"
            :fill="notifications.value ? '#ff0000' : '#6c757d'"
            class="notification-icon"
            @click="handleNotificationClick"
            role="button"
            aria-label="Notifications"
          >
            <path
              d="M80-560q0-100 44.5-183.5T244-882l47 64q-60 44-95.5 111T160-560H80Zm720 0q0-80-35.5-147T669-818l47-64q75 55 119.5 138.5T880-560h-80ZM160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80ZM320-280h320v-280q0-66-47-113t-113-47q-66 0-113 47t-47 113v280Z"
            />
          </svg>
          <span
            v-if="notifications.value > 0"
            class="badge bg-danger rounded-pill notification-badge"
          >{{ notifications.value }}</span>
        </div>
          <p class="names">{{ user?.fullname }}</p>

        <!-- Profile Dropdown -->
        <div class="dropdown" ref="dropdownRef">
          <button
            class="toggles d-flex align-items-center"
            @click.stop="toggleDropdown"
            :aria-expanded="dropdownOpen"
            aria-haspopup="true"
          >
            <img :src="profilePhoto" alt="User" class="profile-img" />
            
            <svg
              class="arrow"
              xmlns="http://www.w3.org/2000/svg"
              width="16"
              height="16"
              viewBox="0 0 24 24"
            >
              <path d="M7 10l5 5 5-5z" />
            </svg>
          </button>

          <transition name="fade-scale">
            <ul
              v-if="dropdownOpen"
              class="dropdown-menu dropdown-menu-end show"
              @click.stop
            >
              <li><a class="dropdown-item" href="#">Profile</a></li>
              <li><a class="dropdown-item" href="#">Dashboard</a></li>
              <li><a class="dropdown-item" href="#">Edit Profile</a></li>
              <li><hr class="dropdown-divider" /></li>
              <li>
                <button @click="logout" class="dropdown-item text-danger">
                  Logout
                </button>
              </li>
            </ul>
          </transition>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch, computed } from "vue";
import { useRouter } from "vue-router";
import api from "../api/axios.js";

const user = ref(null);
const userRole = ref("");
const profilePhoto = ref("/storage/user.png");
const notifications = ref(3);
const searchQuery = ref("");
const router = useRouter();

const dropdownOpen = ref(false);
const dropdownRef = ref(null);

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value;
};

// Close dropdown on outside click
const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    dropdownOpen.value = false;
  }
};

// Close dropdown on Escape key
const handleKeyDown = (e) => {
  if (e.key === "Escape") dropdownOpen.value = false;
};

// Close dropdown when route changes
watch(
  () => router.currentRoute.value.fullPath,
  () => { dropdownOpen.value = false; }
);

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
  document.addEventListener("keydown", handleKeyDown);
  const storedUser = localStorage.getItem("user");
  const storedRole = localStorage.getItem("role");

  if (storedUser) {
    user.value = JSON.parse(storedUser);
    userRole.value = storedRole ?? "";

    if (user.value.profile_photo_path) {
      profilePhoto.value = `/storage/${user.value.profile_photo_path}`;
    }
  }
});
onBeforeUnmount(() => {
  document.removeEventListener("click", handleClickOutside);
  document.removeEventListener("keydown", handleKeyDown);
});

const handleSearch = () => {
  console.log("Searching for:", searchQuery.value);
};

const handleNotificationClick = () => {
  notifications.value = 0;
};

const headerId = computed(() => {
  switch (userRole.value) {
    case "admin":
      return "header-admin";
    case "counter_manager":
      return "header-manager";
    case "controller":
      return "header-controller";
    default:
      return "";
  }
});

const logout = async () => {
  try {
    await api.post("/logout");

    // Clear storage
    localStorage.removeItem("user");
    localStorage.removeItem("role");
    localStorage.removeItem("auth_token");

    // Remove Authorization header
    delete api.defaults.headers.common["Authorization"];

    router.push("/");
  } catch (err) {
    console.error("Logout failed:", err);
  }
};




</script>

<style scoped>
h1, h2, h3, h4, h5 {
  font-family: "El Messiri", sans-serif;
  font-weight: 800;
}
.names{color: #000; margin-bottom: 0;}
/* Common header style */
#header,
#header-admin,
#header-manager,
#header-controller {
  border-bottom: 0.5px solid var(--main-color);
}

/* ADMIN */
#header-admin {
  background-color: var(--bg-color);
}

/* MANAGER */
#header-manager {
  background-color: #ffcbdd;
}

/* CONTROLLER */
#header-controller {
  background-color: #780116;
}

.form-controll{
  background-color: none !important;
}
.panel {
  margin-bottom: 0;
  color: #ff8989e0;
}

.notification-icon {
  cursor: pointer;
  transition: transform 0.15s ease, fill 0.15s ease;
}
.notification-icon:hover {
  transform: scale(1.08);
}

.notification-badge {
  position: absolute;
  top: -6px;
  right: -8px;
  font-size: 12px;
  padding: 3px 6px;
  background-color: var(--main-color);
  color: white;
  border-radius: 50%;
  font-weight: bold;
}



.profile-img {
  width: 40px;
  height: 40px;
  object-fit: cover;
  border-radius: 50%;
  border: 2px solid var(--main-color);
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.profile-img:hover {
  transform: scale(1.03);
  box-shadow: 0 0 8px rgba(255, 0, 0, 0.25);
}

.arrow {
  margin-left: 6px;
  transition: transform 0.2s ease;
}
.toggles[aria-expanded="true"] .arrow {
  transform: rotate(180deg);
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  right: 0;
  z-index: 1050;
  min-width: 180px;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  background: white;
  padding: 6px 0;
}

.fade-scale-enter-active,
.fade-scale-leave-active {
  transition: all 150ms ease;
}
.fade-scale-enter-from {
  opacity: 0;
  transform: translateY(-6px) scale(0.97);
}
.fade-scale-enter-to {
  opacity: 1;
  transform: translateY(0) scale(1);
}
.fade-scale-leave-from {
  opacity: 1;
  transform: translateY(0) scale(1);
}
.fade-scale-leave-to {
  opacity: 0;
  transform: translateY(-6px) scale(0.97);
}

.dropdown-item:hover {
  background-color: #f0f0ff;
}

/* Profile dropdown toggle button */
.toggles {
  background: transparent; /* remove default button color */
  border: none;           /* remove default border */
  padding: 0;             /* no extra padding */
  cursor: pointer;
  display: flex;
  align-items: center;
}


</style>
