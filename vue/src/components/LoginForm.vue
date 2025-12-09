<template>
  <div class="card shadow-sm p-4" style="max-width: 400px; margin:auto;">
    <h4 class="mb-3 text-center">Login</h4>

    <form @submit.prevent="login">
      <div class="mb-3">
        <label>Email</label>
        <input v-model="email" type="email" class="form-control" placeholder="Enter email" />
      </div>

      <div class="mb-3">
        <label>Password</label>
        <input v-model="password" type="password" class="form-control" placeholder="Enter password" />
      </div>

      <button class="btn btn-primary w-100">Login</button>
    </form>

    <p v-if="error" class="text-danger mt-3 text-center">{{ error }}</p>
  </div>
</template>

<script>
import api from "../services/api";
import { ref } from "vue";

export default {
  emits: ["logged"],
  setup(_, { emit }) {
    const email = ref("test@test.com");
    const password = ref("123456");
    const error = ref("");

    async function login() {
      error.value = "";
      try {
        const res = await api.post("/login", {
          email: email.value,
          password: password.value,
        });
        emit("logged", res.token || res.data.token);
      } catch {
        error.value = "Invalid login";
      }
    }

    return { email, password, login, error };
  },
};
</script>
