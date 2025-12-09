<template>
  <Layout :token="token" @logout="logout">
    <div class="mt-4">

      <LoginForm
        v-if="!token"
        @logged="onLogged"
      />

      <div v-else>
        <ProductForm @created="fetchProducts" />

        <ProductList
          :products="products"
          @deleted="fetchProducts"
        />
      </div>

    </div>
  </Layout>
</template>

<script>
import { ref, onMounted } from 'vue'
import Layout from './components/Layout.vue'
import LoginForm from './components/LoginForm.vue'
import ProductList from './components/ProductList.vue'
import ProductForm from './components/ProductForm.vue'
import api from './services/api'

export default {
  components: { Layout, LoginForm, ProductList, ProductForm },
  setup() {
    const token = ref(localStorage.getItem("token") || "")
    const products = ref([])

    async function fetchProducts() {
      if (!token.value) return
      const res = await api.get("/products", token.value)
      products.value = res.data || res
    }

    function onLogged(t) {
      token.value = t
      localStorage.setItem("token", t)
      fetchProducts()
    }

    function logout() {
      localStorage.removeItem("token")
      token.value = ""
      products.value = []
    }

    onMounted(() => {
      if (token.value) fetchProducts()
    })

    return { token, products, onLogged, logout, fetchProducts }
  }
}
</script>
