<div 
    x-data="{}"
    @remove-notification.window="setTimeout(() => $wire.remove($event.detail.id), $event.detail.timeout)"
    class="fixed top-5 right-5 z-50 space-y-3 w-72"
>
    @foreach($notifications as $notification)
        <div 
            wire:key="notification-{{ $notification['id'] }}"
            class="notification flex items-center p-4 rounded-md shadow-lg transform transition-all duration-300 ease-in-out animate-notification"
            :class="{
                'bg-green-100 border-l-4 border-green-500': '{{ $notification['type'] }}' === 'success',
                'bg-red-100 border-l-4 border-red-500': '{{ $notification['type'] }}' === 'error',
                'bg-blue-100 border-l-4 border-blue-500': '{{ $notification['type'] }}' === 'info',
                'bg-yellow-100 border-l-4 border-yellow-500': '{{ $notification['type'] }}' === 'warning'
            }"
        >
            <div class="flex-1 pr-2">
                <span 
                    class="font-medium"
                    :class="{
                        'text-green-800': '{{ $notification['type'] }}' === 'success',
                        'text-red-800': '{{ $notification['type'] }}' === 'error',
                        'text-blue-800': '{{ $notification['type'] }}' === 'info',
                        'text-yellow-800': '{{ $notification['type'] }}' === 'warning'
                    }"
                >
                    {{ $notification['message'] }}
                </span>
            </div>
            <button 
                type="button" 
                class="text-gray-400 hover:text-gray-500" 
                wire:click="remove('{{ $notification['id'] }}')"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endforeach
    
    <style>
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .animate-notification {
            animation: slideInRight 0.3s ease-out forwards;
        }
    </style>
</div>
