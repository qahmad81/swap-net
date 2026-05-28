# SwapNet Developer Documentation (AGENTS.md)

## Project Overview
SwapNet is a specialized barter application focused on local community exchanges. Unlike global marketplaces, SwapNet emphasizes trust and proximity through a network-based model.

## Architecture
- **Backend**: Laravel 11 API-only core.
- **Admin**: Filament PHP 3.x for content management and moderation.
- **Mobile**: Flutter 3.x application for iOS and Android.
- **Database**: MySQL 8.0.

## Setup Instructions

### Environment Requirements
- **PHP**: 8.3.12+
- **Database**: MySQL 8.0+
- **Tools**: Composer, Node.js (for Filament assets if needed).

### Backend Setup
1. Clone the repository.
2. Install dependencies: `composer install`.
3. Configure `.env`:
   - `DB_DATABASE=swap_net`
   - `DB_USERNAME=root`
   - `DB_PASSWORD=root`
4. Run migrations and seed data: `php artisan migrate --seed`.
5. Link storage: `php artisan storage:link`.
6. Start the server: `php artisan serve`.

### Flutter Setup
1. Navigate to the mobile directory: `cd mobile`.
2. Install packages: `flutter pub get`.
3. Run the app: `flutter run`.
   - *Note*: Ensure the backend URL in the mobile app's service configuration matches your local IP/host.

## API Overview
The API is grouped into several core domains:
- **Auth**: Sanctum-based social and token authentication.
- **Networks**: Private community management and membership.
- **Listings**: CRUD for barter items.
- **Offers**: The negotiation engine (Accept/Reject/Withdraw).
- **Messages**: 1-to-1 chat for coordinated swaps.
- **Delivery**: Tracking the physical exchange status.
- **Reviews**: Post-swap feedback loop.
- **Notifications**: Device token management for push alerts.

## Key Design Decisions
- **Polling vs WebSockets**: To minimize infrastructure complexity for V1, the app uses intelligent polling/refreshing rather than real-time WebSockets for messaging.
- **No Maps**: Privacy-first approach; location is handled by network membership rather than precise GPS coordinates.
- **Listing Stability**: Listings expire after 3 days to keep the marketplace fresh, requiring explicit renewal by users.
- **Closed Ecosystem**: Items are only visible to members of the same network.

## Admin Credentials
- **URL**: `http://localhost:8000/admin`
- **User**: `admin@swapnet.local`
- **Password**: `password`
