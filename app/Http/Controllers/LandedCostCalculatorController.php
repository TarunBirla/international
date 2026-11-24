<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HsCodeDuty;

class LandedCostCalculatorController extends Controller
{
    public function index()
    {
        $duties = HsCodeDuty::all();
        $origins = ['India', 'China', 'Germany', 'United States', 'Japan', 'All'];
        $currencies = [
            'GBP' => ['symbol' => '£', 'rate_to_gbp' => 1.0],
            'USD' => ['symbol' => '$', 'rate_to_gbp' => 0.79],
            'EUR' => ['symbol' => '€', 'rate_to_gbp' => 0.86],
            'INR' => ['symbol' => '₹', 'rate_to_gbp' => 0.0094],
            'CNY' => ['symbol' => '¥', 'rate_to_gbp' => 0.11],
        ];

        return view('calculator.index', compact('duties', 'origins', 'currencies'));
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'product_value' => 'required|numeric|min:1',
            'currency' => 'required|string|in:GBP,USD,EUR,INR,CNY',
            'origin_country' => 'required|string',
            'hs_code' => 'nullable|string',
            'custom_duty_rate' => 'nullable|numeric|min:0',
            'shipping_method' => 'required|string',
            'shipping_cost' => 'nullable|numeric|min:0',
            'insurance_cost' => 'nullable|numeric|min:0',
            'weight_kg' => 'nullable|numeric|min:0',
        ]);

        $currency = $validated['currency'];
        $rates = [
            'GBP' => 1.0,
            'USD' => 0.79,
            'EUR' => 0.86,
            'INR' => 0.0094,
            'CNY' => 0.11,
        ];

        $conversionToGbp = $rates[$currency] ?? 1.0;
        $productValueGbp = $validated['product_value'] * $conversionToGbp;

        // Auto calculate shipping if not provided
        $weight = (float) ($validated['weight_kg'] ?? 20);
        $shippingGbp = 0;
        if (!empty($validated['shipping_cost']) && $validated['shipping_cost'] > 0) {
            $shippingGbp = (float) $validated['shipping_cost'] * $conversionToGbp;
        } else {
            switch ($validated['shipping_method']) {
                case 'dhl_express':
                    $shippingGbp = max(60, 45 + ($weight * 5.5));
                    break;
                case 'air_freight':
                    $shippingGbp = max(180, 120 + ($weight * 3.8));
                    break;
                case 'sea_fcl':
                    $shippingGbp = 1850; // standard 20ft container average
                    break;
                case 'sea_lcl':
                default:
                    $shippingGbp = max(220, 150 + ($weight * 1.8));
                    break;
            }
        }

        // Marine Insurance (usually ~ 0.5% - 0.8% of product value + shipping)
        $insuranceGbp = !empty($validated['insurance_cost']) 
            ? (float) $validated['insurance_cost'] * $conversionToGbp 
            : round(($productValueGbp + $shippingGbp) * 0.006, 2);

        // CIF Value (Cost, Insurance, Freight)
        $cifValueGbp = $productValueGbp + $shippingGbp + $insuranceGbp;

        // Duty Rate
        $dutyRatePercent = 2.50; // Default
        if (!empty($validated['custom_duty_rate'])) {
            $dutyRatePercent = (float) $validated['custom_duty_rate'];
        } elseif (!empty($validated['hs_code'])) {
            $hs = HsCodeDuty::where('hs_code', $validated['hs_code'])->first();
            if ($hs) {
                $dutyRatePercent = (float) $hs->duty_rate_percent;
            }
        }

        $customsDutyGbp = round($cifValueGbp * ($dutyRatePercent / 100), 2);

        // Import VAT (20% on CIF + Customs Duty)
        $vatBaseGbp = $cifValueGbp + $customsDutyGbp;
        $importVatGbp = round($vatBaseGbp * 0.20, 2);

        // Standard UK Customs Clearance Brokerage Fee (e.g. Junaid & Co.)
        $clearanceFeeGbp = 120.00;

        // Port handling / documentation fee
        $portHandlingGbp = 65.00;

        // Estimated Total Landed Cost
        $totalLandedCostGbp = $cifValueGbp + $customsDutyGbp + $importVatGbp + $clearanceFeeGbp + $portHandlingGbp;

        // Convert results back to selected currency for comparison
        $result = [
            'input' => $validated,
            'currency' => $currency,
            'product_value_gbp' => round($productValueGbp, 2),
            'shipping_gbp' => round($shippingGbp, 2),
            'insurance_gbp' => round($insuranceGbp, 2),
            'cif_value_gbp' => round($cifValueGbp, 2),
            'duty_rate_percent' => $dutyRatePercent,
            'customs_duty_gbp' => round($customsDutyGbp, 2),
            'vat_rate_percent' => 20.00,
            'import_vat_gbp' => round($importVatGbp, 2),
            'clearance_fee_gbp' => $clearanceFeeGbp,
            'port_handling_gbp' => $portHandlingGbp,
            'total_landed_cost_gbp' => round($totalLandedCostGbp, 2),
            'total_customs_payable_gbp' => round($customsDutyGbp + $importVatGbp + $clearanceFeeGbp + $portHandlingGbp, 2),
            'landed_cost_original_currency' => round($totalLandedCostGbp / $conversionToGbp, 2),
        ];

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($result);
        }

        $duties = HsCodeDuty::all();
        $origins = ['India', 'China', 'Germany', 'United States', 'Japan', 'All'];
        $currencies = [
            'GBP' => ['symbol' => '£', 'rate_to_gbp' => 1.0],
            'USD' => ['symbol' => '$', 'rate_to_gbp' => 0.79],
            'EUR' => ['symbol' => '€', 'rate_to_gbp' => 0.86],
            'INR' => ['symbol' => '₹', 'rate_to_gbp' => 0.0094],
            'CNY' => ['symbol' => '¥', 'rate_to_gbp' => 0.11],
        ];

        return view('calculator.index', compact('duties', 'origins', 'currencies', 'result'));
    }
}
