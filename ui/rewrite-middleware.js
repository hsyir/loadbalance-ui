// rewrite-middleware.js

export default function rewriteMiddleware(req, res, next) {
    if (req.url.startsWith('/assets/')) {
      const assetPath = req.url.replace('/assets/', '/?loadbalance=true&action=asset&file=');
      req.url = assetPath;
    }
    next();
  }
  