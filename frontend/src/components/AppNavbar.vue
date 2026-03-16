<template>
  <header
    :class="[
      'fixed top-6 w-full z-50 transition-all duration-500 ease-in-out',
      isScrolled ? 'bg-zinc-950/95 backdrop-blur-md border-b border-zinc-800/50 py-3 shadow-xl' : 'bg-transparent py-5'
    ]"
  >
    <div class="container mx-auto px-6 md:px-32 lg:px-40">
      <div class="flex items-center justify-between border-b border-zinc-700/50 pb-11">
        <Sheet :open="menuOpen" @update:open="handleSheetUpdate">
          <SheetTrigger as-child>
            <button :disabled="isAnimating" class="text-white flex items-center gap-3 group cursor-pointer">
              <Menu class="w-5 h-5 stroke-[1.5] transition-transform duration-300 group-hover:rotate-90" />
              <span class="hidden md:inline-block text-xs font-brand uppercase tracking-[0.2em] font-semibold mt-0.5">Menu</span>
            </button>
          </SheetTrigger>

          <SheetContent
            side="left"
            class="w-[90vw] sm:w-[43vw] text-white p-12 flex flex-col z-[100] max-w-none"
            style="background: rgba(10,10,10,0.45); backdrop-filter: blur(10px) saturate(180%);"
          >
            <SheetTitle class="sr-only">Menu de navigation</SheetTitle>

            <nav class="font-brand flex flex-col gap-8 flex-1 justify-center items-end pr-12 uppercase tracking-[0.15em] will-change-[opacity,transform]">
              <RouterLink to="/" :class="linkClass" :style="linkDelay(0)">
                Accueil
                <span class="underline" />
              </RouterLink>

              <RouterLink to="/catalogue" :class="linkClass" :style="linkDelay(1)">
                Nos véhicules
                <span class="underline" />
              </RouterLink>

              <a href="#" :class="linkClass" :style="linkDelay(2)">
                Services Premium
                <span class="underline" />
              </a>

              <a href="#" :class="linkClass" :style="linkDelay(3)">
                L'Agence
                <span class="underline" />
              </a>
            </nav>
          </SheetContent>
        </Sheet>

        <RouterLink to="/" class="absolute left-1/2 -translate-x-1/2 flex flex-col items-center group">
          <img
            :src="logoUrl"
            alt="Royal Wheels Logo"
            class="object-contain"
            :class="isScrolled ? 'w-10 h-10' : 'w-14 h-14'"
          />
        </RouterLink>

        <div class="flex items-center gap-6">
          <button class="text-white cursor-pointer group">
            <User class="w-5 h-5 stroke-[1.5] transition-transform duration-100 group-hover:scale-110 group-hover:-translate-y-0.5" />
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed, watch } from 'vue'
import { RouterLink } from 'vue-router'
import { Menu, User } from 'lucide-vue-next'
import { Sheet, SheetContent, SheetTitle, SheetTrigger } from '@/components/ui/sheet'
import logoUrl from '@/assets/logo.webp'

const isScrolled = ref(false)
const menuOpen = ref(false)
const linksVisible = ref(false)
const isClosing = ref(false)
const isAnimating = ref(false)
const linksAnimated = ref(false)

let scrollTimer = null
const handleScroll = () => {
  if (scrollTimer) return
  scrollTimer = requestAnimationFrame(() => {
    isScrolled.value = window.scrollY > 20
    scrollTimer = null
  })
}

onMounted(() => window.addEventListener('scroll', handleScroll))
onUnmounted(() => window.removeEventListener('scroll', handleScroll))

const handleSheetUpdate = (val) => {
  if (isAnimating.value) return
  if (val) {
    isAnimating.value = true
    menuOpen.value = true
    setTimeout(() => { isAnimating.value = false }, 800)
  } else {
    closeMenu()
  }
}

const closeMenu = () => {
  if (isAnimating.value) return
  isAnimating.value = true
  isClosing.value = true
  linksVisible.value = false
  setTimeout(() => {
    menuOpen.value = false
    isClosing.value = false
    isAnimating.value = false
  }, 570)
}

watch(menuOpen, (val) => {
  if (val) {
    setTimeout(() => {
      linksVisible.value = true
      setTimeout(() => { linksAnimated.value = true }, 800)
    }, 100)
  } else {
    linksAnimated.value = false
  }
})

const linkClass = computed(() =>
  linksVisible.value
    ? "group flex items-center gap-4 text-1xl font-bold text-zinc-400 hover:text-[#C5A070] transition-all duration-700 ease-[cubic-bezier(.16,1,.3,1)] will-change-[opacity,transform] opacity-100 translate-x-0"
    : "group flex items-center gap-4 text-1xl font-bold text-zinc-400 hover:text-[#C5A070] transition-all duration-700 ease-[cubic-bezier(.16,1,.3,1)] will-change-[opacity,transform] opacity-0 -translate-x-70"
)

const linkDelay = (index) => ({
  transitionDelay: isClosing.value
    ? `${(3 - index) * 150}ms`
    : linksAnimated.value ? '0ms'
    : menuOpen.value ? `${150 + index * 100}ms` : '0ms'
})
</script>

<style scoped>
.underline{
width:0;
height:1px;
background:#C5A070;
transition:width .3s;
}

.group:hover .underline{
width:32px;
}
</style>