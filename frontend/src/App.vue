<template>
  <div class="dark min-h-screen bg-background text-foreground flex flex-col">
    
    <AppNavbar />

    <main class="flex-1 relative">
      <RouterView />
    </main>

  </div>
</template>

<script setup>
import { RouterView } from 'vue-router'
import AppNavbar from '@/components/AppNavbar.vue'
</script>