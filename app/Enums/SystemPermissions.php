<?php

namespace App\Enums;

use App\Traits\HasValues;

enum SystemPermissions: string
{
    use HasValues;

    case ManageUsers = 'manage users';

    case ManageLeaderboard = 'manage leaderboard';

    case BanUsers = 'ban users';

    case ManageTransfers = 'manage transfers';

    case ManageWithdrawals = 'manage withdrawals';

    case ManageUSDTSale = 'manage usdt sale';

    case ManageDonations = 'manage donations';

    case ManageOrders = 'manage orders';

    case ManageArbitrageTrade = 'manage arbitrage trade';

    case ManageCurrencyPair = 'manage currency pair';

    case ManageAdverts = 'manage sell adverts';

    case ManageSettings = 'manage settings';

    case ManageKYC = 'manage kyc';

    case ViewAllDisputes = 'manage disputes';

    case AccessDashboard = 'access dashboard';

    case ManageFunds = 'manage funds';

    case AssignRoles = 'assign roles';

    case ManagePayoutProofs = 'manage payout proofs';

    case ManageSocialMediaPosts = 'manage social media posts';

    case CreateSellOrder = 'create sell order';

}