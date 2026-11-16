# AERO DALLAS — WordPress theme

Custom classic theme for **AERO DALLAS — Industrial & Aviation Support**. Hand-coded PHP, plain CSS and vanilla JavaScript. No page builder, no parent theme and no required plugins.

- WordPress 6.5 or newer, PHP 8.1 or newer
- Built-in Request a Quote form (no form plugin needed)
- Fonts are self-hosted (Archivo Black, Open Sauce One); nothing is loaded from Google at runtime
- Light and dark modes with a toggle in the header

## Light and dark mode

The site has two looks that share all content:

- **Light mode:** bright photo hero, navy logo, and a floating quick-quote bar (service, equipment type, company) that opens the quote form already filled in.
- **Dark mode:** dark photo hero, white logo, and a strip of the four service areas under the hero.

Choose the default under **Appearance → Customize → AERO DALLAS — Company Details → Appearance (light / dark)**: Light, Dark, or follow the visitor's device setting. Visitors can switch with the sun/moon button in the header, and their choice is remembered on their device. You can hide the toggle in the same panel; the default then always applies.

The logo switches automatically: navy wordmark on light backgrounds, white wordmark on dark backgrounds (header in dark mode, footer, mobile menu). Both are traced SVGs with transparent backgrounds.

## Installation

1. In WordPress go to **Appearance → Themes → Add New → Upload Theme**.
2. Choose `aero-dallas.zip`, click **Install Now**, then **Activate**.

Or copy the `aero-dallas` folder into `wp-content/themes/` and activate it under **Appearance → Themes**.

## What activation sets up

Activating the theme builds the whole site. Running it again (for example after switching themes and back) does not create duplicates: existing pages, menus and content are detected and left as they are.

- **Six pages** with their templates: Home, About Us (`/about-us/`), Services (`/services/`), How We Work (`/how-we-work/`), Policies (`/policies/`), Contact Us (`/contact-us/`).
- **Home** is set as the static front page (Settings → Reading).
- **Menus**: "Primary Menu" (header) and "Footer Menu" (footer quick links), both assigned to their locations.
- **Site content**: the four services, seven process steps, six core values, six "Why choose" points and five FAQs, using the copy from the content brief.
- **Tagline** "Where industrial expertise meets dependable support." if the site had none.
- **Permalinks** set to "Post name" if they were still on the default plain setting.

## Editing content

| What | Where |
|---|---|
| Phone, email, address, hours, regions served, Instagram | Appearance → Customize → **AERO DALLAS — Company Details → Contact details** |
| Where quote requests are emailed | Same panel → **Quote notification email** |
| The quote call-to-action band (heading and text) | Same panel → **Quote call-to-action band** |
| Home hero photo, About photo, Vision & mission photo | Same panel → **Photos** |
| Inner page header photo | Set a **Featured image** on the page |
| Services (name, home card title, short text, detail list, photo, anchor) | **Site Content → Services** |
| How We Work steps | **Site Content → Process Steps** |
| Core values | **Site Content → Core Values** |
| "Why choose AERO DALLAS?" points | **Site Content → Why Choose Points** |
| FAQs | **Site Content → FAQs** |
| Client testimonials (hidden until at least one is published) | **Site Content → Testimonials** |
| Statistics band (hidden until a number and label are filled in) | Customize → AERO DALLAS — Company Details → **Home: statistics and insights** |
| "Latest Insights" (3 newest posts; off by default) | Same section → **Show "Latest Insights"** |
| Hero heading highlight (orange words) | Pages → Home → "Page sections" → **Hero heading highlight** |
| "How We Work" band and quote band photos | Customize → … → **Photos** |
| Page text (About "Who We Are", Services intro, How We Work intro, Contact intro, all Policies) | **Pages → edit the page** (main editor) |
| Page headings, hero text, vision, mission, commitment, section headings | **Pages → edit the page → "Page sections" box** below the editor |
| Search result description per page | **Pages → edit → "Search & sharing" box** |
| Header logo (optional replacement) | Appearance → Customize → Site Identity → Logo |
| Browser tab icon (optional replacement) | Appearance → Customize → Site Identity → Site Icon |

Notes:

- **Order** of services, steps, values, points and FAQs follows each item's *Order* field (lower first). Drafts and trashed items are hidden.
- **Empty fields do not render.** If the address or hours are blank, those rows are simply left out. Nothing like "To be confirmed" is ever shown.
- **Policies table of contents** is built automatically from the Heading blocks on the Policies page. Each heading's *HTML anchor* (block sidebar → Advanced) sets its link, e.g. `privacy` gives `/policies/#privacy`. The footer links to `#privacy`, `#quotation-payment`, `#cancellation-refund`, `#warranty` and `#health-safety-quality`, so keep those anchors.
- **Service anchors** (`industrial`, `aviation`, `parts-supply`, `logistics`) are used in links such as `/services/#aviation` and to pre-select the service on the quote form (`/contact-us/?service=aviation#quote`).
- **Photos**: to replace a service photo, set a *Featured image* on the service. Other photos are replaced in the Customizer. With no replacement set, the bundled photo is used.

## The Request a Quote form

The form is on the Contact Us page (`/contact-us/#quote`). Every "Request a Quote" button links there. Service-specific buttons pre-select the matching service.

**What happens on submit**

1. The submission is checked on the server: required fields, valid email and phone, a known service, and a file type and size check on any upload.
2. Spam protection without a CAPTCHA: a WordPress nonce, a hidden honeypot field, a signed time check (submissions faster than 3 seconds or older than 24 hours are rejected), and a limit of 5 submissions per visitor per 10 minutes.
3. The request is saved as a private **Quote Request** in the admin (left menu → **Quote Requests**). The list shows name, company, contact details, service, file and date. New requests are counted in a badge next to the menu item.
4. Any uploaded file is stored in the Media Library, attached to the request, and given a random prefix so its address cannot be guessed. Allowed types are PDF, DOC, DOCX, XLS, XLSX, JPG and PNG, up to 10 MB. The real file type is verified, not only the extension.
5. An email with all details goes to the **Quote notification email**, or to the site admin email if that is empty. The uploaded file is attached and linked, and *Reply-To* is set to the customer's address.

With JavaScript the form submits in place and shows inline messages. Without JavaScript it falls back to a normal form post with the same validation and messages.

## Email delivery (important)

WordPress sends email through the server's `mail()` function by default. Many hosts deliver that unreliably or straight to spam. Before launch:

1. Install an SMTP plugin such as **WP Mail SMTP**, **FluentSMTP** or **Post SMTP**.
2. Connect it to a transactional email provider (for example Amazon SES, Postmark, Mailgun, SendGrid, Brevo, or your Google Workspace / Microsoft 365 mailbox).
3. Send from an address on the company's own domain, and add the provider's SPF and DKIM records to the domain's DNS.
4. Submit a test quote request and confirm it arrives.

Quote requests are always saved in **Quote Requests** in WordPress even if an email fails, so no enquiry is lost.

## SEO

- Each page outputs a title, meta description, canonical URL, Open Graph and Twitter card tags.
- JSON-LD structured data: `Organization` (only filled-in details), `WebSite`, `BreadcrumbList` on inner pages, `Service` items on the Services page, and `FAQPage` wherever the FAQ appears.
- If an SEO plugin (Yoast, Rank Math, AIOSEO, SEOPress, The SEO Framework, Slim SEO) is active, the theme stops printing its own meta and social tags and Organization/Breadcrumb data, and leaves those to the plugin.

## Development notes

```
aero-dallas/
├── style.css, functions.php, theme.json
├── header.php, footer.php, front-page.php, page.php, index.php, single.php, 404.php
├── page-templates/   about, services, how-we-work, policies, contact
├── template-parts/   brand, page-header, quick-quote, service-card, why-choose, process-steps,
│                     who-we-are, stats, testimonials, insights, faq, cta-band, quote-form,
│                     contact-details, swoosh
├── inc/              helpers, icons, setup, enqueue, content-types, meta-boxes, customizer,
│                     cpt-quote-request, quote-form, seo-schema, activation
└── assets/           css, js, fonts, img/brand, img/photos
```

- There is no build step. Edit `assets/css/main.css` and `assets/js/*.js` directly.
- Colours and type are defined as CSS custom properties at the top of `main.css` and mirrored in `theme.json` for the block editor. Dark mode only redefines the tokens under `[data-theme="dark"]`; the mode is set on `<html>` by a small inline script in `<head>` before the page paints.
- Accessibility: buttons use navy text on orange (4.9:1); white on this orange would fail WCAG AA. Small orange text in light mode uses a deeper orange (#B84A06) for the same reason.
- Everything is prefixed `aerodallas_` and translatable with the `aero-dallas` text domain.

## Credits and licences

- **Fonts**: Archivo Black (The Archivo Black Project Authors / Omnibus-Type) and Open Sauce One (The Open Sauce One Authors / marcologous), both under the SIL Open Font License 1.1. The licence texts are in `assets/fonts/`.
- **Photography**: from Unsplash under the [Unsplash License](https://unsplash.com/license), which allows free commercial use without attribution. The images were cropped to remove third-party airline branding.
  - Home hero, jet at sunset: Chris Leipelt — https://unsplash.com/photos/6w_dYdazo20
  - "How We Work" band, aircraft at the gate at night: pengye Chen — https://unsplash.com/photos/qfHUdMG-OeI
  - Quote band, truck at dusk: Josiah Farrow — https://unsplash.com/photos/ucuOscdCaO4
  - Aircraft nose gear (bundled, available as a replacement): Jacob Hamm — https://unsplash.com/photos/yJgD95BlxvA
  - Aviation, pushback tug: Krisztian Tabori — https://unsplash.com/photos/9RbWunBOMnw
  - Industrial, technician grinding: TECNIC Bioprocess Solutions — https://unsplash.com/photos/9IEGO2zcM2s
  - Parts supply, warehouse: AFINIS Group — https://unsplash.com/photos/OnbSOhz0oig
  - Logistics, air cargo containers: Bernd Dittrich — https://unsplash.com/photos/miHHRMLDDH8
  - About, welding: Rob Lambert — https://unsplash.com/photos/9Q_pLLP_jmA
  - Detail, jet engine: Felix Berger — https://unsplash.com/photos/PfYaLrYS1Oc
- **Icons**: drawn for this theme.

Stock photos show generic equipment and settings, not AERO DALLAS's own work. Replace them with real company photography when it is available.
