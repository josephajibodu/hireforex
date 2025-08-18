# HireForex Project Conversion Plan

## Overview
Converting the existing project to a HireForex forex trader marketplace while maintaining the current User Interface structure.

## Phase Breakdown

### Phase 1: Database Structure & Models (Days 1-4)
**Status**: 🚧 In Progress
**Rationale**: Starting with database structure as it's the foundation for everything else.

#### 1.1 Clean Up Existing Models & Migrations
- [x] Audit existing models and identify what to keep/delete
- [x] Remove unused migrations
- [x] Update composer.json if needed

#### 1.2 Create New Core Models
- [x] Create Trader model with factory and seeder
- [x] Create Trade model with factory and seeder
- [x] Create TopUp model with factory and seeder (already exists)
- [x] Create Withdrawal model with factory and seeder (already exists)

#### 1.3 Update Existing Models
- [x] Update User model fields
- [x] Simplify Wallet model
- [x] Update WalletTransaction model

### Phase 2: Database Migrations (Days 5-6)
**Status**: ✅ Completed
- [x] Create all new table migrations
- [x] Update existing table migrations
- [x] Run migrations and seed data

### Phase 3: Core Business Logic (Days 7-10)
**Status**: ✅ Completed
- [x] Implement TraderService (Using actions for specific actions instead)
- [x] Implement TradeService (Using actions for specific actions instead)
- [x] Implement TopUpService (Using actions for specific actions instead)
- [x] Implement WithdrawalService (Using actions for specific actions instead)
- [x] Update WalletService (Using actions for specific actions instead)

### Phase 4: Frontend Views & Components (Days 11-18)
**Status**: ✅ Completed
- [x] Update main dashboard
- [x] Create trader marketplace page
- [x] Implement trade creation modal
- [x] Create top-up forms (already exists, needs updating)
- [x] Create withdrawal forms (already exists, needs updating)
- [x] Update trade history views
- [x] Create Volt components for traders and trades
- [x] Remove old marketplace and gift card components
- [x] Update navigation and sidebar
- [x] Update welcome page to reflect HireForex platform
- [x] Update all branding references from Cardbeta to HireForex
- [x] Update FAQ configurations and support contact information

### Phase 5: Filament Admin Panel (Days 19-22)
**Status**: ✅ Completed
**Rationale**: Admin panel should be last because we need to understand the complete data structure first.
- [x] Update existing Filament resources
- [x] Create new admin resources
- [x] Implement admin actions
- [x] Add admin dashboard widgets

### Phase 6: Testing & Validation (Days 23-25)
**Status**: ⏳ Pending
- [ ] Write unit tests for services
- [ ] Write feature tests for user flows
- [ ] Test admin functionality

### Phase 7: UI/UX Polish (Days 26-28)
**Status**: ⏳ Pending
- [ ] UI/UX improvements
- [ ] Error handling
- [ ] Performance optimization

## Current Status
- **Phase**: 1.1 - Clean Up Existing Models & Migrations
- **Next Task**: Audit existing models and identify what to keep/delete

## Key Models to Create
1. **Trader**: name, experience, favorite_pairs, track_record, mbg_rate, min_capital, available_volume, duration
2. **Trade**: user_id, trader_id, amount, potential_return, mbg_rate, status, start_date, end_date
3. **TopUp**: user_id, amount, method (bybit/usdt), status, screenshot, bybit_email, network
4. **Withdrawal**: user_id, amount, fee_amount, total_amount, method, status

## Key Models to Update
1. **User**: Add first_name, last_name, phone_number, username fields
2. **Wallet**: Simplify to USDT balance only
3. **WalletTransaction**: Update for trade-related transactions

## Total Estimated Time
4-5 weeks

## Notes
- Maintain existing UI structure and design patterns
- Use existing authentication and authorization systems
- Keep Filament admin panel structure but update for new models
- Ensure all user flows work for both guest and authenticated users
