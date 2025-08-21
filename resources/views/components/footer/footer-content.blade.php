<div {{ $attributes->class(["flex flex-wrap mx-[-15px] mt-[-30px] xl:mt-0 lg:mt-0"]) }}>
    <div class="md:w-4/12 xl:w-6/12 lg:w-6/12 w-full flex-[0_0_auto] px-[15px] max-w-full xl:mt-0 lg:mt-0 mt-[30px]">
        <div class="widget">
            <div class="mb-4">
                <img src="{{ asset('full-logo.png') }}" class="h-[48px]" alt="logo" >
            </div>
            <p class="!mb-4">© {{ date('Y') }} HireForex. <br class="hidden xl:block lg:block">All rights reserved.</p>
        </div>
        </div>

    <div class="md:w-4/12 xl:w-3/12 lg:w-3/12 w-full flex-[0_0_auto] px-[15px] max-w-full xl:mt-0 lg:mt-0 mt-[30px]">
        <div class="widget">
            <h4 class="mb-3 text-[1rem] leading-[1.45] text-brand-800">Get in Touch</h4>
            <ul class="pl-0 list-none !mb-0">
                <li><a class="text-gray-500 hover:text-brand-500" href="{{ route('contact-us') }}" wire:navigate>Contact Us</a></li>
                <li class="mt-[0.35rem]"><a class="text-[#60697b] hover:text-[#60697b]" href="mailto:support@hireforex.com">support@hireforex.com</a></li>
                <li class="mt-4">
                    <p class="text-sm text-gray-500 mb-2">HireForex is available in:</p>
                    <div class="flex items-center gap-3">
                        <img class="h-5 rounded-sm" src="https://flagcdn.com/ng.svg" alt="Nigeria flag" title="Nigeria">
                        <img class="h-5 rounded-sm" src="https://flagcdn.com/za.svg" alt="South Africa flag" title="South Africa">
                        <img class="h-5 rounded-sm" src="https://flagcdn.com/ke.svg" alt="Kenya flag" title="Kenya">
                        <img class="h-5 rounded-sm" src="https://flagcdn.com/gh.svg" alt="Ghana flag" title="Ghana">
                    </div>
                </li>
            </ul>
        </div>
        </div>

    <div class="md:w-4/12 xl:w-3/12 lg:w-3/12 w-full flex-[0_0_auto] px-[15px] max-w-full xl:mt-0 lg:mt-0 mt-[30px]">
        <div class="widget">
            <h4 class="mb-3 text-[1rem] leading-[1.45] text-brand-800">Learn More</h4>
            <ul class="pl-0 list-none !mb-0">
                <li><a class="text-gray-500 hover:text-brand-500" href="{{ route('faq') }}" wire:navigate>Frequently Asked Questions</a></li>
                <li class="mt-[0.35rem]"><a class="text-gray-500 hover:text-brand-500" href="{{ route('terms-and-conditions') }}" wire:navigate>Terms of Use</a></li>
                <li class="mt-[0.35rem]"><a class="text-gray-500 hover:text-brand-500" href="{{ route('privacy-policy') }}" wire:navigate>Privacy Policy</a></li>
                <li class="mt-[0.35rem]"><a class="text-gray-500 hover:text-brand-500" href="{{ route('join-team') }}" wire:navigate.hover>Join the HireForex Team</a></li>
            </ul>
        </div>
        </div>
    <div class="w-full flex-[0_0_auto] px-[15px] max-w-full mt-[30px]">
        <div class="border-t border-gray-200/80 pt-6">
            <p class="text-xs text-gray-500 leading-relaxed">
                <strong>Legal Disclaimer:</strong> Trading foreign exchange (Forex) and related financial instruments carries a high level of risk and may not be suitable for all investors. Past performance of traders on HireForex does not guarantee future results. HireForex does not provide financial advice, investment recommendations, or brokerage services. We connect clients with skilled traders, and all trading decisions are made at your own risk. Money-Back Guarantees (MBG) are limited to the specific percentage offered by HireForex and selected by you at the time of hiring. HireForex only covers the MBG chosen and does not extend coverage beyond it. By using HireForex, you acknowledge that you understand these risks and accept full responsibility for your investment decisions.
            </p>
        </div>
    </div>
    <div class="w-full flex-[0_0_auto] px-[15px] max-w-full mt-[30px]">
        <div class="border-t border-gray-200/80 pt-6">
            <p class="text-xs text-gray-500 leading-relaxed">
                <strong>Ads Disclaimer:</strong> HireForex is an independent platform and is not endorsed by, directly affiliated with, maintained, authorized, or sponsored by Google LLC, Meta Platforms, Inc. (including Facebook and Instagram), or any of their subsidiaries or affiliates. All product names, logos, and brands are the property of their respective owners and are used only for identification purposes. Use of these names does not imply any affiliation or endorsement..
            </p>
        </div>
    </div>
    </div>
