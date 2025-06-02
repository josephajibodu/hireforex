<?php

use App\Settings\GeneralSetting;

if (!function_exists('merge')) {
    function merge($arrays): string
    {
        $result = [];

        foreach ($arrays as $array) {
            if ($array !== null) {
                if (gettype($array) !== 'string') {
                    foreach ($array as $key => $value) {
                        if (is_integer($key)) {
                            $result[] = $value;
                        } elseif (isset($result[$key]) && is_array($result[$key]) && is_array($value)) {
                            $result[$key] = merge([$result[$key], $value]);
                        } else {
                            $result[$key] = $value;
                        }
                    }
                } else {
                    $result[count($result)] = $array;
                }
            }
        }

        return join(" ", $result);
    }
}

if (!function_exists('uncamelize')) {
    function uncamelize($camel, $splitter = "_"): string
    {
        $camel = preg_replace('/(?!^)[[:upper:]][[:lower:]]/', '$0', preg_replace('/(?!^)[[:upper:]]+/', $splitter . '$0', $camel));
        return strtolower($camel);
    }
}

if (!function_exists('formatCurrency')) {
    function formatCurrency($number): string
    {
        if ($number) {
            $formattedNumber = preg_replace('/\D/', '', strval($number));
            $rest = strlen($formattedNumber) % 3;
            $currency = substr($formattedNumber, 0, $rest);
            $thousand = str_split(substr($formattedNumber, $rest), 3);
            $separator = '';

            if ($thousand) {
                $separator = $rest ? "." : "";
                $currency .= $separator . implode(".", $thousand);
            }

            return $currency;
        } else {
            return "";
        }
    }
}

if (!function_exists('getFileList')) {
    function getFileList($directory, $extensions): array
    {
        $files = [];

        if (is_dir($directory)) {
            $scannedFiles = scandir($directory);
            foreach ($scannedFiles as $file) {
                if ($file === '.' || $file === '..') continue;

                $fileExtension = explode(".", $file);
                if (in_array(end($fileExtension), explode(",", $extensions))) {
                    array_push($files, str_replace(base_path() . "/", "", "/" . implode("/", array_filter(explode("/", $directory), "strlen")) . "/" . $file));
                }
            }
        }

        return $files;
    }
}

function to_money(string|int|float $value, int $divider = 1, string $currency = '₦', bool $hideSymbol = false): string
{
    $amount = floatval($value) / ($divider ?: 1);

    // Determine decimal places (show 2 dp only if there's a fraction)
    $decimalPlaces = fmod($amount, 1) !== 0.0 ? 2 : 0;

    // Format the number
    $formattedAmount = number_format($amount, $decimalPlaces);

    return $hideSymbol ? $formattedAmount : "$currency$formattedAmount";
}

function getWithdrawalFeePercentage(): float|int
{
    $percentage = app(GeneralSetting::class)->withdrawal_fee;

    return $percentage / 100;
}

function getWithdrawalFeeLabel(): string
{
    $percentage = app(GeneralSetting::class)->withdrawal_fee;

    return "$percentage%";
}

function get_referral_key(): string
{
    return 'profitchain_referrer';
}

function get_referral_bonus(): float
{
    $settings = app(GeneralSetting::class);
    $value = $settings->referral_bonus;

    return is_referral_flat() ? $value : $value / 100;
}

function is_referral_flat(): bool
{
    return app(GeneralSetting::class)->referral_type !== 'percentage';
}

function getCurrentRate(): float
{
    $settings = app(GeneralSetting::class);

    return $settings->usd_rate;
}

function obfuscate($word, int $letterCount = 3): string
{
    $length = strlen($word);

    if ($letterCount == 0) {
        return str_repeat('*', $length);
    }

    // If the word has less than 3 letters, return all asterisks
    if ($length <= $letterCount) {
        return str_repeat('*', $length);
    }

    return substr($word, 0, -$letterCount) . str_repeat('*', $letterCount);
}