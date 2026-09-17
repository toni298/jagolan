# CSS Structure Documentation

## Overview

CSS telah di-refactor menjadi struktur modular untuk memudahkan maintenance dan persiapan Theme System.

## File Structure

```
resources/css/
├── public.css                 # Entry point - imports all modules
└── public/
    ├── base/
    │   ├── _variables.css      # CSS custom properties
    │   ├── _reset.css          # Browser reset & base styles
    │   ├── _animations.css     # Animation preferences
    │   ├── _responsive-tablet.css  # Tablet breakpoints
    │   └── _responsive-mobile.css  # Mobile breakpoints
    ├── layout/
    │   ├── _container.css      # Container & section layouts
    │   ├── _header.css         # Header & navigation
    │   └── _footer.css         # Footer styles
    ├── components/
    │   ├── _buttons.css        # Button styles
    │   └── _dropdown.css       # Dropdown components
    └── pages/
        ├── _hero.css           # Hero section
        ├── _about.css          # About section
        ├── _values.css         # Core values section
        ├── _projects.css       # Projects grid
        ├── _visi-misi.css      # Vision & mission
        ├── _estate.css         # Estate section
        ├── _products.css       # Products page
        └── _product-detail.css # Product detail page
```

## Build Process

CSS dicompile oleh Vite menggunakan `@import` statements.

### Development

```bash
npm run dev
```

### Production

```bash
npm run build
```

## Important Notes

1. **Pixel-Perfect Preservation**: Semua CSS telah dipindahkan tanpa perubahan untuk menjaga tampilan yang identik
2. **CSS Variables**: Sudah menggunakan CSS custom properties di `_variables.css`
3. **Import Order**: Urutan import di `public.css` sangat penting (Base → Layout → Components → Pages → Responsive)
4. **Vite Integration**: CSS di-load via `@vite('resources/css/public.css')` di layout

## Next Steps

Setelah struktur CSS stabil, tahap selanjutnya:

1. ✅ Refactoring selesai
2. ⏳ Testing pixel-perfect match
3. ⏳ Implementasi Theme System dengan CSS Variables
4. ⏳ Add theme switcher functionality

## Files to Clean Up

After verification:

- `split_css.py` (Python script - not used)
- `split-css.cjs` (Node.js script - temporary)
- `public/css/public.css` (original monolithic file - keep as backup)
