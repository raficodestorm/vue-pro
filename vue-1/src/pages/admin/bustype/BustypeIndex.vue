<script setup>
    import { ref, onMounted } from "vue";
    import axios from '../../../api/axios.js';
    import { useRouter } from "vue-router";
    
    const router = useRouter();
    const types = ref([]);
    const loading = ref(true);
    
    // Fetch all bustypes
    const fetchTypes = async () => {
      try {
        const res = await axios.get("/bustypes");
        types.value = res.data.types;
      } catch (error) {
        console.error("Failed to load bustypes", error);
      }finally {
        loading.value = false;
      }
    };
    
    // Delete Bustype
    const deleteType = async (id) => {
      if (!confirm("Are you sure you want to delete this type?")) return;
    
      await axios.delete(`/bustypes/${id}`);
      fetchTypes();
    };
    
    onMounted(fetchTypes);
    </script>
    
    <template>
      <div class="buslist-container">
    
        <!-- Header -->
        <div class="header-bar">
          <h2>Bus Type List</h2>
    
          <router-link to="/admin/bustypes/create" class="add-btn">
            + Add New
          </router-link>
        </div>
    
        <!-- Table Card -->
        <div class="card-box shadow">
    
          <table class="table table-bordered table-hover align-middle custom-table">
    
            <thead>
              <tr>
                <th>#</th>
                <th>Bus Type</th>
                <th width="180">Actions</th>
              </tr>
            </thead>
    
            <tbody v-if="!loading">
              <tr v-for="(type, index) in types" :key="type.id">
                <td>{{ index + 1 }}</td>
                <td class="fw-semibold">{{ type.type }}</td>
    
                <td>
                  <div class="action-btns">
    
                    <button @click="deleteType(type.id)" class="delete-btn">
                      Delete
                    </button>
    
                  </div>
                </td>
              </tr>
            </tbody>
    
            <tbody v-else>
              <tr><td colspan="3" class="text-center py-3">Loading...</td></tr>
            </tbody>
    
          </table>
    
        </div>
      </div>
    </template>
    
    <style scoped>
    /* Root theme colors */
    :root {
      --main-color: #ff0000;
      --second-color: #780116;
      --bg-color: #fffffc;
      --back-color: #edf6f9af;
      --text-color: #220901;
      --light-hover: #ff0101ca;
    }
    
    .buslist-container {
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
    
    /* Add Button */
    .add-btn {
      padding: 8px 18px;
      background: var(--main-color);
      color: var(--bg-color);
      border-radius: 6px;
      font-weight: 600;
      text-decoration: none;
      transition: 0.3s;
    }
    
    .add-btn:hover {
      background: var(--light-hover);
    }
    
    /* Card Box */
    .card-box {
      background: var(--bg-color);
      padding: 20px;
      border-radius: 12px;
      border: 1px solid #ddd;
    }
    
    /* Table */
    .custom-table th {
      background: var(--second-color);
      color: var(--bg-color);
      font-weight: 600;
      font-size: 15px;
    }
    
    .custom-table td {
      color: var(--text-color);
      vertical-align: middle;
    }
    
    /* Actions Buttons */
    .action-btns {
      display: flex;
      gap: 10px;
    }
    
    .edit-btn {
      padding: 6px 14px;
      background: var(--second-color);
      color: var(--bg-color);
      border-radius: 5px;
      font-weight: 500;
      text-decoration: none;
      transition: 0.3s;
    }
    
    .edit-btn:hover {
      background: var(--main-color);
    }
    
    .delete-btn {
      padding: 6px 14px;
      background: var(--main-color);
      color: var(--bg-color);
      border: none;
      border-radius: 5px;
      font-weight: 500;
      transition: 0.3s;
    }
    
    .delete-btn:hover {
      background: var(--light-hover);
    }
    </style>
    