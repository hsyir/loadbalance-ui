import fs from 'fs';
import path from 'path';

export default function customAssetUrlPlugin() {
  return {
    name: 'custom-asset-url',
    enforce: 'post',
    transformIndexHtml(html) {
      return html.replace(/(["'])(assets\/.*?\.(css|js|png|jpg|jpeg|gif|svg))\1/g, (_, quote, file) => {
        const fileName = file.split('/').pop();
        return `${quote}?loadbalance=true&action=asset&file=${fileName}${quote}`;
      });
    },
    writeBundle(options, bundle) {
      const indexPath = path.join(options.dir, 'index.html');
      if (fs.existsSync(indexPath)) {
        let html = fs.readFileSync(indexPath, 'utf-8');
        html = html.replace(/(["'])(assets\/.*?\.(css|js|png|jpg|jpeg|gif|svg))\1/g, (_, quote, file) => {
          const fileName = file.split('/').pop();
          return `${quote}?loadbalance=true&action=asset&file=${fileName}${quote}`;
        });
        fs.writeFileSync(indexPath, html);
      }
    },
  };
}
