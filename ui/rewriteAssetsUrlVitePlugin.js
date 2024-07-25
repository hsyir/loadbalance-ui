export default function customRewrite() {
  return {
    name: 'custom-rewrite',
    generateBundle(options, bundle) {
      for (const [key, value] of Object.entries(bundle)) {
        if (value.type === 'asset' && /\.(css|js)$/.test(value.fileName)) {
          const originalFileName = value.fileName;
          value.fileName = `/?loadbalance=true&action=asset&file=${originalFileName}`;
        }
      }
    }
  };
}
