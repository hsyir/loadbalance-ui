import { defineConfig } from 'vite'
import { fileURLToPath, URL } from 'node:url'
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify'

// Custom plugin to modify asset URLs
function transformAssetURLs() {
  return {
    name: 'transform-asset-urls',
    transformIndexHtml(html) {
      return html.replace(/(href|src)="([^"]+)(\.(js|css|png|jpg|jpeg|gif|svg|woff))"/g, (match, p1, p2, p3) => {
        const newUrl = p2.includes('assets/') ? `${p2}${p3}` : `assets/${p2}${p3}`;
        return `${p1}="?loadbalance=true&action=asset&file=${newUrl}"`;
      });
    },
    generateBundle(options, bundle) {
      for (const fileName in bundle) {
        const chunk = bundle[fileName];
        if (chunk.type === 'asset' || chunk.type === 'chunk') {
          if (!fileName.startsWith('assets/')) {
            const newFileName = `assets/${fileName}`;
            chunk.fileName = newFileName;
          }
        }
      }
    }
  };
}

export default defineConfig({
  plugins: [
    vue(),
    vuetify(), 
    transformAssetURLs()
  ],
  build: {
    rollupOptions: {
      output: {
        assetFileNames: 'assets/[name]-[hash][extname]',
        chunkFileNames: 'assets/[name]-[hash].js',
        entryFileNames: 'assets/[name]-[hash].js'
      }
    }
  },
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
    extensions: [
      '.js',
      '.json',
      '.jsx',
      '.mjs',
      '.ts',
      '.tsx',
      '.vue',
    ],
  },
  server: {
    port: 3000,
  },
})
