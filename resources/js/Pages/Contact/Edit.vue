<template>
    <div>
      <Head :title="`${form.name}`" />
      <div class="flex justify-start mb-8 max-w-3xl">
        <h1 class="text-3xl font-bold">
          <Link class="text-indigo-400 hover:text-indigo-600" href="/users">Users</Link>
          <span class="text-indigo-400 font-medium">/</span>
          {{ form.name }}
        </h1>
      </div>
      <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <h3 class="p-3 font-bold">
            User
        </h3>
        <form @submit.prevent="updateUser">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="First name" />
            <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" disabled/>
            <text-input v-model="form.password" :error="form.errors.password" class="pb-8 pr-6 w-full lg:w-1/2" type="password" autocomplete="new-password" label="Password" />
          </div>
          <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
            <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update User</loading-button>
          </div>
        </form>
      </div>

      <div class="max-w-3xl mt-2 bg-white rounded-md shadow overflow-hidden">
        <h3 class="p-3 font-bold">
            Contact
        </h3>
        <form @submit.prevent="updateContact">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input v-model="form.no_hp" :error="form.errors.no_hp" class="pb-8 pr-6 w-full lg:w-1/2" label="No HP " />
            <text-input v-model="form.sim" :error="form.errors.sim" type="number" class="pb-8 pr-6 w-full lg:w-1/2" label="SIM "/>
            <TextareaInput v-model="form.alamat" :error="form.errors.alamat" class="pb-8 pr-6 w-full lg:w-full" label="Alamat " />
          </div>
          <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
            <button class="btn-indigo ml-auto" type="submit">Update Contact</button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import { Head, Link } from '@inertiajs/vue3'
  import Layout from '@/Shared/Layout.vue'
  import TextInput from '@/Shared/TextInput.vue'
  import TextareaInput from '@/Shared/TextareaInput.vue'
  import FileInput from '@/Shared/FileInput.vue'
  import SelectInput from '@/Shared/SelectInput.vue'
  import LoadingButton from '@/Shared/LoadingButton.vue'
  import TrashedMessage from '@/Shared/TrashedMessage.vue'
  
  export default {
    components: {
      FileInput,
      Head,
      Link,
      LoadingButton,
      SelectInput,
      TextInput,
      TrashedMessage,
      TextareaInput
    },
    layout: Layout,
    props: {
      user: Object,
    },
    remember: 'form',
    data() {
      return {
        form: this.$inertia.form({
          _method: 'put',
          name: this.user.name,
          email: this.user.email,
          role: this.user.role,
          password: this.user.password,
          nama_kontak: this.user.contacts[0].name,
          no_hp: this.user.contacts[0].no_hp,
          sim: this.user.contacts[0].sim,
          alamat: this.user.contacts[0].alamat
        }),
      }
    },
    methods: {
      updateUser() {
        this.form.post(`/users/${this.user.id}/edit`, {
          onSuccess: () => this.form.reset('password', 'photo'),
        })
      },
      updateContact() {
        this.form.post(`/contact/${this.user.id}/edit`, {
          onSuccess: () => this.form.reset('password', 'photo'),
        })
      },
      destroy() {
        if (confirm('Are you sure you want to delete this user?')) {
          this.$inertia.delete(`/users/${this.user.id}`)
        }
      },
      restore() {
        if (confirm('Are you sure you want to restore this user?')) {
          this.$inertia.put(`/users/${this.user.id}/restore`)
        }
      },
    },
  }
  </script>