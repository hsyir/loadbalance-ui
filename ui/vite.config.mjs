import { defineConfig } from 'vite'
import { fileURLToPath, URL } from 'node:url'
import vue from '@vitejs/plugin-vue'

// Custom plugin to modify asset URLs
function transformAssetURLs() {
  return {
    name: 'transform-asset-urls',
    transformIndexHtml(html) {
      return html.replace(/(href|src)="([^"]+\.(js|css|png|jpg|jpeg|gif|svg))"/g, (match, p1, p2) => {
        return `${p1}="?loadbalance=true&action=asset&file=${p2}"`;
      });
    },
    generateBundle(options, bundle) {
      for (const fileName in bundle) {
        const chunk = bundle[fileName];
        if (chunk.type === 'asset') {
          const newFileName = `assets/${fileName}`;
          chunk.fileName = newFileName;
        }
      }
    }
  };
}

export default defineConfig({
  plugins: [
    vue(),
    transformAssetURLs()
  ],
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
