<script setup>
    import HomeHero from "../../components/HomeHero.vue";
    import BusSearchForm from "../../components/BusSearchForm.vue";
    import PopularRoutes from "../../components/PopularRoutes.vue";
    import ServiceSection from "../../components/ServiceSection.vue";
    </script>
    
    <template>
      <div>
    
        <!-- Hero + Search Form -->
      <section id="home">
        <HomeHero>
          <BusSearchForm />
        </HomeHero>
      </section>
    
        <!-- Popular Routes -->
        <section id="routes">
        <PopularRoutes />
        </section>
    
        <!-- Services -->
        <section id="services">
        <ServiceSection />
        </section>
    
      </div>
    </template>
    