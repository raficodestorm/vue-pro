<template>
  <nav :class="['sidebar', { close: isClosed }]">
    <ul>

      <!-- Logo + toggle -->
      <li class="first">
        <span>
          <RouterLink class="nav-logo" to="/counter">
            RunStar
          </RouterLink>
        </span>

        <button class="toggle-btn" @click="toggleSidebar">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="26px"
            viewBox="0 -960 960 960"
            width="26px"
            fill="#220901"
          >
            <path
              d="M440-240 200-480l240-240 56 56-183 184 183 184-56 56Zm264 0L464-480l240-240 56 56-183 184 183 184-56 56Z"
            />
          </svg>
        </button>
      </li>

      <!-- Menu Items -->
      <li
        v-for="item in menuItems"
        :key="item.title"
        :class="{ dropdown: item.submenu }"
      >
        <!-- Single Page Link -->
        <RouterLink
          v-if="!item.submenu"
          :to="item.route"
          :title="item.title"
          class="single-link"
        >
          <img :src="getIcon(item.icon)" :alt="item.title" />
          <span>{{ item.title }}</span>
        </RouterLink>

        <!-- Dropdown Menu -->
        <div v-else>
          <a href="#" @click.prevent="toggleSubmenu(item)" :title="item.title">
            <img :src="getIcon(item.icon)" />
            <span>{{ item.title }}</span>

            <svg
              class="arrow"
              xmlns="http://www.w3.org/2000/svg"
              height="24px"
              viewBox="0 -960 960 960"
              width="24px"
              fill="#0000F5"
            >
              <path d="M480-360 280-560h400L480-360Z" />
            </svg>
          </a>

          <ul class="submenu" :class="{ showmenu: item.showSubmenu }">
            <div>
              <li v-for="sub in item.submenu" :key="sub.title">
                <RouterLink :to="sub.route" :title="sub.title">
                  <img :src="getIcon(sub.icon)" />
                  <span class="mentext">{{ sub.title }}</span>
                </RouterLink>
              </li>
            </div>
          </ul>
        </div>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import { ref } from "vue";
import { RouterLink } from "vue-router";

const isClosed = ref(false);

const toggleSidebar = () => {
  isClosed.value = !isClosed.value;
};

/* Load SVG Icons (Vite Safe) */
const getIcon = (name) => {
  return `/svg/${name}.svg`;
};

/* Sidebar Menu Items */
const menuItems = ref([
  { title: "Dashboard", icon: "dashboard", route: "/counter" },

  {
    title: "Bus type",
    icon: "addbus",
    submenu: [
      { title: "Add Bustype", icon: "bus", route: "/counter" },
      { title: "All Bustypes", icon: "list", route: "/counter" },
    ],
    showSubmenu: false,
  },

  {
    title: "Buses",
    icon: "bus",
    submenu: [
      { title: "Add New Bus", icon: "addbus", route: "/counter" },
      { title: "All Buses", icon: "list", route: "/counter" },
    ],
    showSubmenu: false,
  },

  {
    title: "Schedule",
    icon: "schedule",
    submenu: [
      { title: "Create schedule", icon: "addschedule", route: "/counter" },
      { title: "All schedules", icon: "list", route: "/counter" },
    ],
    showSubmenu: false,
  },

  {
    title: "Trips",
    icon: "trip",
    submenu: [
      { title: "Pending Trips", icon: "pending", route: "/counter" },
      { title: "Running Trips", icon: "running", route: "/counter" },
      { title: "Finished Trips", icon: "finish", route: "/counter" },
    ],
    showSubmenu: false,
  },

  { title: "Bookings", icon: "booking", route: "/counter" },

  {
    title: "location",
    icon: "location",
    submenu: [
      { title: "Add location", icon: "addlocation", route: "/counter" },
      { title: "All locations", icon: "list", route: "/counter" },
    ],
    showSubmenu: false,
  },

  {
    title: "Route",
    icon: "route",
    submenu: [
      { title: "Add Route", icon: "addroute", route: "/counter" },
      { title: "All Routes", icon: "list", route: "/counter" },
    ],
    showSubmenu: false,
  },


]);

/* Toggle submenu open/close */
const toggleSubmenu = (item) => {
  item.showSubmenu = !item.showSubmenu;
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
/* =================== Sidebar style start =================== */
.sidebar {
  min-height: 100vh;
  width: 220px;
  background-color: var(--bg-color);
  padding: 5px 0.5rem;
  /* border-right: 0.5px solid var(--main-color); */
  position: sticky !important;
  top: 0;
  z-index: 2;
  align-self: self-start;
  overflow: hidden;
  transition: 0.2s ease-in-out;
}
i {
  font-size: large;
  color: var(--second-color);
}
.sidebar ul {
  list-style: none;
  padding-left: 0;
}
.first {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 25px;
}

.nav-logo {
  font-family: "El Messiri", sans-serif;
  font-weight: 900;
  font-size: 2rem;
  letter-spacing: 1px;
  position: relative;
  display: inline-block;
  background: linear-gradient(90deg, #ff0000, #fae103, #ff0000);
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

.nav-logo:hover {
  transform: scale(1.05);
  animation: glowShift 0.8s ease-in-out infinite;
}

.sidebar a,
.sidebar .nav-logo {
  border-bottom-right-radius: 1.5rem;
  border-top-right-radius: 1.5rem;
  padding: 0.85rem;
  color: var(--text-color);
  display: flex;
  align-items: center;
  gap: 1rem;
  text-decoration: none;
  transition: 0.2s ease-in-out;
}
.sidebar a span,
.sidebar .dropdown span {
  flex-grow: 1;
}
.sidebar a:hover {
  background-color: var(--main-color);
  border-left: 3px solid var(--text-color);
}
.sidebar .submenu {
  display: grid;
  grid-template-rows: 0fr;
  transition: 300ms ease-in-out;
  > div {
    overflow: hidden;
  }
}
.submenu a {
  padding-left: 1.8rem;
  font-size: 0.8rem;
}
.arrow {
  transition: transform 0.3s ease;
}

.toggle-btn {
  border: none;
  padding: 0px 12px;
  border-radius: 50%;
  background: none;
  margin-left: auto;
  > svg {
    transition: rotate 150ms ease;
  }
}
.toggle-btn:hover {
  background-color: var(--light-hover);
}

/* ----------- js class start ----------- */
.close {
  width: 60px;
  padding: 5px;
}
.sidebar .submenu.showmenu {
  grid-template-rows: 1fr;
}
.rotate {
  transform: rotate(180deg);
}
.subpadding {
  padding-left: 22px !important;
}
/* ------------- js class end ------------ */
.sidebar.close a span {
  display: none;
}
.sidebar.close a .arrow {
  display: none;
}
.sidebar.close a {
  justify-content: center;
  gap: 0;
}

@media (max-width: 800px) {
  body {
    grid-template-columns: 1fr;
  }

  main {
    padding: 2rem 1rem 60px 1rem; /* fixed selector */
  }

  .container {
    padding: 0;
  }

  .sidebar {
    height: 60px;
    width: 100%;
    border-right: none;
    border-top: 0.5px solid var(--main-color);
    position: fixed;
    bottom: 0;
    top: unset;
  }

  .sidebar ul {
    padding: 0;
    display: grid;
    grid-auto-columns: 60px;
    grid-auto-flow: column;
    align-items: center;
    overflow-x: auto;
  }

  .sidebar ul li span,
  .sidebar ul li:first-child,
  .sidebar #drop svg:last-child {
    display: none;
  }
  span{
    text-align: start !important;
  }
  .sidebar ul li {
    height: 60px;
    width: 60px; /* fixed */
    padding: 0;
    justify-content: center;
  }
  .sidebar a {
    padding: 1.1rem;
  }
  .sidebar a:hover {
    background-color: var(--main-color);
    border-radius: 50%;
    border-left: none;
  }

  .dropdown .submenu.showmenu {
    position: fixed;
    border-radius: 0; /* fixed */
    height: 60px;
    width: 100%;
    bottom: 60px;
    left: 0;
    box-sizing: border-box;
    display: flex;
    justify-content: center;
    background-color: var(--light-hover);
  }

  .dropdown .submenu.showmenu > div {
    overflow-x: auto;
  }

  .dropdown .submenu.showmenu li {
    display: inline-flex;
    padding: 0 1.5rem;
  }

  .dropdown .submenu.showmenu a {
    box-sizing: border-box;
    padding: 1rem;
    width: auto;
    justify-content: center;
  }
  .dropdown .submenu.showmenu a:hover {
    background-color: yellow;
  }
  .dropdown .submenu.showmenu a svg {
    display: none;
  }

  /* Ensure text is visible */
  .dropdown .submenu.showmenu a span {
    display: inline;
  }
}
</style>
