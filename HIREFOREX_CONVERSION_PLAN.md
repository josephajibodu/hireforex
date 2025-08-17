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
**Status**: ⏳ Pending
- [ ] Implement TraderService
- [ ] Implement TradeService (Using actions for specific actions instead)
- [ ] Implement TopUpService (Using actions for specific actions instead)
- [ ] Implement WithdrawalService (Using actions for specific actions instead)
- [ ] Update WalletService (Using actions for specific actions instead)

### Phase 4: Frontend Views & Components (Days 11-18)
**Status**: ⏳ Pending
- [ ] Update main dashboard
- [ ] Create trader marketplace page
- [ ] Implement trade creation modal
- [ ] Create top-up forms
- [ ] Create withdrawal forms
- [ ] Update trade history views

### Phase 5: Filament Admin Panel (Days 19-22)
**Status**: ⏳ Pending
**Rationale**: Admin panel should be last because we need to understand the complete data structure first.
- [ ] Update existing Filament resources
- [ ] Create new admin resources
- [ ] Implement admin actions
- [ ] Add admin dashboard widgets

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
