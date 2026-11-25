<!-- Global Floating Toaster (Top Right) -->
<div x-data="toastManager()" 
     class="fixed top-5 right-5 z-[9999] w-full max-w-sm space-y-3 pointer-events-none"
     x-cloak>
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible"
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-4"
             class="pointer-events-auto w-full bg-white rounded-2xl border shadow-xl p-4 flex items-start space-x-3 relative overflow-hidden"
             :class="{
                 'border-emerald-200 shadow-emerald-600/10': toast.type === 'success',
                 'border-rose-200 shadow-rose-600/10': toast.type === 'error',
                 'border-sky-200 shadow-sky-600/10': toast.type === 'info',
                 'border-amber-200 shadow-amber-600/10': toast.type === 'warning'
             }">
            
            <!-- Left accent strip -->
            <div class="absolute left-0 top-0 bottom-0 w-1.5"
                 :class="{
                     'bg-emerald-500': toast.type === 'success',
                     'bg-rose-500': toast.type === 'error',
                     'bg-sky-500': toast.type === 'info',
                     'bg-amber-500': toast.type === 'warning'
                 }"></div>

            <!-- Icon -->
            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 text-sm ml-1"
                 :class="{
                     'bg-emerald-50 text-emerald-600': toast.type === 'success',
                     'bg-rose-50 text-rose-600': toast.type === 'error',
                     'bg-sky-50 text-sky-600': toast.type === 'info',
                     'bg-amber-50 text-amber-600': toast.type === 'warning'
                 }">
                <i :class="{
                    'fa-solid fa-circle-check': toast.type === 'success',
                    'fa-solid fa-circle-xmark': toast.type === 'error',
                    'fa-solid fa-circle-info': toast.type === 'info',
                    'fa-solid fa-triangle-exclamation': toast.type === 'warning'
                }"></i>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0 pt-0.5">
                <p class="text-xs font-black capitalize tracking-tight"
                   :class="{
                       'text-emerald-900': toast.type === 'success',
                       'text-rose-900': toast.type === 'error',
                       'text-sky-900': toast.type === 'info',
                       'text-amber-900': toast.type === 'warning'
                   }"
                   x-text="toast.title || (toast.type === 'error' ? 'Notice' : 'Success')"></p>
                <p class="text-xs text-slate-600 font-medium mt-0.5 leading-snug break-words" x-text="toast.message"></p>
            </div>

            <!-- Close button -->
            <button @click="removeToast(toast.id)" 
                    class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-50 transition flex-shrink-0">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
    </template>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('toastManager', () => ({
        toasts: [],
        init() {
            // Listen for window event: window.dispatchEvent(new CustomEvent('notify', { detail: { message: '...', type: 'success' } }))
            window.addEventListener('notify', (e) => {
                this.addToast(e.detail.message, e.detail.type || 'success', e.detail.title);
            });

            // Initial flash messages from Laravel sessions
            @if(session('success'))
                this.addToast(@json(session('success')), 'success', 'Success');
            @endif

            @if(session('error'))
                this.addToast(@json(session('error')), 'error', 'Error');
            @endif

            @if(session('status'))
                this.addToast(@json(session('status')), 'info', 'Status');
            @endif

            @if(session('warning'))
                this.addToast(@json(session('warning')), 'warning', 'Warning');
            @endif

            @if(isset($errors) && $errors->any())
                @foreach($errors->all() as $error)
                    this.addToast(@json($error), 'error', 'Validation Notice');
                @endforeach
            @endif
        },
        addToast(message, type = 'success', title = '') {
            const id = Date.now() + Math.random();
            const toast = { id, message, type, title, visible: true };
            this.toasts.push(toast);
            setTimeout(() => {
                this.removeToast(id);
            }, 4500);
        },
        removeToast(id) {
            const index = this.toasts.findIndex(t => t.id === id);
            if (index !== -1) {
                this.toasts[index].visible = false;
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 300);
            }
        }
    }));
});
</script>
