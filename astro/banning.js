const fs = require('fs');

function parseBlockedAgents(robotsPath) {
  const content = fs.readFileSync(robotsPath, 'utf8');
  const blocks = content.split(/\n\s*\n/);
  const blocked = [];
  for (const block of blocks) {
    const lines = block.split('\n').map(l => l.trim()).filter(l => l && !l.startsWith('#'));
    const agents = lines
      .filter(l => l.startsWith('User-agent:'))
      .map(l => l.slice('User-agent:'.length).trim());
    if (lines.some(l => l === 'Disallow: /')) {
      for (const agent of agents) {
        if (agent !== '*') blocked.push(agent.toLowerCase());
      }
    }
  }
  return blocked;
}

let BLOCKED_AGENTS = [];

const ipResponseLog = new Map();
const ipBlockLog = new Map();

function init(robotsPath) {
  BLOCKED_AGENTS = parseBlockedAgents(robotsPath);
}

function isBlockedAgent(ua) {
  const lower = ua.toLowerCase();
  return BLOCKED_AGENTS.some(bot => lower.includes(bot));
}

function recordResponse(ip, statusCode) {
  const now = Date.now();
  const entries = ipResponseLog.get(ip) ?? [];
  entries.push({ time: now, status: statusCode });
  ipResponseLog.set(ip, entries.filter(e => now - e.time < 10000));
}

function blockAutomatically(ip) {
  const now = Date.now();
  const entries = ipResponseLog.get(ip) ?? [];
  return entries.filter(e => now - e.time < 3000 && e.status === 404).length >= 5;
}

function recordBlock(ip) {
  const times = ipBlockLog.get(ip) ?? [];
  times.push(Date.now());
  ipBlockLog.set(ip, times);
}

function exponentialBackOff(ip) {
  return (ipBlockLog.get(ip) ?? []).length > 3;
}

module.exports = { init, isBlockedAgent, recordResponse, blockAutomatically, recordBlock, exponentialBackOff };
