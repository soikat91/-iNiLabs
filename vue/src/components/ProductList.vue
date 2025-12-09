<template>
  <div>
    <h3 class="mb-3">Products</h3>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Name</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Description</th>
          <th style="width:150px;">Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="p in products" :key="p.id">
          <td>{{ p.name }}</td>
          <td>{{ p.price }}</td>
          <td>{{ p.stock }}</td>
          <td>{{ p.description }}</td>
          <td>
            <button class="btn btn-sm btn-primary me-1" @click="openEdit(p)">Edit</button>
            <button class="btn btn-sm btn-danger" @click="remove(p.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- EDIT MODAL -->
    <div class="modal fade" id="editModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">
            <div class="mb-2">
              <label>Name</label>
              <input v-model="editForm.name" class="form-control" />
            </div>

            <div class="row">
              <div class="col-md-6 mb-2">
                <label>Price</label>
                <input v-model.number="editForm.price" class="form-control" />
              </div>

              <div class="col-md-6 mb-2">
                <label>Stock</label>
                <input v-model.number="editForm.stock" class="form-control" />
              </div>
            </div>

            <div class="mb-2">
              <label>Description</label>
              <textarea v-model="editForm.description" rows="3" class="form-control"></textarea>
            </div>
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-success" @click="update">Save Changes</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import api from "../services/api";
import { ref, reactive } from "vue";
import bootstrap from "bootstrap/dist/js/bootstrap.bundle.min.js";

export default {
  props: { products: Array },
  setup(props, { emit }) {
    // Reactive edit form
    const editForm = reactive({
      id: "",
      name: "",
      price: 0,
      stock: 0,
      description: "",
    });

    let editModal = null;

    // Open modal and fill form
    function openEdit(product) {
      Object.assign(editForm, product);

      if (!editModal) {
        editModal = new bootstrap.Modal(document.getElementById("editModal"));
      }
      editModal.show();
    }

    // Update product via API and reflect changes instantly
    async function update() {
      const token = localStorage.getItem("token");
      try {
        const res = await api.put(`/products/${editForm.id}`, { ...editForm }, token);

        // Update local products array
        const index = props.products.findIndex(p => p.id === editForm.id);
        if (index !== -1) {
          props.products[index] = { ...editForm };
        }

        editModal.hide();
        alert("Product updated successfully!");
      } catch (err) {
        console.error("Update failed:", err);
        alert("Failed to update product. See console for details.");
      }
    }

    // DELETE product
    async function remove(id) {
      if (!confirm("Delete product?")) return;

      const token = localStorage.getItem("token");

      try {
        await api.delete(`/products/${id}`,token);

        // Remove product from local array
        const index = props.products.findIndex(p => p.id === id);
        if (index !== -1) props.products.splice(index, 1);

        alert("Product deleted successfully!");
      } catch (err) {
        console.error("Delete failed:", err);
        alert("Failed to delete product. See console for details.");
      }
    }

    return { editForm, openEdit, update, remove };
  },
};
</script>

<style scoped>
.table td,
.table th {
  vertical-align: middle;
}
</style>
