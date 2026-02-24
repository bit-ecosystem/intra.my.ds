# Laravel SaaS Application

## Overview
This is an open-source Laravel-based SaaS application licensed under **Apache 2.0**. It can be installed on-premise or on private servers by subscribing companies. The application is designed for flexibility and data sovereignty.

## License
- Licensed under **Apache 2.0**.
- You may use, modify, and distribute the software freely, including for commercial purposes.

## Key Features
- On-premise installation for full data control.
- Subscription model offering:
  - Prepaid credits for downloading premium templates.
  - Access to updates and support.

## Data Ownership
All data entered by the subscribing company remains their property. The service provider does not claim ownership of customer data.

## Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/yourname/laravel-saas-app.git
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Configure environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Run migrations:
   ```bash
   php artisan migrate
   ```

## Subscription & Credits
- Subscribe to unlock premium templates.
- Credits can be purchased and redeemed for templates or modules.

## Support
For support and inquiries, contact: **your-email@example.com**
