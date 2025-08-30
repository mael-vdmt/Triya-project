import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    host: '0.0.0.0',
    port: 5173,
    hmr: {
      overlay: false,
    },
    watch: {
      usePolling: true,
    },
  },
  optimizeDeps: {
    include: ['vue', 'vue-router', 'pinia'],
  },
  css: {
    devSourcemap: false,
  },
})
