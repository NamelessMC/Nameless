# Nexus · AAA-Theme for NamelessMC

A modern, AAA-grade UI/UX theme for NamelessMC. Dark-first, with optional light mode. Built with TailwindCSS + Alpine.js, coexists peacefully with the legacy jQuery/Fomantic stack that NamelessMC modules rely on.

Design language: Linear / Vercel / Discord / Notion / Raycast — cyberpunk minimalism with Apple-level polish.

---

## 1. Requirements

- **NamelessMC** ≥ 2.2.5 already installed and working with the bundled `DefaultRevamp` theme.
- **Node.js** ≥ 18 + npm (only required to **build** the theme; not required at runtime).

### Install Node.js on Windows
The fastest path is via winget (built into Windows 10/11):

```powershell
winget install OpenJS.NodeJS.LTS
```

Reopen your terminal afterwards so `npm` lands in PATH. Verify:

```powershell
node --version   # v20.x or later
npm --version    # 10.x or later
```

Alternatives: [nodejs.org installer](https://nodejs.org/), `scoop install nodejs-lts`, or `volta install node`.

---

## 2. Build the theme

From the repo root:

```bash
cd custom/templates/Nexus
npm install                # one-time
npm run build              # production build → assets/css/nexus.css + assets/js/nexus.js
```

For active development:

```bash
npm run dev                # watch mode: rebuilds on file save
```

The build pipeline is:

| Step       | Tool           | Input                    | Output                          |
|------------|----------------|--------------------------|---------------------------------|
| CSS        | Tailwind 3.4   | `src/styles/main.css`    | `assets/css/nexus.css` (~50 KB) |
| JS         | esbuild        | `src/scripts/main.js`    | `assets/js/nexus.js` (~45 KB)   |

Build artifacts live under `assets/` and are git-ignored — always run `npm run build` after pulling.

---

## 3. Activate Nexus

After building, in the NamelessMC admin panel:

**StaffCP → Layout → Templates** → set `Nexus` as active. That's it. Public pages now render with Nexus; admin panel keeps using `Default` (Iteration 2 territory).

If the theme doesn't appear, ensure the folder is exactly at `custom/templates/Nexus/` and that `template.php` is readable by PHP.

---

## 4. What's included (Iteration 1)

### Pages
- Landing / Portal · Auth (login, register, forgot/change password, 2FA, complete signup, AuthMe)
- Profile (hero banner, tabs, wall posts, about, reactions)
- User CP (overview, settings, plus baselines for alerts, messaging, connections, sessions, OAuth, etc.)
- Forum (index, view-forum, view-topic, new-topic, edit, search, search-results, follow, merge, move, redirects)
- Members directory · Leaderboards · Status
- Errors (404, 403, maintenance) · Cookies, Privacy, Terms · Custom pages
- Reactions modal · User popover

### Widgets
- Online Staff · Online Users · Server Status · Statistics · Reactions
- Minecraft Account (3D skin viewer) · Profile Posts · Cookie Notice · Widget Error
- Forum · Latest Posts

### Design system
- `src/styles/tokens.css` — all color, radius, spacing, shadow, type tokens as CSS custom properties
- `src/styles/base.css` — element resets, headings, focus rings, scrollbars, kbd, code
- `src/styles/components.css` — `.nx-*` component classes (button, card, input, modal, dropdown, tabs, badge, avatar, alert, toast, table, pagination, skeleton, spinner, divider, tooltip, kbd)
- `src/styles/overrides.css` — legacy compatibility layer: restyles Fomantic UI / Bootstrap class hooks used by modules (`.ui.button`, `.ui.segment`, `.ui.form`, `.ui.message`, `.ui.modal`, `.ui.grid`, `.dataTables_wrapper`, `select2-container`, etc.)
- `tailwind.config.js` — token-aware Tailwind config

### Interactivity
- `src/scripts/main.js` — Alpine.js + custom stores (`theme`, `toast`, `modal`, `mobileNav`, `palette`)
- Custom directives: `x-tooltip`, `x-clipboard`
- Global shortcuts: `Cmd/Ctrl+K` opens command palette, `Escape` closes overlays
- Seamless coexistence with jQuery — module scripts keep working

---

## 5. Customization

### Brand color
Open `src/styles/tokens.css`, change `--accent` (and `--accent-hover`, `--accent-subtle`, `--accent-glow` to match), then `npm run build`.

### Light mode
Already in: header pre-paint script applies the persisted `nexus.theme` localStorage key. Users toggle via the sun/moon icon in the navbar. Default = dark.

### Add a component
Add a class under `@layer components` in `components.css`. Use Tailwind utilities and CSS variables — both have first-class support.

### Add icons
Edit `components/icon.tpl` — it's a single inline-SVG sprite. Add a `{elseif $name eq 'your-name'}<path d="…"/>` branch. All icons inherit `currentColor` for theming.

---

## 6. Smoke-test plan

After install + build + activation, walk through these flows. Each should work identically to DefaultRevamp.

1. **Visitor flow** — visit `/`, click around (Forum, Members, Login). No console errors. Theme toggle works.
2. **Auth** — Register, log out, log in. Forgot-password sends email. 2FA prompt works if enabled.
3. **Profile** — Edit avatar, change banner, post on a wall, react to a post, view About tab.
4. **Forum** — Create topic, reply, react, edit, delete, lock (as mod), follow/unfollow, search.
5. **Members** — Browse overview, filter by group, search a username, click a member card.
6. **Mobile** — Resize to ≤640px. Off-canvas drawer opens, command palette works, all pages remain usable.
7. **Cmd-K** — Press `Ctrl+K` / `⌘K` anywhere; type a nav name; press Enter. Should navigate.
8. **Light mode** — Click sun/moon. Page re-themes without reload. Refresh — preference persists.
9. **Modules** — If you have the Cookie-Consent or Discord-Integration modules enabled, verify their widgets render and their modals (Cookie-Consent banner) still trigger correctly.

### Lighthouse targets
```bash
npx lighthouse http://localhost:8080 --view
```
Aim for: Performance ≥ 90, Accessibility ≥ 95, Best Practices ≥ 95, SEO ≥ 90.

### Browser support
Tested in Chrome, Firefox, Safari, Edge (current). IE11 explicitly unsupported (the IE-warning banner removes itself in modern browsers).

---

## 7. Troubleshooting

| Symptom                                                  | Fix                                                                                          |
|----------------------------------------------------------|----------------------------------------------------------------------------------------------|
| Page renders unstyled (raw HTML)                         | `assets/css/nexus.css` is missing — run `npm run build`.                                     |
| `npm install` errors with Python/MSVC noise              | Update Node.js to LTS ≥ 18; the old Tailwind required postinstall builds, current does not.  |
| Theme not selectable in admin                            | Verify path `custom/templates/Nexus/template.php` exists and PHP can read it. Clear cache.   |
| Module page is partially un-themed                       | The module renders Fomantic/Bootstrap markup we haven't restyled. Add an override rule in `src/styles/overrides.css` and rebuild. |
| jQuery / Bootstrap modal not opening                     | Make sure jQuery is loaded before module code. `template.php` already includes `AssetTree::JQUERY` — check the network tab. |
| Cmd+K does nothing                                       | Alpine didn't boot — check console for JS errors; ensure `assets/js/nexus.js` loads (404?).  |
| Light mode flashes dark on page transitions             | The pre-paint script in `header.tpl` should prevent this; confirm `localStorage.getItem('nexus.theme')` returns the right value. |

---

## 8. Roadmap

- **I2** Admin panel redesign (separate plan — `custom/panel_templates/Nexus/`).
- **I3** Email templates + TinyMCE skin polish.
- **I4** Optional Vue islands for complex admin widgets.

---

## License
MIT — matches the upstream NamelessMC license. Use freely, attribute optionally.
