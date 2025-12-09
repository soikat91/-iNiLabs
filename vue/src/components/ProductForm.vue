<template>
  <div class="card mb-4 shadow-sm p-4">
    <h4 class="mb-3">Add Product</h4>

    <form @submit.prevent="create">
      <div class="row">
        
        <div class="col-md-6 mb-3">
          <label>Name</label>
          <input v-model="form.name" class="form-control" />
        </div>

        <div class="col-md-3 mb-3">
          <label>Price</label>
          <input v-model.number="form.price" class="form-control" />
        </div>

        <div class="col-md-3 mb-3">
          <label>Stock</label>
          <input v-model.number="form.stock" class="form-control" />
        </div>
      </div>

      <div class="mb-3">
        <label>Description</label>
        <textarea v-model="form.description" class="form-control"></textarea>
      </div>

      <button class="btn btn-success">Create</button>
    </form>

    <p v-if="error" class="text-danger mt-2">{{ error }}</p>
  </div>
</template>


<script>
import api from '../services/api'
import { reactive, ref } from 'vue'

export default {
  emits: ['created'],
  setup(_, { emit }) {
    const form = reactive({ name:'', description:'', price:0, stock:0 })
    const error = ref('')

    async function create() {
      error.value = ''
      try {
        await api.post('/products', form, localStorage.getItem('token'))
        emit('created')
        form.name = ''
        form.description = ''
        form.price = 0
        form.stock = 0
      } catch (e) {
        error.value = 'Create failed'
      }
    }

    return { form, create, error }
  }
}
</script>
