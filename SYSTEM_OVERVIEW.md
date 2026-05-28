# Rumah Sehat Holistik Satu Bumi
## Digital Management System — Executive Overview

---

## What This System Is

The RSH Satu Bumi Digital Management System is the central operational platform for running Rumah Sehat Holistik Satu Bumi's programs. It handles everything from the moment a prospective participant finds us online, through registration and payment, all the way to program management and reporting — in one integrated system.

It replaces manual processes (spreadsheets, paper forms, WhatsApp confirmations) with a structured, trackable digital workflow that reduces administrative burden and ensures nothing falls through the cracks.

---

## Who Uses It

| Role | What They Do in the System |
|---|---|
| **Prospective Participants** | Browse program information, fill out the registration form online |
| **Admin Staff** | Review registrations, manage payments, issue invoices, publish articles |
| **Program Managers** | Set up program schedules and monitor enrollment capacity |
| **Management / C-Suite** | View dashboard reports, monitor revenue and registration trends |

---

## The Participant Journey — How It Works

### Step 1 — Registration (Public, Online)
A prospective participant visits the website and fills out an online registration form. The form captures:
- Personal information (name, date of birth, occupation, contact)
- Health background (complaints, current medications, allergies, treatment history)
- Emotional and physical self-assessment
- Program period selection (based on available schedules)

The form saves progress automatically — participants can close the browser and return later without losing their answers. Before final submission, a confirmation screen summarizes their choices so they can review before sending.

Once submitted, the participant receives a unique **registration code** they can use to check their status at any time.

### Step 2 — Admin Review
The admin team receives a notification and can immediately review the full registration in the admin panel. Each registration shows:
- Complete participant profile and health information
- BMI indicator with health category badge (Underweight / Normal / Overweight / Obese)
- Assigned program period and current enrollment count
- Full submission history

### Step 3 — Status Management
The admin updates the registration status as the participant progresses:

| Status | Meaning |
|---|---|
| **Pending Payment** | Registration received, awaiting initial payment |
| **DP / Confirmed** | Down payment received, spot confirmed |
| **Fully Paid** | Complete payment received |
| **Cancelled** | Registration cancelled |

Each status change is tracked, and the system enforces a logical progression — it is not possible to accidentally skip steps or set an invalid status.

### Step 4 — Invoice & Payment
When a registration is confirmed, the system **automatically generates an invoice**. Invoices handle two payment scenarios:

- **Full Payment** — Standard invoice for the complete program fee
- **Down Payment (DP)** — Invoice showing the DP amount due, with the remaining balance clearly displayed in a highlighted summary box

Invoices can be downloaded as a professionally formatted **PDF** at any time, including the organization's logo, payment bank details, and program information. They can also be re-issued or adjusted if needed.

### Step 5 — Program Completion
Once participants have completed the program and settled their balance, the admin marks them as **Fully Paid**. The full record remains in the system for future reference.

---

## Key Capabilities

### Program Schedule Management
The admin team can create, edit, and manage program periods with:
- Start and end dates (3-day, 7-day, or custom duration)
- Enrollment quota (maximum participants)
- Full price and down payment amount
- Active / inactive toggle

The system tracks available spots in real time. When a program reaches capacity, it stops accepting registrations. Participants can also be **rescheduled** to a different program period directly from the admin panel without needing to re-register.

---

### Invoicing & Billing
The system maintains a complete invoice record for every transaction. Key features include:
- Auto-generated invoice numbers
- Support for full payment or down payment billing
- PDF download with professional layout
- Payment status tracking (Unpaid / Paid / Cancelled)
- A product/service catalog that speeds up invoice creation for recurring items

---

### Content & Articles
The website's articles and blog posts are managed entirely within the system. Staff can:
- Write and publish articles with a rich text editor
- Upload cover images
- Organize content by category
- Use an **AI writing assistant** to generate a first draft from a topic prompt (powered by the team's AI provider of choice — Google Gemini, OpenRouter, or others)

Published articles appear on the public-facing website automatically.

---

### Reporting & Dashboard

**Live Dashboard** — The admin home screen shows at a glance:
- Total registrations broken down by status
- Active program periods and their current enrollment vs. quota
- The 5 most recent registrations with status badges
- A **6-month trend chart** tracking registration volume and revenue month by month
- A **status breakdown chart** showing the proportion of pending, confirmed, paid, and cancelled registrations

**Data Export** — Any list in the system (registrations, invoices) can be exported to a **CSV file** compatible with Microsoft Excel. Filters applied on screen carry through to the export — so exporting only "Fully Paid" registrations from a specific program period produces exactly that file, ready for reporting or handover.

---

### Notifications & Communication
The system sends automated email notifications at key moments (registration confirmation, status updates). Email templates, sender name, and recipient addresses are all configurable by the admin without any technical involvement.

A test email function is available so the team can verify email delivery before relying on it for real participants.

---

### Security & Access Control

- The admin panel is accessed through a discreet, non-obvious URL (not the standard `/admin` path)
- Attempting to access admin pages without being logged in shows a generic "page not found" — it does not reveal that an admin panel exists
- Sessions automatically expire after **30 minutes of inactivity**, protecting against unauthorized access on shared computers
- Two staff roles are available: **Admin** (full access) and **Staff** (limited access)
- All activity is logged and viewable in the system's audit log

---

### Google Sheets Integration
Registration data is automatically mirrored to a connected Google Sheets document, giving management a familiar spreadsheet view of participant records without manual data entry.

---

## Summary of Benefits

| Before | After |
|---|---|
| Registration via WhatsApp or paper form | Structured online form with health assessment |
| Manual invoice in Word/Excel | Auto-generated PDF invoices, tracked per participant |
| Status tracked in a spreadsheet | Real-time status board with audit trail |
| Revenue figures compiled manually | Live dashboard with monthly charts |
| Separate tools for articles, billing, scheduling | Single integrated platform |
| No visibility into capacity | Real-time quota tracking per program period |

---

## System Access

The system is hosted at **rshsatubumi.id** and is accessible from any browser — no installation required.

- **Public website & registration:** `rshsatubumi.id`
- **Admin panel:** Accessible to authorized staff only (credentials provided separately)
- **Check registration status (for participants):** `rshsatubumi.id/daftar/cek`

---

*Document prepared for internal management reference. For technical documentation, refer to the developer handover materials.*
