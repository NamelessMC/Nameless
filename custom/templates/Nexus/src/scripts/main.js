/* ================================================================
 * Nexus · Client Bundle
 *
 * Alpine.js + theme management + command palette + helpers.
 * Coexists with jQuery (window.$) and any legacy module scripts.
 * Loaded with `defer` by NamelessMC's asset resolver.
 * ================================================================ */

import Alpine from 'alpinejs';

/* ----------------------------------------------------------------
 * 1. Theme management (Dark / Light, persisted to localStorage)
 *    Sets data-theme="dark|light" on <html>.
 *    Default = "dark" (Nexus is dark-first).
 *    Honors prefers-color-scheme only on first visit.
 * ---------------------------------------------------------------- */
const THEME_KEY = 'nexus.theme';

function getInitialTheme() {
  try {
    const stored = localStorage.getItem(THEME_KEY);
    if (stored === 'dark' || stored === 'light') return stored;
  } catch { /* SSR-ish guard */ }
  return 'dark';
}

function applyTheme(theme) {
  document.documentElement.setAttribute('data-theme', theme);
  try { localStorage.setItem(THEME_KEY, theme); } catch {}
}

// Apply immediately to avoid FOUC (header.tpl also has an inline pre-paint script)
applyTheme(getInitialTheme());

/* ----------------------------------------------------------------
 * 2. Alpine global stores
 * ---------------------------------------------------------------- */
document.addEventListener('alpine:init', () => {
  /** theme store */
  Alpine.store('theme', {
    current: getInitialTheme(),
    init() { applyTheme(this.current); },
    toggle() {
      this.current = this.current === 'dark' ? 'light' : 'dark';
      applyTheme(this.current);
    },
    set(theme) {
      if (theme !== 'dark' && theme !== 'light') return;
      this.current = theme;
      applyTheme(theme);
    },
  });

  /** toast store: push/dismiss notifications */
  Alpine.store('toast', {
    items: [],
    push({ title = '', body = '', variant = 'default', timeout = 4000 } = {}) {
      const id = Math.random().toString(36).slice(2);
      this.items.push({ id, title, body, variant });
      if (timeout > 0) setTimeout(() => this.dismiss(id), timeout);
    },
    dismiss(id) {
      this.items = this.items.filter((t) => t.id !== id);
    },
  });

  /** modal store: simple registry, lets any element open/close by name */
  Alpine.store('modal', {
    active: null,
    open(name)  { this.active = name; document.body.style.overflow = 'hidden'; },
    close()     { this.active = null; document.body.style.overflow = ''; },
    is(name)    { return this.active === name; },
  });

  /** mobile-nav store */
  Alpine.store('mobileNav', {
    open: false,
    toggle() { this.open = !this.open; document.body.style.overflow = this.open ? 'hidden' : ''; },
    close()  { this.open = false; document.body.style.overflow = ''; },
  });

  /** command palette store: holds items and visibility */
  Alpine.store('palette', {
    visible: false,
    query: '',
    items: [],
    activeIndex: 0,
    open() { this.visible = true; this.query = ''; this.activeIndex = 0;
             this.$nextTick = () => requestAnimationFrame(() => document.getElementById('nx-palette-input')?.focus()); this.$nextTick(); },
    close() { this.visible = false; },
    register(items) { this.items = items; },
    get filtered() {
      const q = this.query.trim().toLowerCase();
      if (!q) return this.items;
      return this.items
        .map((item) => ({
          item,
          score: scoreFuzzy(`${item.title} ${item.section || ''} ${item.keywords?.join(' ') || ''}`.toLowerCase(), q),
        }))
        .filter((x) => x.score > 0)
        .sort((a, b) => b.score - a.score)
        .map((x) => x.item);
    },
    move(delta) {
      const total = this.filtered.length;
      if (!total) return;
      this.activeIndex = (this.activeIndex + delta + total) % total;
    },
    activate() {
      const item = this.filtered[this.activeIndex];
      if (item?.href) window.location.href = item.href;
      else if (item?.action) item.action();
    },
  });
});

function scoreFuzzy(haystack, needle) {
  if (!needle) return 1;
  if (haystack.includes(needle)) return 10 + needle.length;
  // simple subsequence scoring
  let i = 0, score = 0;
  for (const ch of haystack) {
    if (ch === needle[i]) { score += 1; i++; if (i === needle.length) return score; }
  }
  return i === needle.length ? score : 0;
}

/* ----------------------------------------------------------------
 * 3. Custom Alpine directives
 * ---------------------------------------------------------------- */
document.addEventListener('alpine:init', () => {
  /** x-tooltip="Some text"  — lightweight title fallback */
  Alpine.directive('tooltip', (el, { expression }) => {
    el.setAttribute('title', expression.replace(/^['"]|['"]$/g, ''));
    el.dataset.nxTooltip = '1';
  });

  /** x-clipboard="value"  — copies value to clipboard, dispatches toast */
  Alpine.directive('clipboard', (el, { expression }, { evaluate }) => {
    el.addEventListener('click', async (e) => {
      e.preventDefault();
      let value = '';
      try { value = evaluate(expression); }
      catch { value = expression; }
      try {
        await navigator.clipboard.writeText(String(value));
        Alpine.store('toast').push({
          title: typeof window.copied === 'string' ? window.copied : 'Copied',
          variant: 'success',
          timeout: 1800,
        });
      } catch { /* clipboard blocked */ }
    });
  });
});

/* ----------------------------------------------------------------
 * 4. Global keyboard shortcuts
 * ---------------------------------------------------------------- */
window.addEventListener('keydown', (e) => {
  // Strg/Cmd + K opens command palette
  if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
    e.preventDefault();
    Alpine.store('palette').open();
    return;
  }
  // Escape closes palette / modal / mobile nav
  if (e.key === 'Escape') {
    if (Alpine.store('palette')?.visible) Alpine.store('palette').close();
    else if (Alpine.store('modal')?.active) Alpine.store('modal').close();
    else if (Alpine.store('mobileNav')?.open) Alpine.store('mobileNav').close();
  }
});

/* ----------------------------------------------------------------
 * 5. Loading-time display (matches DefaultRevamp contract)
 *    `loadingTime` is set as a global const by template.php inline JS.
 * ---------------------------------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('page_load');
  if (el && typeof window.loadingTime === 'string') el.textContent = window.loadingTime;
});

/* ----------------------------------------------------------------
 * 6. IE-warning hide if browser is modern
 * ---------------------------------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
  const ieMsg = document.getElementById('ie-message');
  if (ieMsg && !document.documentMode) ieMsg.remove();
});

/* ----------------------------------------------------------------
 * 7. Smart link-active marker
 *    Anchor with [data-nav-key] gets .is-active when its key matches
 *    the page slug we expose via window.page (set by template.php).
 * ---------------------------------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
  const page = window.page;
  if (!page) return;
  document.querySelectorAll('[data-nav-key]').forEach((el) => {
    if (el.dataset.navKey === page) el.classList.add('is-active');
  });
});

/* ----------------------------------------------------------------
 * 8. Boot Alpine
 * ---------------------------------------------------------------- */
window.Alpine = Alpine;
Alpine.start();
