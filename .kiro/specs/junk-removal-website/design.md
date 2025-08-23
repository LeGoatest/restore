# Design Document - The Pick-up Chicks Website

## Overview

This design document outlines the technical architecture and user experience design for The Pick-up Chicks junk removal website. The design emphasizes simplicity, performance, and conversion optimization while maintaining the playful brand personality.

## Architecture

### Technology Stack
- **Frontend**: HTML5, Tailwind CSS v4.1, minimal vanilla JavaScript
- **Dynamic Content**: HTMX for modal content loading
- **Icons**: Iconify icons via Tailwind plugin
- **Build Process**: Direct CSS compilation, no complex bundling
- **Hosting**: Static hosting compatible (Netlify, Vercel, etc.)

### File Structure
```
/
├── index.html                 # Main landing page
├── input.css                  # Tailwind source file
├── terms.html                 # Terms of service page
├── privacy.html               # Privacy policy page
├── static/
│   ├── css/
│   │   └── styles.css         # Compiled Tailwind CSS
│   ├── js/
│   │   └── htmx.min.js        # HTMX library
│   ├── images/
│   │   ├── The-Pick-Up-Chicks-Logo.svg
│   │   ├── pick-up-chicks-junk-removal-image-1.svg
│   │   └── pick-up-chicks-junk-removal-box-truck.svg
│   └── partials/
│       ├── terms.html         # Terms modal content
│       ├── privacy.html       # Privacy modal content
│       └── empty.html         # Empty content for closing modals
└── .kiro/
    ├── steering/              # Project guidelines
    └── specs/                 # Feature specifications
```

## Components and Interfaces

### Header Component
- **Logo**: SVG logo with proper alt text
- **Navigation**: Desktop horizontal menu, mobile hamburger menu
- **CTA Button**: Prominent "Get a quote" button
- **Responsive Behavior**: Collapses to hamburger menu below 768px

### Hero Section
- **Layout**: Two-column on desktop, stacked on mobile
- **Content**: Main headline, subheadline, two CTA buttons
- **Visual**: Brand mascot illustration
- **Background**: Golden yellow brand color with rounded corners
- **Responsive**: Image order changes on mobile (image first, text second)

### Services Grid
- **Layout**: CSS Grid with responsive columns (1/2/3 columns)
- **Cards**: Icon, title, description with hover effects
- **Icons**: Iconify icons for consistency and performance
- **Responsive**: Adapts from single column to three columns

### Trust Indicators Section
- **Layout**: Three-column grid showcasing key differentiators
- **Content**: Woman-owned, community-focused, satisfaction guaranteed
- **Icons**: Custom SVG icons for each trust indicator
- **Styling**: Consistent with service cards

### Service Areas Section
- **Layout**: Grid of location names
- **Content**: Cities and towns served
- **Responsive**: Adapts from 2 columns to 6 columns
- **Styling**: Simple cards with location names

### FAQ Section
- **Component**: HTML `<details>` and `<summary>` elements
- **Behavior**: CSS-only accordion (one open at a time)
- **Animation**: Smooth expand/collapse with icon rotation
- **Content**: 8+ common questions with comprehensive answers

### Contact Footer
- **Layout**: Two-column on desktop, single column on mobile
- **Left Column**: Contact information, business hours, social links
- **Right Column**: Contact form with validation
- **Background**: Golden yellow brand color
- **Form Fields**: Name, phone, email, reason, message

### Modal System
- **Technology**: HTMX for content loading
- **Trigger**: Footer links for Terms and Privacy
- **Behavior**: Overlay with background blur
- **Content**: Loaded dynamically from partial files
- **Closing**: Click outside, Escape key, or close button

## Data Models

### Contact Form Data
```typescript
interface ContactForm {
  name: string;           // Required, min 2 characters
  phone: string;          // Required, phone format validation
  email: string;          // Required, email format validation
  reason: string;         // Required, dropdown selection
  message: string;        // Optional, max 1000 characters
}
```

### Service Data
```typescript
interface Service {
  id: string;             // Unique identifier
  title: string;          // Service name
  description: string;    // Brief description
  icon: string;           // Iconify icon name
}
```

### FAQ Data
```typescript
interface FAQ {
  id: string;             // Unique identifier
  question: string;       // Question text
  answer: string;         // Answer content (HTML allowed)
}
```

## Error Handling

### Form Validation
- **Client-side**: HTML5 validation attributes
- **Visual Feedback**: Red borders and error messages for invalid fields
- **Success State**: Green confirmation message after submission
- **Fallback**: Server-side validation for security

### HTMX Error Handling
- **Network Errors**: Graceful degradation to static pages
- **Content Loading**: Fallback to direct page navigation
- **Modal Errors**: Close modal and show error message

### Accessibility Errors
- **Missing Alt Text**: All images have descriptive alt attributes
- **Keyboard Navigation**: All interactive elements are keyboard accessible
- **Screen Reader**: Proper ARIA labels and semantic HTML

## Testing Strategy

### Manual Testing
1. **Cross-browser Testing**: Chrome, Firefox, Safari, Edge
2. **Device Testing**: Mobile phones, tablets, desktop screens
3. **Accessibility Testing**: Screen reader compatibility, keyboard navigation
4. **Performance Testing**: Page load times, image optimization

### Automated Testing
1. **HTML Validation**: W3C markup validator
2. **CSS Validation**: W3C CSS validator
3. **Accessibility**: axe-core automated accessibility testing
4. **Performance**: Lighthouse performance audits

### User Testing
1. **Task Completion**: Can users find services and contact information?
2. **Form Usability**: Can users successfully submit the contact form?
3. **Mobile Experience**: Is the mobile experience intuitive?
4. **FAQ Usability**: Can users find answers to common questions?

## Performance Considerations

### Image Optimization
- **Format**: SVG for logos and icons, optimized PNG/WebP for photos
- **Sizing**: Responsive images with appropriate dimensions
- **Loading**: Lazy loading for non-critical images

### CSS Optimization
- **Tailwind Purging**: Remove unused CSS classes in production
- **Critical CSS**: Inline critical styles for above-the-fold content
- **Minification**: Compress CSS for production

### JavaScript Optimization
- **Minimal JS**: Only essential JavaScript functionality
- **HTMX**: Lightweight library for dynamic content
- **No Frameworks**: Avoid heavy JavaScript frameworks

### Caching Strategy
- **Static Assets**: Long cache times for CSS, JS, images
- **HTML**: Short cache times for content updates
- **CDN**: Use CDN for static asset delivery

## Security Considerations

### Form Security
- **Input Validation**: Server-side validation for all form inputs
- **CSRF Protection**: Cross-site request forgery protection
- **Rate Limiting**: Prevent form spam and abuse

### Content Security
- **HTTPS**: All content served over secure connections
- **Content Security Policy**: Restrict resource loading
- **XSS Prevention**: Sanitize user inputs

### Privacy Compliance
- **Data Collection**: Minimal data collection, clear privacy policy
- **Cookie Usage**: Minimal cookie usage, proper consent
- **GDPR Compliance**: Privacy policy covers data handling

## Deployment Strategy

### Build Process
1. **CSS Compilation**: Compile Tailwind CSS from input.css
2. **Asset Optimization**: Optimize images and minify CSS/JS
3. **HTML Validation**: Validate HTML markup
4. **Accessibility Check**: Run automated accessibility tests

### Hosting Requirements
- **Static Hosting**: Compatible with Netlify, Vercel, GitHub Pages
- **Custom Domain**: Support for custom domain configuration
- **SSL Certificate**: Automatic HTTPS certificate provisioning
- **Form Handling**: Integration with form processing service (Netlify Forms, Formspree)

### Monitoring
- **Analytics**: Google Analytics for traffic monitoring
- **Performance**: Core Web Vitals monitoring
- **Uptime**: Website uptime monitoring
- **Form Submissions**: Track form completion rates