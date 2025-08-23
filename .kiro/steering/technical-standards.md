---
inclusion: always
---

# Technical Standards & Guidelines

## Technology Stack
- **CSS Framework**: Tailwind CSS v4.1
- **JavaScript**: Minimal vanilla JS + HTMX for dynamic content
- **Icons**: Iconify icons via @iconify/tailwind4 plugin
- **Build Process**: Direct CSS compilation, no complex build pipeline

## Code Standards

### HTML
- Use semantic HTML5 elements
- Include proper accessibility attributes (alt text, ARIA labels, skip links)
- Maintain proper heading hierarchy (h1 → h2 → h3)
- Use meaningful class names and IDs

### CSS (Tailwind)
- Use Tailwind utility classes primarily
- Custom CSS only in @layer components for reusable patterns
- Follow mobile-first responsive design principles
- Use CSS custom properties for brand colors and consistent theming

### JavaScript
- Minimize JavaScript usage - prefer CSS-only solutions when possible
- Use HTMX for dynamic content loading instead of complex JS frameworks
- Keep functions small and focused
- Use modern ES6+ syntax
- Add comments for complex logic

## Responsive Design
- **Mobile First**: Design for mobile, enhance for larger screens
- **Breakpoints**: sm (640px), md (768px), lg (1024px), xl (1280px)
- **Typography Scale**: Responsive text sizing (text-3xl sm:text-4xl lg:text-6xl)
- **Spacing**: Consistent spacing scale using Tailwind's spacing system

## Performance Guidelines
- Optimize images (use SVG for logos and icons)
- Minimize HTTP requests
- Use efficient CSS selectors
- Lazy load non-critical content
- Keep JavaScript bundle small

## Accessibility Requirements
- WCAG 2.1 AA compliance
- Keyboard navigation support
- Screen reader compatibility
- Sufficient color contrast ratios
- Focus indicators on interactive elements
- Skip links for main content

## File Organization
```
/
├── index.html (main page)
├── input.css (Tailwind source)
├── static/
│   ├── css/styles.css (compiled CSS)
│   ├── js/htmx.min.js
│   ├── images/ (optimized images)
│   └── partials/ (HTMX partial templates)
│        ├── terms.html 
│        └── privacy.html 
└── .kiro/
    ├── steering/ (project guidelines)
    └── specs/ (feature specifications)
```