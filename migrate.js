#!/usr/bin/env node
// WordPress → Astro migration script

import mysql from 'mysql2/promise';
import fs from 'fs';
import path from 'path';
import { execSync } from 'child_process';

const SOCKET = '/Users/grillermo/Library/Application Support/Local/run/ZWR8Fecp4/mysql/mysqld.sock';
const WP_UPLOADS = path.resolve('./wordpress/wp-content/uploads');
const ASTRO_BLOG = path.resolve('./astro/src/content/blog');
const ASTRO_PUBLIC_UPLOADS = path.resolve('./astro/public/uploads');
const WP_BASE_URL = 'http://blog.grillermo.com.local.local';

function slugify(text) {
  return text
    .toString()
    .toLowerCase()
    .trim()
    .replace(/\s+/g, '-')
    .replace(/[^\w\-]+/g, '')
    .replace(/\-\-+/g, '-')
    .replace(/^-+/, '')
    .replace(/-+$/, '');
}

function stripGutenbergBlocks(content) {
  if (!content) return '';
  // Remove Gutenberg block comments
  return content
    .replace(/<!-- wp:[^\n]*?-->/g, '')
    .replace(/<!-- \/wp:[^\n]*?-->/g, '')
    .trim();
}

function escapeYamlString(str) {
  if (!str) return '""';
  // Use double-quoted YAML string, escaping backslashes and double quotes
  const escaped = str.replace(/\\/g, '\\\\').replace(/"/g, '\\"').replace(/\n/g, '\\n');
  return `"${escaped}"`;
}

function buildFrontmatter(post, categories, heroImagePath) {
  const lines = [
    `title: ${escapeYamlString(post.post_title)}`,
    `pubDate: ${post.post_date.toISOString()}`,
    `wpId: ${post.ID}`,
  ];

  if (post.post_modified && post.post_modified.getTime() !== post.post_date.getTime()) {
    lines.push(`updatedDate: ${post.post_modified.toISOString()}`);
  }

  if (categories && categories.length > 0) {
    const cats = categories.filter(c => c && c !== 'uncategorized');
    if (cats.length > 0) {
      lines.push(`categories: [${cats.map(c => escapeYamlString(c)).join(', ')}]`);
    }
  }

  if (heroImagePath) {
    lines.push(`heroImage: ${escapeYamlString(heroImagePath)}`);
  }

  return `---\n${lines.join('\n')}\n---\n\n`;
}

function rewriteImageUrls(content) {
  return content.replace(
    new RegExp(WP_BASE_URL.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '/wp-content/uploads/', 'g'),
    '/uploads/'
  );
}

function copyUploads() {
  if (!fs.existsSync(WP_UPLOADS)) {
    console.log('No uploads directory found, skipping.');
    return;
  }
  fs.mkdirSync(ASTRO_PUBLIC_UPLOADS, { recursive: true });
  try {
    execSync(`rsync -a --quiet "${WP_UPLOADS}/" "${ASTRO_PUBLIC_UPLOADS}/"`, { stdio: 'inherit' });
    console.log('Images copied to public/uploads/');
  } catch (e) {
    console.error('rsync failed:', e.message);
  }
}

async function migrate() {
  const conn = await mysql.createConnection({
    socketPath: SOCKET,
    user: 'root',
    password: 'root',
    database: 'local',
  });

  console.log('Connected to MySQL');

  // Fetch all published posts
  const [posts] = await conn.query(`
    SELECT ID, post_title, post_name, post_content, post_date, post_modified, post_status
    FROM wp_posts
    WHERE post_type = 'post' AND post_status = 'publish'
    ORDER BY post_date ASC
  `);

  console.log(`Found ${posts.length} published posts`);

  // Fetch categories per post
  const [catRows] = await conn.query(`
    SELECT tr.object_id as post_id, t.slug, t.name
    FROM wp_term_relationships tr
    JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
    JOIN wp_terms t ON tt.term_id = t.term_id
    WHERE tt.taxonomy = 'category'
  `);

  const catMap = {};
  for (const row of catRows) {
    if (!catMap[row.post_id]) catMap[row.post_id] = [];
    catMap[row.post_id].push(row.slug);
  }

  // Fetch featured images
  const [thumbRows] = await conn.query(`
    SELECT pm.post_id, a.guid
    FROM wp_postmeta pm
    JOIN wp_posts a ON pm.meta_value = a.ID
    WHERE pm.meta_key = '_thumbnail_id' AND a.post_type = 'attachment'
  `);

  const thumbMap = {};
  for (const row of thumbRows) {
    thumbMap[row.post_id] = row.guid;
  }

  fs.mkdirSync(ASTRO_BLOG, { recursive: true });

  // Track used filenames to avoid collisions
  const usedSlugs = {};

  let written = 0;
  for (const post of posts) {
    let slug = post.post_name || slugify(post.post_title) || `post-${post.ID}`;
    if (!slug || slug.trim() === '') slug = `post-${post.ID}`;

    // Ensure unique slug
    if (usedSlugs[slug]) {
      slug = `${slug}-${post.ID}`;
    }
    usedSlugs[slug] = true;

    const categories = catMap[post.ID] || [];

    // Hero image: convert absolute WP URL to relative public path
    let heroImagePath = null;
    if (thumbMap[post.ID]) {
      heroImagePath = thumbMap[post.ID]
        .replace(WP_BASE_URL + '/wp-content/uploads/', '/uploads/')
        .replace(/^http:\/\/[^/]+\/wp-content\/uploads\//, '/uploads/');
    }

    const strippedContent = stripGutenbergBlocks(post.post_content);
    const contentWithRewrittenUrls = rewriteImageUrls(strippedContent);

    const frontmatter = buildFrontmatter(post, categories, heroImagePath);
    const fileContent = frontmatter + contentWithRewrittenUrls;

    const filePath = path.join(ASTRO_BLOG, `${slug}.md`);
    fs.writeFileSync(filePath, fileContent, 'utf8');
    written++;
  }

  console.log(`Written ${written} markdown files to ${ASTRO_BLOG}`);

  await conn.end();

  // Copy uploads
  copyUploads();
}

migrate().catch(err => {
  console.error('Migration failed:', err);
  process.exit(1);
});
