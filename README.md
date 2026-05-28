# SwapNet - Local Barter App

SwapNet is a hyper-local barter platform designed to facilitate the exchange of goods and services within communities. It allows users to join private networks (neighborhoods, offices, clubs), list items they no longer need, and discover offers from others nearby.

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel)
![Flutter](https://img.shields.io/badge/Flutter-3.x-02569B?style=for-the-badge&logo=flutter)
![Filament](https://img.shields.io/badge/Filament-3.x-EBB308?style=for-the-badge&logo=filament)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)

## Features

- **Multi-Network**: Join specific communities via invite codes.
- **Barter Listings**: Create listings for goods or services with images and categories.
- **Offer Management**: Make barter offers, negotiate, and accept/reject swaps.
- **Direct Messaging**: In-app chat for coordination once an offer is accepted.
- **Delivery Tracking**: Simple status updates for hand-offs.
- **Member Reviews**: Build trust within the network through peer reviews.
- **Admin Panel**: Robust back-office management using Filament.

## Quick Start

### Backend (Laravel)
1. **Requirements**: PHP 8.3, MySQL.
2. **Setup**:
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate --seed
   php artisan serve
   ```
3. **Admin Access**: `admin@swapnet.local` / `password` at `/admin`.

### Mobile (Flutter)
1. **Requirements**: Flutter SDK.
2. **Setup**:
   ```bash
   cd mobile
   flutter pub get
   flutter run
   ```

## License

MIT License. See [LICENSE](LICENSE) for details.
