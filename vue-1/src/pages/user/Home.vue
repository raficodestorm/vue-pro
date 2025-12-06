<script setup>
    import HomeHero from "../../components/HomeHero.vue";
    import BusSearchForm from "../../components/BusSearchForm.vue";
    import PopularRoutes from "../../components/PopularRoutes.vue";
    import ServiceSection from "../../components/ServiceSection.vue";
    </script>
    
    <template>
      <div>
    
        <!-- Hero + Search Form -->
        <HomeHero>
          <BusSearchForm />
        </HomeHero>
    
        <!-- Popular Routes -->
        <PopularRoutes />
    
        <!-- Services -->
        <ServiceSection />
    
      </div>
    </template>
    