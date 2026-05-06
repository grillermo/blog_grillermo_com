process.title = 'blog_grillermo_com';

const http = require('http');
const fs = require('fs');
const handler = require('./node_modules/serve-handler/src/index.js');
const maxmind = require('maxmind');
const path = require('path');

const PORT = 4321;

async function start() {
  const lookup = await maxmind.open(path.join(__dirname, '../GeoLite2-City_20260501/GeoLite2-City.mmdb'));

  const server = http.createServer((req, res) => {
    if (!req.headers['user-agent']) {
      res.writeHead(404);
      res.end();
      return;
    }

    const skip = (req.method === 'HEAD' && req.url === '/') || (req.method === 'GET' && req.url === '/robots.txt');
    const start = Date.now();
    const ip = req.headers['cf-connecting-ip'] ?? req.socket.remoteAddress?.replace('::ffff:', '') ?? 'unknown';
    const geo = lookup.get(ip);
    const country = geo?.country?.iso_code ?? geo?.registered_country?.iso_code ?? '?';
    const city = geo?.city?.names?.en ?? '';
    const ua = req.headers['user-agent'] ?? '';
    const location = ` | ${country}${city ? '/' + city : ''} (${ip}) [${ua}] | `;

    if (!skip) {
      const time = new Date();
      const formatted = `${time.toLocaleDateString()} ${time.toLocaleTimeString()}`;
      console.info(' HTTP ', formatted, location, `${req.method} ${req.url}`);
    }

    res.on('finish', () => {
      if (!skip && res.statusCode !== 404) {
        const time = new Date();
        const formatted = `${time.toLocaleDateString()} ${time.toLocaleTimeString()}`;
        const ms = Date.now() - start;
        console.info(' HTTP ', formatted, location, `Returned ${res.statusCode} in ${ms} ms`);
      }
    });

    const urlPath = req.url.split('?')[0];
    const direct = path.join(__dirname, 'dist', urlPath, 'index.html');
    const blogVersion = path.join(__dirname, 'dist/blog', urlPath, 'index.html');
    if (!fs.existsSync(direct) && fs.existsSync(blogVersion)) {
      res.writeHead(301, { Location: '/blog' + urlPath });
      res.end();
      return;
    }

    handler(req, res, { public: 'dist' });
  });

  server.listen(PORT, () => {
    console.info(`Listening on http://localhost:${PORT}`);
  });
}

start().catch(err => {
  console.error('Failed to start:', err);
  process.exit(1);

});
