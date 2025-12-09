<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-3">
      <a class="navbar-brand" href="#">Products App</a>

      <ul class="navbar-nav ms-auto">
        <li class="nav-item" v-if="token">
          <a class="nav-link" @click="$emit('logout')" style="cursor:pointer;">
            Logout
          </a>
        </li>
      </ul>
    </nav>

    <div class="container mt-4">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  props: ["token"]
}
</script>
