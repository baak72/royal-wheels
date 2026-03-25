<template>
  <div class="pt-32 min-h-screen">
    <div class="container mx-auto px-6 md:px-12">
      <h1 class="text-4xl text-[#C5A070] font-brand mb-10">Nos Véhicules</h1>

      <div v-if="isLoading" class="text-zinc-400">
        Connexion au garage en cours...
      </div>

      <div v-else-if="error" class="text-red-500 font-bold">
        {{ error }}
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="car in vehicles" :key="car.id" class="p-6 bg-zinc-900 border border-zinc-800/60 rounded-2xl">
          <h2 class="text-xl text-white font-bold">{{ car.brand }} {{ car.model }}</h2>
          <p class="text-[#C5A070] mt-2">{{ car.daily_price }} € / jour</p>
          <p class="text-zinc-500 text-sm mt-1">{{ car.power_hp }} CH • {{ car.gearbox }}</p>
        </div>
      </div>
      
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../lib/axios'

const vehicles = ref([])
const isLoading = ref(true)
const error = ref(null)

const fetchVehicles = async () => {
  try {
    const response = await api.get('/vehicles')
    vehicles.value = response.data.vehicles
  } catch (err) {
    error.value = "Problème de communication avec l'API Laravel."
    console.error(err)
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchVehicles()
})
</script>