<div {{ $attributes->class(["flex flex-wrap mx-[-15px] mt-[-30px] xl:mt-0 lg:mt-0"]) }}>
    <div class="md:w-4/12 xl:w-6/12 lg:w-6/12 w-full flex-[0_0_auto] px-[15px] max-w-full xl:mt-0 lg:mt-0 mt-[30px]">
        <div class="widget">
            <div class="mb-4">
                <img src="{{ asset('full-logo.jpg') }}" class="h-[48px]" alt="logo" >
{{--                <x-app-logo-full />--}}
            </div>
            <p class="!mb-4">© 2025 HireForex. <br class="hidden xl:block lg:block">All rights reserved.</p>
        </div>
        <!-- /.widget -->
    </div>

    <!-- /column -->
    <div class="md:w-4/12 xl:w-3/12 lg:w-3/12 w-full flex-[0_0_auto] px-[15px] max-w-full xl:mt-0 lg:mt-0 mt-[30px]">
        <div class="widget">
            <h4 class="mb-3 text-[1rem] leading-[1.45] text-brand-800">Get in Touch</h4>

                            <a class="text-[#60697b] hover:text-[#60697b]" href="mailto:support@hireforex.com">support@hireforex.com</a>
        </div>
        <!-- /.widget -->
    </div>

    <!-- /column -->
    <div class="md:w-4/12 xl:w-3/12 lg:w-3/12 w-full flex-[0_0_auto] px-[15px] max-w-full xl:mt-0 lg:mt-0 mt-[30px]">
        <div class="widget">
            <h4 class="mb-3 text-[1rem] leading-[1.45] text-brand-800">Learn More</h4>
            <ul class="pl-0 list-none !mb-0">
                <li><a class="text-gray-500 hover:text-brand-500" href="{{ route('faq') }}" wire:navigate>Frequently Asked Questions</a></li>
                <li class="mt-[0.35rem]"><a class="text-gray-500 hover:text-brand-500" href="{{ route('terms-and-conditions') }}" wire:navigate>Terms of Use</a></li>
                <li class="mt-[0.35rem]"><a class="text-gray-500 hover:text-brand-500" href="{{ route('privacy-policy') }}" wire:navigate>Privacy Policy</a></li>
            </ul>
        </div>
        <!-- /.widget -->
    </div>
    <!-- /column -->
</div>
