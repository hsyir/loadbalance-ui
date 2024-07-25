export default function customAssetUrlPlugin() {
  return {
    name: 'custom-asset-url',
    enforce: 'post',
    transformIndexHtml(html) {
      return html.replace(/(["'])\/assets\/(.*?)\1/g, (_, quote, file) => {
        return `${quote}/?loadbalance=true&action=asset&file=${file}${quote}`;
      });
    },
    generateBundle(_, bundle) {
      for (const file of Object.keys(bundle)) {
        if (file.endsWith('.css') || file.endsWith('.js')) {
          const chunk = bundle[file];
          if (chunk.type === 'asset' || chunk.type === 'chunk') {
            const code = chunk.source;
            if (typeof code === 'string') {
              chunk.source = code.replace(/(["'])\/assets\/(.*?)\1/g, (_, quote, file) => {
                return `${quote}/?loadbalance=true&action=asset&file=${file}${quote}`;
              });
            }
          }
        }
      }
    },
  };
}
