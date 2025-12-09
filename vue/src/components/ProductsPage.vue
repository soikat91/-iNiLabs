<template>
  <div>
    <h3>Product List</h3>
    <ProductList :products="products" @updated="updateProduct" @deleted="removeProduct" />
  </div>
</template>

<script>
import { ref } from 'vue';
import ProductList from './ProductList.vue';
import api from '../services/api';

export default {
  components: { ProductList },

  setup() {
    const products = ref([]);

    // Fetch the list of products from the API
    async function fetchProducts() {
      const token = localStorage.getItem("token");
      const response = await api.get('/products', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      products.value = response.data;
    }

    // Handle the updated product data from the child component
    function updateProduct(updatedProduct) {
      // Replace the updated product in the array (force Vue to re-render)
      products.value = products.value.map(p => 
        p.id === updatedProduct.id ? updatedProduct : p
      );
    }

    // Handle deleted product by removing it from the list
    function removeProduct(id) {
      products.value = products.value.filter(p => p.id !== id);
    }

    // Fetch the initial products when the component is mounted
    fetchProducts();

    return { products, updateProduct, removeProduct };
  },
};
</script>
