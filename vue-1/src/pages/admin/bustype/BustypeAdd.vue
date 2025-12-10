<script setup>
    import { ref } from "vue";
    import axios from '../../../api/axios.js';
    import { useRouter } from "vue-router";
    
    const type = ref("");
    const loading = ref(false);
    const message = ref("");
    const errors = ref({});

    
    const saveType = async () => {
        loading.value = true;
      message.value = "";
    
      try {
        const response = await axios.post("/bustypes", {
          type: type.value,
        });
    
        message.value = "Bustype added successfully!";
        
        // Reset form
        type.value = "";
    
      } catch (error) {
        message.value = "Error: Unable to add Bustype.";
        console.error(error);
      } finally {
        loading.value = false;
      }
    };
    </script>
    
    <template>
      <div class="bus-container">
        <!-- Status Message -->
        <small v-if="errors?.type" class="text-danger fw-semibold">
          {{ errors.type[0] }}
        </small>

    
        <!-- Header Section -->
        <div class="header-bar">
          <h2>Create Bus Type</h2>
    
          <router-link to="/admin/bustype/index" class="back-btn">
            All Bustypes
          </router-link>
        </div>
    
        <!-- Main Card -->
        <div class="card-box shadow">
    
          <form @submit.prevent="saveType">
    
            <div class="form-group mb-3">
              <label class="form-label">Bus Type</label>
              <input
                type="text"
                v-model="type"
                class="form-control custom-input"
                placeholder="Enter bus type"
              />
    
              <small v-if="errors.type" class="text-danger fw-semibold">
                {{ errors.type[0] }}
              </small>
            </div>
    
            <button class="submit-btn" :disabled="loading">
              {{ loading ? "Saving..." : "Save"}}</button>
          </form>
    
        </div>
    
      </div>
    </template>
    
    <style scoped>
    /* Root colors from your theme */
    :root {
      --main-color: #ff0000;
      --second-color: #780116;
      --bg-color: #fffffc;
      --back-color: #edf6f9af;
      --text-color: #220901;
      --light-hover: #ff0101ca;
    }
    
    .bus-container {
      background: var(--back-color);
      min-height: 100vh;
      padding: 25px;
    }
    
    /* Header */
    .header-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }
    
    .header-bar h2 {
      font-weight: 700;
      color: var(--second-color);
    }
    
    /* Back Button */
    .back-btn {
      padding: 8px 18px;
      background: var(--second-color);
      color: var(--bg-color);
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      transition: 0.3s;
    }
    
    .back-btn:hover {
      background: var(--main-color);
    }
    
    /* Card Box */
    .card-box {
      padding: 25px;
      background: var(--bg-color);
      border-radius: 12px;
      border: 1px solid #dddddd;
    }
    
    /* Inputs */
    .custom-input {
      border: 1px solid #cccccc;
      padding: 10px 12px;
      border-radius: 6px;
      transition: 0.2s;
      color: var(--text-color);
    }
    
    .custom-input:focus {
      border-color: var(--main-color);
      box-shadow: 0 0 0 0.15rem rgba(255, 0, 0, 0.25);
    }
    
    /* Submit Button */
    .submit-btn {
      background: var(--main-color);
      color: var(--bg-color);
      border: none;
      padding: 10px 18px;
      border-radius: 6px;
      font-weight: 600;
      transition: 0.3s;
    }
    
    .submit-btn:hover {
      background: var(--light-hover);
    }
    </style>
    