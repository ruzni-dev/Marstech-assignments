# 03-landing-page

Simple responsive landing page template (DARKBEAST) demonstrating a hero section, services, pricing, and contact sections.

## Project structure

- `index.html` — main page
- `assets/css/style.css` — styles (responsive rules and hero headline sizing)
- `assets/js/script.js` — behaviors (back-to-top, progress bar, animations)
- `assets/images/` — images used by the site

## Run locally

1. Open a terminal in the project root: `03-landing-page`
2. Start a simple static server (Python 3):

```bash
python -m http.server
```

3. Open `http://localhost:8000` in your browser.

## Editing tips

- Adjust the hero headline sizing in `assets/css/style.css` (`.hero-title`). The file uses responsive sizing with `clamp()` so the full sentence fits across viewports.
- CSS, images, and scripts are under the `assets/` folder; update those paths from `index.html` if you move files.
- Test responsiveness using your browser's device toolbar (mobile/tablet widths).

## Notes

- If text still overflows on a particular device, try lowering the `clamp()` max value (e.g., `clamp(1.2rem, 4.5vw, 2.8rem)`) or add a small breakpoint in `assets/css/style.css`.
- For quick changes, refresh the browser after editing CSS; disable caching if needed (DevTools -> Network -> Disable cache).

---

If you want, I can add a `package.json` + dev server or wire up live-reload for faster iteration.
# 03-landing-page