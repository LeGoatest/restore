# Implementation Plan - The Pick-up Chicks Website

## Overview

This implementation plan addresses the critical issues found in the current `index.html` file and ensures the website meets the established brand guidelines and technical standards. The tasks focus on fixing existing problems and completing missing functionality.

## Critical Issues Identified

1. **Brand Color Inconsistency**: Navigation links use `hover:text-red-600` instead of brand yellow
2. **Missing Background Colors**: Body and header elements missing proper background colors
3. **Incomplete Form Styling**: Contact form inputs missing proper background colors
4. **Missing Service Content**: Services section appears to be incomplete
5. **Broken CSS Classes**: Several elements have incomplete class attributes (empty spaces)
6. **Desktop Layout Issues**: Responsive changes broke original desktop layout structure
7. **CSS Utilization**: Not properly using input.css custom classes and components

## Implementation Tasks

- [x] 1. Update all copy and branding for Restore Removal





  - Replace "The Pick-up Chicks" with "Restore Removal" throughout the site
  - Update phone number from (262) 470-3925 to (239) 412-1566
  - Change service areas from Wisconsin locations to Florida locations
  - Update business hours to Mo-Fr 07:00-18:00, Sa 08:00-18:00
  - Remove chicken-themed messaging and replace with restoration/renewal messaging
  - _Requirements: Brand accuracy and consistency_

- [x] 2. Fix CSS custom properties and component utilization





  - Update input.css to properly define all brand colors and component classes
  - Replace hardcoded Tailwind classes with custom component classes where appropriate
  - Ensure btn-primary, btn-secondary, service-card, and other components work correctly
  - _Requirements: Proper CSS architecture per technical-standards.md_

- [x] 3. Fix brand color consistency across all elements





  - Replace all `red-600`, `red-500`, `red-700` classes with brand yellow equivalents
  - Update navigation hover states to use custom brand colors from input.css
  - Fix mobile menu button focus ring to use yellow instead of red
  - _Requirements: Brand consistency per design-system.md_

- [x] 4. Restore proper desktop layout structure




  - Fix hero section desktop layout that was broken during responsive updates
  - Restore proper two-column desktop layout with correct spacing and alignment
  - Ensure desktop navigation and header maintain original intended design
  - Test desktop layout at 1024px+ to ensure it matches original design intent
  - _Requirements: Responsive design that doesn't break desktop experience_




- [x] 5. Fix missing background colors and styling issues






  - Add proper `bg-white` class to body element (currently missing)
  - Add proper `bg-white` class to header element (currently missing)
  - Fix contact form input classes that have missing `bg-white` values
  - Fix contact form container that's missing `bg-white` class
  - _Requirements: Consistent styling per design-system.md_

- [x] 6. Complete services section implementation




  - [x] 6.1 Add missing service cards with proper content


    - Implement all 6+ service types (junk removal, home cleanout, garage cleanout, etc.)
    - Add proper Iconify icons for each service using `iconify-icons` elements
    - Ensure consistent styling with `service-card` and `service-icon` classes from input.css
    - _Requirements: 2.1, 2.2_

  - [x] 6.2 Fix services grid layout


    - Verify responsive grid classes are properly applied
    - Test single/double/triple column layouts across breakpoints
    - Ensure proper spacing and hover effects using CSS component classes
    - _Requirements: 2.3, 2.4, 2.5, 2.6_
-

- [x] 7. Fix hero section button styling




  - Replace hardcoded button styles with proper `btn-primary` and `btn-secondary` classes
  - Ensure buttons use brand colors defined in CSS custom properties
  - Test button hover states and accessibility
  - _Requirements: 1.2, 1.3, Brand consistency_
-

- [x] 8. Complete "Why Choose Us" section



  - [x] 8.1 Add missing trust indicator content


    - Update messaging to remove woman-owned references (not applicable to Restore Removal)
    - Implement cards for professional service, community-focused, satisfaction guaranteed
    - Add proper Iconify icons instead of inline SVG
    - Ensure consistent styling with other card components
    - _Requirements: 3.1, 3.2, 3.3, 3.4_

  - [x] 8.2 Fix service areas location cards


    - Add missing `bg-white` classes to location cards
    - Update all service areas to Florida locations (Homosassa Springs, Crystal River, etc.)
    - Test responsive grid behavior
    - _Requirements: 3.5_
-

- [x] 9. Fix contact form styling and functionality



  - [x] 9.1 Repair form input background colors


    - Add proper `bg-white` classes to all form inputs
    - Fix form container background styling
    - Ensure form inputs have proper opacity and styling
    - _Requirements: 4.2, 4.5_

  - [x] 9.2 Complete form validation and accessibility


    - Add proper form labels and ARIA attributes
    - Implement client-side validation with proper error states
    - Test form submission and user feedback
    - _Requirements: 4.5, 8.2, 8.3_

- [ ] 10. Verify and test HTMX modal functionality
  - [ ] 10.1 Test terms and privacy modal loading
    - Verify HTMX loads content from static/partials/ correctly
    - Test modal opening, closing, and background blur
    - Ensure modal content is properly styled
    - _Requirements: 6.1, 6.2, 6.3, 6.4_

  - [ ] 10.2 Fix modal styling issues
    - Ensure modal overlay has proper z-index and positioning
    - Test modal responsiveness on mobile devices
    - Verify escape key and click-outside functionality
    - _Requirements: 6.4, 6.5_
-

- [-] 11. Update CSS custom properties and component classes


  - [x] 11.1 Fix CSS variable definitions


    - Ensure all brand colors are properly defined in @theme
    - Fix any missing or incorrect CSS custom properties
    - Verify btn-primary and btn-secondary classes work correctly
    - _Requirements: Design system consistency_

  - [ ] 11.2 Test component class functionality


    - Verify service-card, service-icon, and form classes work
    - Test hover states and transitions
    - Ensure mobile-menu class functions properly
    - _Requirements: Component consistency_

- [ ] 12. Cross-browser and device testing
  - [ ] 12.1 Test brand color consistency
    - Verify yellow brand colors display correctly across browsers
    - Test hover states and transitions
    - Ensure color contrast meets accessibility standards
    - _Requirements: 8.6, Brand consistency_

  - [ ] 12.2 Mobile responsiveness testing
    - Test all sections on mobile devices
    - Verify touch targets are appropriately sized
    - Test form usability on mobile
    - _Requirements: 7.1, 7.2, 7.5_


- [x] 13. Create lead capture "squeeze page"




  - [x] 13.1 Design squeeze page layout


    - Create new HTML file (quote.html or lead-capture.html)
    - Use footer contact section design as the base template
    - Implement single-purpose page focused on lead generation
    - Update all copy for Restore Removal branding
    - _Requirements: Lead generation optimization_



  - [ ] 13.2 Implement squeeze page functionality
    - Create focused headline and value proposition for Restore Removal
    - Implement contact form similar to footer but as main page content


    - Add minimal navigation (logo + phone number (239) 412-1566)
    - Ensure mobile-responsive design matches footer styling


    - _Requirements: Conversion optimization_

- [ ] 14. Update SEO and metadata files

  - [ ] 14.1 Update schema.json for Restore Removal
    - Update business name from "The Pick-up Chicks" to "Restore Removal"
    - Update phone number to (239) 412-1566


    - Update service areas to Florida locations (Homosassa Springs, Crystal River, Inverness, etc.)
    - Update business hours to Mo-Fr 07:00-18:00, Sa 08:00-18:00
    - Update geographic coordinates to latitude: 27.994402, longitude: -82.442515
    - Update business address and service area descriptions
    - _Requirements: Local SEO optimization and accurate business data_

  - [ ] 14.2 Create and update LLM training files
    - Update robots.txt to reference llms.txt and llms-full.txt files
    - Create llms.txt with basic business information for AI training
    - Create llms-full.txt with comprehensive business data for AI training
    - Include service areas, contact info, business hours, and service descriptions

    - Ensure AI assistants have accurate Restore Removal information
    - _Requirements: AI/LLM discoverability and accurate business representation_

- [ ] 15. Performance optimization and final validation
  - [ ] 15.1 CSS compilation and optimization
    - Compile Tailwind CSS and verify all custom classes from input.css are working
    - Remove unused CSS classes
    - Test loading performance
    - _Requirements: 8.1_

  - [ ] 15.2 Final accessibility and SEO audit



    - Run automated accessibility tests
    - Verify proper heading hierarchy and alt text
    - Test keyboard navigation throughout the site
    - Update all SEO meta tags for Restore Removal and Florida locations
    - _Requirements: 8.2, 8.3, 8.4, 9.1_