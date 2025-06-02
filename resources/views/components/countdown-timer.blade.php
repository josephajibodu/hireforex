@props([
    'timeRemaining',
    'message'
])

<div
        x-data="{
        countdown: {{ $timeRemaining }},
        intervalRef: null,
        message: @js($message),
        showMessage: false,
        init() {
            this.intervalRef = setInterval(() => {
                --this.countdown;

                if (this.countdown <= 0) {
                    clearInterval(this.intervalRef);
                    this.showMessage = true;
                }
            }, 1000);
        }
    }"
>
    <flux:badge class="flex justify-center" x-show="!showMessage" style="width: 7rem!important;">
        <span x-text="Math.floor(countdown / 3600).toString().padStart(2, '0')"></span>:
        <span x-text="Math.floor((countdown % 3600) / 60).toString().padStart(2, '0')"></span>:
        <span x-text="(countdown % 60).toString().padStart(2, '0')"></span>
    </flux:badge>

    <flux:badge class="text-center!" x-show="showMessage" x-text="message || '-------'" color="danger" style="width: 7rem;"></flux:badge>
</div>