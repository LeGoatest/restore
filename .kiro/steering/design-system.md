---
inclusion: always
---

# Design System Guidelines

## Color Palette

### Primary Colors
- **Brand Primary**: `#f1c40f` (Golden Yellow) - Main brand color, buttons, highlights
- **Brand Primary Hover**: `#d4ac0d` - Hover states for primary elements
- **Brand Secondary**: `#2c3e50` - Dark blue-gray for contrast, secondary buttons
- **Brand Secondary Hover**: `#1a252f` - Hover states for secondary elements

### Neutral Colors
- **Background**: `#ffffff` (White) - Main background
- **Surface**: `#f9fafb` (Light Gray) - Card backgrounds, sections
- **Text Primary**: `#000000` (Black) - Main text color
- **Text Secondary**: `#6b7280` (Gray-500) - Secondary text, descriptions
- **Border**: `#e5e7eb` (Gray-200) - Borders, dividers

## Typography

### Font Family
- Primary: `font-sans` (system font stack)
- Fallback: `-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif`

### Type Scale
- **Hero Headings**: `text-3xl sm:text-4xl md:text-5xl lg:text-6xl`
- **Section Headings**: `text-2xl sm:text-3xl lg:text-4xl`
- **Card Headings**: `text-lg font-medium`
- **Body Text**: `text-base sm:text-lg`
- **Small Text**: `text-sm`

### Font Weights
- **Bold**: `font-bold` (700) - Main headings
- **Semibold**: `font-semibold` (600) - Subheadings, buttons
- **Medium**: `font-medium` (500) - Card titles
- **Normal**: `font-normal` (400) - Body text

## Component Patterns

### Buttons
```css
.btn-primary {
  @apply text-black px-6 py-3 rounded-full font-semibold transition-colors duration-200;
  background-color: var(--color-brand-primary);
}

.btn-secondary {
  @apply text-white px-6 py-3 rounded-full font-semibold transition-colors duration-200;
  background-color: var(--color-brand-secondary);
}
```

### Cards
```css
.service-card {
  @apply rounded-lg p-4 sm:p-6 shadow-sm hover:shadow-md transition-shadow duration-200;
  background-color: var(--color-input-bg);
}
```

### Icons
```css
.service-icon {
  @apply w-10 h-10 sm:w-12 sm:h-12 text-black rounded-md flex items-center justify-center mb-3 sm:mb-4;
  background-color: var(--color-brand-primary);
}
```

## Layout Patterns

### Container Widths
- **Full Width**: `max-w-7xl mx-auto` - Main content container
- **Narrow**: `max-w-4xl mx-auto` - FAQ, forms, text-heavy content
- **Padding**: `px-4 sm:px-6 lg:px-8` - Consistent horizontal padding

### Grid Systems
- **Services**: `grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8`
- **Two Column**: `grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12`
- **Location Grid**: `grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6`

### Spacing Scale
- **Section Padding**: `py-8 sm:py-12 lg:py-16`
- **Element Margins**: `mb-4 sm:mb-6 lg:mb-8`
- **Grid Gaps**: `gap-4 sm:gap-6 lg:gap-8`

## Interactive Elements

### Hover States
- **Cards**: `hover:shadow-md`
- **Buttons**: `hover:bg-gray-800` (for primary), custom colors for brand buttons
- **Links**: `hover:underline` or `hover:text-yellow-500`

### Focus States
- **Form Elements**: `focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500`
- **Buttons**: Built-in browser focus indicators

### Transitions
- **Standard**: `transition-colors duration-200`
- **Shadows**: `transition-shadow duration-200`
- **Transforms**: `transition-transform duration-200`

## Responsive Behavior

### Breakpoint Strategy
1. **Mobile First**: Design for 320px+ screens
2. **Small**: 640px+ (sm:) - Tablet portrait
3. **Medium**: 768px+ (md:) - Tablet landscape
4. **Large**: 1024px+ (lg:) - Desktop
5. **Extra Large**: 1280px+ (xl:) - Large desktop

### Content Adaptation
- **Text**: Center on mobile, left-align on desktop
- **Images**: Stack on mobile, side-by-side on desktop
- **Navigation**: Hamburger menu on mobile, horizontal on desktop
- **Forms**: Single column on mobile, may use multiple columns on desktop