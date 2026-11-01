# Release Logs: blaze/admin-core

This document details all features, enhancements, refactors, and schema changes introduced in the `main` branch since tag `1.0` (`14d4a67`).

---

## 📊 Release Summary

| Metric | Details |
|---|---|
| **Base Version** | `1.0` (commit `14d4a67`) |
| **Current Branch** | `main` (commit `6770cfe`) |
| **Total Commits** | 45 commits |
| **Files Changed** | 165 files (`+14,022` additions, `-1,601` deletions) |
| **Key Additions** | Menu Builder, FAQs, Careers/Job Applications, Legal Documents, Affiliations, Payment Methods, Admin Help Center, Extensible Configuration System |

---

## 🌟 Major Highlights

### 1. Extensible Architecture & Configuration (`AdminCoreConfiguration`)
- Introduced `AdminCoreConfiguration` as a dependency-injected singleton that consuming applications can extend.
- **Dynamic Module Toggling**: Enable/disable core modules (`menus`, `services`, `pages`, `careers`, `affiliations`, `payment-methods`, `faqs`, etc.) on a per-site basis via `modules()`.
- **Feature Flags Engine**: Configurable feature toggles via `features()` and `featureEnabled()` (e.g. `hero_highlighted_text`, `service_featured`).
- **Site-Specific Form Field Injections**: Custom field descriptors (`ServiceFormField`) allow client repositories to inject extra form fields into Services (`serviceFormFields()`) and Service Features/Itineraries (`serviceFeatureFormFields()`) without modifying package code.
- **Custom Sidebar Extension**: Inject project-specific menu items directly into existing admin modules via `customSidebarItems()`.
- **Homepage Section Definitions**: Predefine site-specific layout section blueprints (`homepageSectionDefinitions()`).
- **Route Link Registry**: Registry of available static frontend routes (`staticRoutes()`) and automatic missing root route detection (`missingRootRoutes()`).

### 2. Interactive Menu Management & Tree Builder
- **Complete Menu Engine**: Added `Menu` and `MenuItem` models with full recursive hierarchy and ordering support.
- **Visual Drag-and-Drop Tree Builder**: Custom interactive UI (`menus/builder.blade.php`) supporting drag-to-reorder, nesting depth indentation, parent assignment, and link targets (`_self`, `_blank`).
- **Dynamic Link Resolution**: Link menu items directly to Services, Service Categories, Blogs, Dynamic Pages, Static Routes, or Custom external URLs.
- **Missing Root Paths Detection**: Automatic audit of static application routes that are unlinked in the primary navigation.
- **Query Optimization**: Highly optimized menu tree loading with single-pass relational eager loading.

### 3. Services & Features (Itineraries) Architecture
- **Tabbed Management Interface**: Refactored Service management into unified tabs: *General*, *Features* (Itineraries), *Gallery / Cover Images*, and *SEO & Metadata*.
- **Extensible Extra Fields**: Full support for project-level schema extensions (`_extra_fields.blade.php`) and custom feature form inputs.
- **Feature Flag Control (`service_featured`)**: Controlled the `is_featured` toggle switch and list column via `service_featured` feature flag, allowing client projects to disable the homepage feature flag cleanly.
- **Frontend Site Previews**: Integrated external site preview links directly within service list titles.
- **Gallery Album Linking**: Ability to assign a shared `GalleryAlbum` directly to a Service, or upload ad-hoc cover images.

### 4. Comprehensive Website Settings & Dynamic Homepage Sections
- **Multi-Tab CMS Configuration**:
  - *General*: Branding, logos (light & dark mode), favicon, copyright, default meta.
  - *Hero Section*: Title, subtitle, primary/secondary buttons, optional highlighted text gradient flag (`hero_highlighted_text`).
  - *About Us*: Company overview, imagery, core value statements.
  - *Subpages & Sections*: Per-module banner titles, subtitles, and badges.
  - *Social & Contact*: Integrated mapping and contact detail overrides.
  - *System & Scripts*: Custom header/body scripts, Google Analytics, maintenance toggle.
- **Dynamic Homepage Sections**: Modular page builder where sections can be created, ordered via drag-and-drop, activated/deactivated, and configured dynamically.
- **Performance**: Migrated homepage section rendering to Blade with automated cache tag flushing on updates.

### 5. FAQs Module
- **FAQ Management**: Dedicated `Faq` model, `FaqController`, and responsive administration views.
- **Category & Ordering Support**: Categorized FAQs with drag-to-reorder capabilities.
- **Polymorphic Model Integration**: Added `HasFaqs` trait allowing FAQs to be attached directly to Services and other models via `<x-admin::faq-picker>`.

### 6. Careers & Job Applicant Tracking Module
- **Job Categories & Postings**: Full recruitment pipeline management with `JobCategory`, `Job`, and `JobApplication` models.
- **Applicant Review Center**: Dedicated view for job applicants (`job_applications/index.blade.php`, `job_applications/show.blade.php`) with resume downloads, applicant metadata, and status pipeline management (`pending`, `reviewed`, `shortlisted`, `rejected`, `hired`).

### 7. Universal Tagging System
- **Centralized Tags Management**: Added `Tag` model, `TagController`, and routes for managing site-wide tags.
- **`HasTags` Trait**: Polymorphic tagging integration across Services, Blogs, Projects, and Gallery Albums.
- **Interactive UI**: Multi-tag badges with counter overflows (`+N more`) in index tables.

### 8. Legal Documents, Partner Affiliations & Payment Methods
- **Legal Documents**: Dedicated CMS manager for Terms of Service, Privacy Policies, Legal Notices, and Compliance PDFs.
- **Affiliations**: Partner, accreditation, and industry association logo showcase with active toggle switches.
- **Payment Methods**: Accepted payment badge manager with quick status toggles.

### 9. Media & Gallery Overhaul
- **Centralized Media Handler**: Added `MediaUploadController` and `HandlesMedia` trait for standardized storage, deletion, and public URL generation.
- **Featured Albums**: Added `is_featured` toggle flag to `GalleryAlbum` for prioritized public presentation.
- **Refined File Uploaders**: Drag-and-drop image and document upload components with live thumbnail previews and replacement confirmation.

### 10. Admin Help Center & Documentation
- **Built-In Documentation Portal**: Added `HelpController` and interactive documentation page (`resources/views/help/index.blade.php`).
- Provides client administrators and content managers with comprehensive guides on navigation, content publishing, media specs, and SEO best practices.

### 11. Performance, Cache & UI Enhancements
- **Cache Management**: Added `HasCache` trait with automatic cache tag invalidation on updates and sort-order reordering, plus a dedicated manual cache clear endpoint (`POST admin/cache/clear`).
- **Enquiry Optimization**: Streamlined `EnquiryController` index and show views, reducing redundant database queries.
- **Customizable Admin Sidebar Component**: Dynamic module rendering, submenus, badge counters, and active route matching.

### 12. Team Members & Public Profile Slugs
- **Team Member Slugs**: Added unique `slug` column to `team_members` table (`2026_11_01_000001_add_slug_to_team_members_table.php`).
- **Automated Slug Generation**: Automatic slug generation upon saving in `TeamMember` model, with collision handling (`name-1`, `name-2`).
- **Public Profile Resolution**: Added `$member->url` accessor checking for `team.show` route availability.
- **Admin Management & Previews**: Integrated external public preview link in the team members index table, and added editable `slug`, `department`, `email`, and `phone` form fields with controller validation and sanitization.

---

## 🗂️ Detailed Commit Log (`1.0` -> `HEAD`)

| Commit | Date | Description |
|---|---|---|
| `6770cfe` | 2026-10-31 | `feat: add service management module with views, controller, and configuration support` |
| `67d423a` | 2026-10-31 | `feat: add menu management controller, views, and admin sidebar component` |
| `567d68e` | 2026-10-31 | `feat: add menu and menu item management controllers, models, configuration, and views` |
| `64f39ab` | 2026-10-31 | `feat: add website settings module with admin configuration and management views` |
| `b69dfbf` | 2026-10-28 | `feat: add website settings index blade view for managing admin configurations` |
| `86d93f2` | 2026-10-28 | `feat: add Service model and admin sidebar component` |
| `510f562` | 2026-10-26 | `feat: add FAQ management feature with CRUD operations and admin views` |
| `f19ea72` | 2026-10-26 | `feat: add admin core configuration, help controller, sidebar component, and routes` |
| `a3f0ccb` | 2026-10-26 | `feat: add admin sidebar component for admin-core package` |
| `4dbe0fb` | 2026-10-22 | `feat: add admin sidebar component with dynamic configuration and menu items` |
| `f11f043` | 2026-10-22 | `feat: add admin sidebar component, website settings view, and configuration class` |
| `ebfbc12` | 2026-10-22 | `feat: add menu management controllers, models, and views for admin core` |
| `5c21399` | 2026-10-14 | `feat: add admin-core package models, controllers, views, and routing for admin panel management` |
| `399d622` | 2026-10-12 | `feat: make service category required and add url accessor to Service model` |
| `7ee17f5` | 2026-10-12 | `feat: add dynamic service category support to menus and implement cache clearing controller` |
| `b47c353` | 2026-10-12 | `feat: add support for associating multiple services with testimonials` |
| `023ec0a` | 2026-10-11 | `refactor: replace Alpine.js rendering with Blade for homepage sections and add cache flushing to sort order trait` |
| `fc49a0d` | 2026-10-11 | `feat: add admin core package features including controllers, models, routes, and views` |
| `b58d5f4` | 2026-10-08 | `feat: add company info admin view with contact and social links tabs` |
| `6117969` | 2026-10-08 | `feat: add CompanyInfoController and company info management view to admin core` |
| `517966c` | 2026-10-06 | `feat: add company info controller, website setting controller, and settings view` |
| `49f8e93` | 2026-10-06 | `feat: add admin views for creating and listing testimonials` |
| `cf32ba8` | 2026-10-06 | `feat: add newsletter subscription module and company info management views to admin core` |
| `923d082` | 2026-10-06 | `feat: add menu management, dynamic pages, and admin controllers to admin-core package` |
| `fc937c2` | 2026-10-05 | `feat: add menu management, dynamic pages, and admin controllers to admin-core package` |
| `611290d` | 2026-10-05 | `feat: add page management models, controllers, views, and menu refactoring migrations` |
| `876f174` | 2026-10-04 | `feat: add Testimonial, ContactMessage, and TeamMember Eloquent models` |
| `b61be59` | 2026-10-03 | `feat: add service management with model, controller, filterable trait, and index view` |
| `f0b2aa5` | 2026-10-02 | `feat: add blog and service modules, media handling trait, and admin routes` |
| `2bb475d` | 2026-10-02 | `feat: add admin form views, controllers, and models for core modules` |
| `d0136ee` | 2026-10-01 | `feat: add WebsiteSettingController and index view for managing website settings` |
| `5cf93d5` | 2026-09-27 | `feat: add admin controllers, models, routes, and form views for blogs, services, and galleries` |
| `9c762e3` | 2026-09-27 | `feat: add core admin routes, models, controllers, and views` |
| `74f00be` | 2026-09-27 | `feat: add admin index views for gallery, testimonials, contact messages, and downloads` |
| `f9cfb8a` | 2026-09-26 | `feat: add WebsiteSettingController to manage website configurations and settings` |
| `5494f75` | 2026-09-26 | `refactor: use value attribute for gallery album select instead of item selected prop` |
| `f5bc9a7` | 2026-09-26 | `feat: add is_featured flag to gallery albums and update gallery item file handling` |
| `74c9a5e` | 2026-09-26 | `feat: add tag management support to blog posts` |
| `b44d85d` | 2026-09-26 | `refactor: remove HasSortOrder trait from Blog model` |
| `7b2facd` | 2026-09-26 | `feat: add google maps iframe support to contact information` |
| `ffe78f4` | 2026-09-26 | `Merge pull request #1 from sarozpradhan64/admin-core-taggings-services` |
| `e882aed` | 2026-09-26 | `feat: add Project model and admin form view` |
| `ad78d43` | 2026-09-25 | `feat: add admin modules for blogs, faqs, services, and website settings` |
| `a7c3e9d` | 2026-09-22 | `feat: add badge for additional tags in blog, gallery items, projects, and services views` |
| `2aeb922` | 2026-09-22 | `feat: add tag management functionality` |

---

## 🛠️ Upgrading & Integration Guide

1. **Register the Configuration Provider**:
   Ensure `config/admin-core.php` points to your project's custom configuration class:
   ```php
   return [
       'configuration' => App\Providers\AdminCoreConfiguration::class,
   ];
   ```
2. **Run Migrations**:
   Run database migrations to initialize tables for new modules (menus, FAQs, job applications, legal documents, affiliations, payment methods):
   ```bash
   php artisan migrate
   ```
3. **Configure Enabled Modules & Features**:
   In your `AdminCoreConfiguration` subclass, override `modules()` and `features()` to match your project's feature set:
   ```php
   public function modules(): array
   {
       return array_merge(parent::modules(), [
           'downloads' => false,
           'careers' => true,
       ]);
   }

   public function features(): array
   {
       return array_merge(parent::features(), [
           'hero_highlighted_text' => true,
           'service_featured' => false,
       ]);
   }
   ```
