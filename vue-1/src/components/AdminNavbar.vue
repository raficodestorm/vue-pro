<template>
  <nav class="navbar sticky-top" id="header">
    <div
      class="container-fluid d-flex align-items-center justify-content-between"
    >
      <!-- Search Form -->
      <form class="d-flex" role="search" @submit.prevent="handleSearch">
        <input
          v-model="searchQuery"
          class="form-control"
          type="search"
          placeholder="Search"
          aria-label="Search"
        />
        <button class="btn bg-white" type="submit">Search</button>
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
            viewBox="0 -960 960 960"
            width="28px"
            fill="#ff0000"
            class="notification-icon"
            @click="handleNotificationClick"
          >
            <path
              d="M80-560q0-100 44.5-183.5T244-882l47 64q-60 44-95.5 111T160-560H80Zm720 0q0-80-35.5-147T669-818l47-64q75 55 119.5 138.5T880-560h-80ZM160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80ZM320-280h320v-280q0-66-47-113t-113-47q-66 0-113 47t-47 113v280Z"
            />
          </svg>
          <span class="badge bg-danger rounded-pill notification-badge">{{
            notifications
          }}</span>
        </div>

        <!-- Profile Dropdown -->
        <div class="dropdown" ref="dropdownRef">
          <a
            href="#"
            class="d-flex align-items-center text-decoration-none dropdown-toggle"
            @click.prevent="toggleDropdown"
          >
            <img :src="profilePhoto" alt="User" class="profile-img" />
          </a>
          <ul class="dropdown-menu dropdown-menu-end" v-show="dropdownOpen">
            <li>
              <a class="dropdown-item"
                >Profile</a
              >
            </li>
            <li>
              <a class="dropdown-item" 
                >Dashboard</a
              >
            </li>
            <li>
              <a class="dropdown-item" 
                >Edit Profile</a
              >
            </li>
            <li><hr class="dropdown-divider" /></li>
            <li>
              <button @click="logout" class="dropdown-item text-danger">
                Logout
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { useRouter } from "vue-router";

// Dummy user data for Vue (replace with actual API/user store)
const userRole = ref("Admin");
const profilePhoto = ref("/storage/user.png"); // Replace with actual dynamic photo
const notifications = ref(3);

const searchQuery = ref("");
const router = useRouter();

// Profile dropdown state
const dropdownOpen = ref(false);
const dropdownRef = ref(null);

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value;
};

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    dropdownOpen.value = false;
  }
};

onMounted(() => document.addEventListener("click", handleClickOutside));
onBeforeUnmount(() =>
  document.removeEventListener("click", handleClickOutside)
);

// Search function
const handleSearch = () => {
  console.log("Searching for:", searchQuery.value);
  // Example: router.push({ name: 'search', query: { q: searchQuery.value } })
};

// Notification click
const handleNotificationClick = () => {
  console.log("Notification clicked");
  notifications.value = 0; // Example: mark as read
};

// Logout function (simulate POST request)
const logout = () => {
  console.log("Logging out...");
  // replace with actual logout logic, e.g., axios.post('/logout')
  router.push("/login");
};
</script>

<style scoped>
h1,
h2,
h3,
h4,
h5 {
  font-family: "El Messiri", sans-serif;
  font-weight: 800;
}

#header {
  border-bottom: 0.5px solid var(--main-color);
  background-color: var(--bg-color);
}

/* Panel text */
.panel {
  margin-bottom: 0;
  color: #ff8989e0;
}

/* Notification Icon */
.notification-icon {
  cursor: pointer;
  transition: transform 0.2s ease, fill 0.2s ease;
}
.notification-icon:hover {
  transform: scale(1.1);
  fill: #0056b3;
}

/* Notification Badge */
.notification-badge {
  position: absolute;
  top: -6px;
  right: -8px;
  font-size: 12px;
  padding: 3px 6px;
  background-color: #dc3545;
  color: white;
  border-radius: 50%;
  font-weight: bold;
}

/* Profile Image */
.profile-img {
  width: 40px;
  height: 40px;
  object-fit: cover;
  border-radius: 50%;
  border: 2px solid #ff0000;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.profile-img:hover {
  transform: scale(1.05);
  box-shadow: 0 0 8px rgba(255, 0, 0, 0.4);
}

/* Dropdown Menu */
.dropdown-menu {
  min-width: 180px;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
.dropdown-item:hover {
  background-color: #f0f0ff;
}
</style>
