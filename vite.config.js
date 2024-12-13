export default defineConfig({
  build: {
    // ... otras configuraciones
    css: {
      preprocessorOptions: {
        scss: {
          additionalData: `
            @import "resources/assets/css/test-table.css";
          `
        }
      }
    }
  }
}); 