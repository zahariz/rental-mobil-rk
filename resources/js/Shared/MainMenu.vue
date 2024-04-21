<template>
  <div v-if="auth">
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/">
        <icon name="dashboard" class="mr-2 w-4 h-4" :class="isUrl('') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Dashboard</div>
      </Link>
    </div>
    <div class="mb-4" v-if="auth.user.role == 'Admin'">
      <Link class="group flex items-center py-3" href="/cars">
        <icon name="cars" class="mr-2 w-4 h-4" :class="isUrl('cars') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('cars') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Master Mobil</div>
      </Link>
    </div>
    <div class="mb-4" v-if="auth.user.role == 'user'">
      <Link class="group flex items-center py-3" href="/list-cars">
        <icon name="cars" class="mr-2 w-4 h-4" :class="isUrl('list-cars') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('list-cars') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">List Mobil</div>
      </Link>
    </div>
    <div class="mb-4" v-if="auth.user.role == 'user'">
      <Link class="group flex items-center py-3" href="/transaction">
        <icon name="rent" class="mr-2 w-4 h-4" :class="isUrl('transaction') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('transaction') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">History</div>
      </Link>
    </div>
    <div class="mb-4" v-if="auth.user.role == 'user'">
      <Link class="group flex items-center py-3" href="/return">
        <icon name="return" class="mr-2 w-4 h-4" :class="isUrl('return') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('return') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Pengembalian</div>
      </Link>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'

export default {
  components: {
    Icon,
    Link,
  },
  props: {
    auth: Object
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length
    },
  },
}
</script>
