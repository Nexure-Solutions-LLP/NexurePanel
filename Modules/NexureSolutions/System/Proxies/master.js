// universal-proxy.js
const express = require('express');
const puppeteer = require('puppeteer');
const fetch = require('node-fetch'); // ensure installed: npm i node-fetch@2
const url = require('url');

const app = express();
const PORT = 3000;

// Helper: fetch any URL with Edge User-Agent
async function fetchResource(targetUrl) {
    const response = await fetch(targetUrl, {
        headers: {
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 ' +
                          '(KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36 Edg/117.0.2045.60'
        }
    });
    const buffer = await response.buffer();
    const contentType = response.headers.get('content-type') || 'application/octet-stream';
    return { buffer, contentType };
}

// Main proxy route
app.get('/', async (req, res) => {
    const target = req.query.url;
    if (!target) return res.status(400).send('No URL provided.');

    // If the request is for a static resource (CSS/JS/img), fetch and return it directly
    if (req.query.resource === '1') {
        try {
            const { buffer, contentType } = await fetchResource(target);
            res.set('Content-Type', contentType);
            return res.send(buffer);
        } catch (err) {
            console.error(err);
            return res.status(500).send('Failed to fetch resource');
        }
    }

    // Otherwise, render full page with Puppeteer
    let browser;
    try {
        browser = await puppeteer.launch({
            headless: true,
            args: ['--no-sandbox', '--disable-setuid-sandbox']
        });
        const page = await browser.newPage();

        await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 ' +
            '(KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36 Edg/117.0.2045.60');

        // Intercept all requests
        await page.setRequestInterception(true);
        page.on('request', async request => {
            const resourceType = request.resourceType();
            const requestUrl = request.url();

            // For main document, continue
            if (resourceType === 'document') return request.continue();

            // For all other resources, rewrite to go through proxy
            const proxiedUrl = '/?url=' + encodeURIComponent(requestUrl) + '&resource=1';
            request.respond({
                status: 200,
                headers: { 'content-type': 'text/html' },
                body: `<script>window.location="${proxiedUrl}"</script>`
            }).catch(() => request.continue());
        });

        await page.goto(target, { waitUntil: 'networkidle2', timeout: 60000 });

        // Wait a short time for JS-heavy sites
        await new Promise(resolve => setTimeout(resolve, 2000));

        const html = await page.content();

        res.set('Content-Type', 'text/html');
        res.send(html);

    } catch (err) {
        console.error(err);
        res.status(500).send('Error rendering page');
    } finally {
        if (browser) await browser.close();
    }
});

app.listen(PORT, () => console.log(`Universal proxy running on http://localhost:${PORT}`));
