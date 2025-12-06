<script setup>
    const routes = [
      { from: "Dhaka", to: "Natore" },
      { from: "Natore", to: "Dhaka" },
      { from: "Dhaka", to: "Rajshahi" },
      { from: "Rajshahi", to: "Dhaka" },
      { from: "Kustia", to: "Dhaka" },
      { from: "Dhaka", to: "Kustia" },
      { from: "Meherpur", to: "Dhaka" },
      { from: "Dhaka", to: "Meherpur" },
      { from: "Dhaka", to: "Cox's Bazar" },
    ];
    </script>
    
    <template>
      <div class="container my-5 p-routes">
        <h2 class="text-center mb-4 popular-title">Popular Routes</h2>
    
        <div class="row g-4">
          <div class="col-md-4 col-sm-6" v-for="(r, i) in routes" :key="i">
            <div class="main-card rounded-3 p-3">
    
              <div class="int">
                <span>From</span>
                <span>To</span>
              </div>
    
              <div class="route-card d-flex justify-content-between align-items-center">
                <span class="fw-bold">{{ r.from }}</span>
    
                <div class="route-line">
                  <i class="bi bi-bus-front"></i>
                </div>
    
                <span class="fw-bold">{{ r.to }}</span>
              </div>
    
            </div>
          </div>
        </div>
      </div>
    </template>
    
    <style scoped>
    .popular-title {
  margin-top: 4rem;
  font-weight: 700;
  font-size: 2.5rem;
  font-family: "El Messiri", sans-serif;
}

.main-card {
  background: var(--section-bg-color);
  min-height: 110px;
  border-radius: 12px;
  border: 1px solid var(--main-color);
  transition: 0.3s ease-in-out;
}
.main-card:hover {
  box-shadow: 0 5px 15px rgba(0,0,0,.35);
  transform: translateY(-4px);
}

.route-line {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  flex-grow: 1;
  position: relative;
}
.route-line::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 12px;
  right: 12px;
  height: 2px;
  background: #ddd;
  z-index: 0;
}
.start-icon,
.end-icon,
.bus-icon {
  font-size: 1.4rem;
  color: #999;
  z-index: 1;
}
.int {
  display: flex;
  justify-content: space-between;
  margin-right: 1rem;
  font-family: "El Messiri", sans-serif;
  font-size: small;
  color: #999;
}
@media (max-width: 768px) {
  .p-routes {
    padding-top: 11rem;
  }
  .popular-title {
    margin-top: 4rem;
    font-weight: 600;
    font-size: 2rem;
  }
}

    </style>
    