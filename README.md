<p align="center">
  <img src="https://cdn.nexuresolutions.com/content/images/logos/NexureLogoSquare.png" alt="Nexure Solutions Logo" width="140">
</p>

<h1 align="center">Nexure EMMIE</h1>

<p align="center">
  <strong>Enterprise Management, Monetary & Intelligence Engine</strong><br>
  The unified operating platform for modern businesses.
</p>

---

## Overview

**Nexure EMMIE** is the core intelligence and operational engine behind the Nexure Panel — designed to power virtually every aspect of a modern business through a **fully modular, automation-first architecture**.

Unlike traditional CRMs or analytics platforms, EMMIE functions as an **enterprise control plane**, unifying customer management, payroll, financial operations, communications, risk awareness, and automation into a single cohesive system.

EMMIE is built to scale from small teams to telecom-grade enterprises while remaining extensible, secure, and developer-friendly.

---

## Core Capabilities

### Unified Operations
- **Enterprise Dashboard**  
  Centralized visibility into business operations, financial activity, automation status, and system health.

- **CRM & Customer Lifecycle Management**  
  Track customers, leads, accounts, communications, and operational history across teams and services.

- **Payroll & Financial Operations**  
  Payroll processing, payments, financing, merchant services, and monetary workflows — all managed from one platform.

### Intelligence & Automation
- **Analytics & Reporting**  
  Actionable insights across operational, financial, and customer data.

- **Automation Engine**  
  Trigger-based workflows, scheduled jobs, and system-level automations designed for real-world business execution — not just reporting.

- **Risk & Trust Signals**  
  Host reputation analysis, monitoring, and fraud-adjacent intelligence layers.

### Communication & Collaboration
- **Integrated Chat & Calling**  
  Team-to-team and team-to-client communication with message and call tracking.

- **Third-Party Integrations**  
  Native and extensible integrations including Discord, Twilio, and external APIs.

### Platform Design
- **Fully Modular Architecture**  
  Enable only what you need. Extend with custom or prebuilt modules.

- **Themes & Customization**  
  Configurable UI themes and interface customization.

- **Multi-Industry Ready**  
  Designed to support industries ranging from accounting and finance to telecommunications, cloud computing, automotive, and web services.

- **Open Source by Design**  
  Built for transparency, extensibility, and developer contribution.

---

## Technology Stack

- **Backend**: PHP 8.4 (Composer)
- **Database**: MySQL
- **Web Server**: NGINX
- **OS**: Linux (NexureOS Fusion 2025.11)
- **Frontend**: HTML, CSS, JavaScript
- **Monitoring**: Sentry API
- **Communications**: Twilio API
- **Security Intelligence**: Neutrino Host Reputation API
- **Environment Management**: Pre-configured `.env` support

---

## Repository Structure Highlights

- `/Modules` — Core system modules and integrations  
- `/Modules/NexureSolutions/System/Handlers` — Base system handlers and execution logic  
- `/Automations` — Cron-driven and scheduled automation tasks  
- `/Install` — Installation and setup flow (in development)

---

## Authors & Contributors

- **Nick Derry** — Lead Architect & Core Development  
- **Mikey Brinkley** — Core Development  
- **Mikey W¹** — Base system handler implementation  
- **Joy Clens²** — Discord integration (partial)  
- **AlexySSH³** — Discord integration (partial)

¹ Authored the original base handler at  
`/Modules/NexureSolutions/System/Handlers/index.php` (later refactored)

² Contributed to Discord integration module  
³ Contributed to Discord integration module

---

## Project Status

Nexure EMMIE is currently under development.  
The automated installer is not finalized yet.

The platform will be opened for **Developer Preview** and **Public Testing** prior to initial release.

---

## Live Demo

A development demo is available here:

🔗 **https://us-east-1.nexure-cloud-compute-130-12-30-4.nexuresolutions.com/**

> Note: This environment may change or reset without notice.

---

## Prerequisites

- PHP 8.4
- Composer
- MySQL
- Git
- Linux (NexureOS Fusion 2025.11 recommended)
- NGINX
- Sentry API account — https://sentry.io/
- Twilio API account — https://www.twilio.com/
- Neutrino API access (Host Reputation API) — https://www.neutrinoapi.com/

---

### Installation

1. Clone the repository: `bash git clone https://github.com/Nexure-Solutions-LLP/NexurePanel.git`
2. Install the panel by running the install.sh bash script.
3. Run post installation by navigating to the panels domain then the folder /Install
4. Configure the panel and set credentials in the .ENV file.
5. Run the cron jobs by doing: `crontab -e` and `0 * * * * /usr/bin/php /var/www/nexurepanel/Automations/index.php`
6. Login to the admin account you created.