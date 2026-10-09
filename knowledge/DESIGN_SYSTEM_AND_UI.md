# Design System & UI/UX Guidelines

## 1. Design Philosophy

The APEX Media platform is styled after the design principles of modern Stripe:
- **Strong Typography**: Confident typography with clear hierarchy.
- **Visual Depth**: Layered compositions using dark space tones, radial gradients, photography, and subtle shadows.
- **Motion with Purpose**: Meaningful micro-animations that communicate relationships and state transitions without being distracting.
- **Modular Storytelling**: Composed of distinct editorial modules that create visual rhythm (alternating between deep dark and light modules).
- **Product-Level Polish**: Every button, input, hover state, and sparkline conveys enterprise-level design craft.
- **Accessibility**: Strict contrast compliance and automatic respect for `prefers-reduced-motion`.

---

## 2. Color Palette

### Primary Base Colors (Dark Theme Canvas)
- **Deep Void / Background**: `#070A12` (Used for hero canvas, intelligence module, and footer)
- **Obsidian Navy**: `#0B0F19` (Used for navigation header, breaking ticker, and cards container)
- **Card Surface Slate**: `#0D121F` (Used for card surfaces, table containers, and panels)
- **Card Surface Elevated**: `#0F172A` (Used for dropdowns, modals, and search dialog)

### Accent & Editorial Colors
- **APEX Brand Red**: `#DC2626` (Red 600) / Hover: `#EF4444` (Red 500)
- **Electric Blue**: `#38BDF8` (Sky 400) / `#60A5FA` (Blue 400)
- **Luminous Cyan**: `#22D3EE` (Cyan 400)
- **Digital Indigo / Violet**: `#818CF8` (Indigo 400) / `#A855F7` (Purple 500)
- **Market Bull Green**: `#34D399` (Emerald 400) / `#10B981` (Emerald 500)
- **Market Bear Rose**: `#FB7185` (Rose 400) / `#F43F5E` (Rose 500)

### Electric Gradient Recipes
```css
/* Hero Headline Gradient */
background-image: linear-gradient(to right, #60A5FA, #A5B4FC, #67E8F9);
-webkit-background-clip: text;
color: transparent;

/* Newsletter CTA Ambient Radial */
background: radial-gradient(circle at 50% 50%, rgba(30, 58, 138, 0.35), transparent 70%);
```

---

## 3. Typography Hierarchy

| Role | Font Family | Tailwind Class | Usage |
| :--- | :--- | :--- | :--- |
| **Interface / Sans** | `Inter` / `Instrument Sans` | `font-sans` | Body text, UI controls, navigation links, market tickers, metrics. |
| **Brand Masthead** | `Cinzel` | `font-brand` | Official APEX masthead logo, list seals, luxury badges. |
| **Editorial Display** | `Newsreader` | `font-headline` | Long-form story headlines, pull quotes, investigative features. |

### Font Sizes & Leading
- **Hero Title**: `text-4xl sm:text-6xl xl:text-7xl font-black tracking-tight leading-[1.08]`
- **Section Headers**: `text-2xl sm:text-3xl font-bold tracking-tight`
- **Article Card Headlines**: `text-sm sm:text-base font-bold leading-snug`
- **Meta / Eyebrows**: `text-[10px] sm:text-[11px] font-extrabold uppercase tracking-widest`

---

## 4. Animation & Motion System (`resources/css/app.css`)

```css
/* Smooth Continuous Marquee Ticker */
@keyframes marquee {
    0% { transform: translateX(0%); }
    100% { transform: translateX(-50%); }
}

/* Ambient Radial Mesh Flow */
@keyframes gradient-mesh {
    0%, 100% {
        background-position: 0% 50%;
        filter: hue-rotate(0deg);
    }
    50% {
        background-position: 100% 50%;
        filter: hue-rotate(15deg);
    }
}

/* Micro-Pulse */
@keyframes pulse-subtle {
    0%, 100% { opacity: 0.8; transform: scale(1); }
    50% { opacity: 1; transform: scale(1.02); }
}

.animate-marquee {
    display: flex;
    width: max-content;
    animation: marquee 35s linear infinite;
}

.animate-marquee:hover {
    animation-play-state: paused;
}

/* Accessibility: Reduced-Motion Overrides */
@media (prefers-reduced-motion: reduce) {
    .animate-marquee {
        animation: none;
        transform: none;
        overflow-x: auto;
    }
    .animate-mesh {
        animation: none;
    }
    .animate-pulse-subtle {
        animation: none;
    }
}
```

---

## 5. Responsive Viewport Standards

| Breakpoint | Width | Layout Behavior |
| :--- | :--- | :--- |
| **Mobile (`sm`)** | `375px – 640px` | Single-column stack, compact ticker, collapsible navigation drawer, full-width touch targets. |
| **Tablet (`md`)** | `641px – 1024px` | 2-column article grids, compressed table rows, compact header. |
| **Desktop (`lg`)** | `1025px – 1280px` | 4-column category ecosystem, full horizontal card rails, sticky blurred nav. |
| **Widescreen (`xl` / `2xl`)** | `1440px+` | Max width constrained to `1600px` with centered generous gutters. |
