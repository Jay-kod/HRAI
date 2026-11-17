<div x-data="{ open: false }">
    <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-controls="user-sidebar" aria-label="Toggle navigation menu" class="fixed left-4 top-4 z-40 inline-flex h-10 w-10 items-center justify-center rounded-lg border border-white/10 bg-slate-900 text-teal-300 shadow-lg shadow-black/20 hover:bg-slate-800 lg:hidden">
        <svg x-show="!open" class="h-5 w-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16"/></svg>
        <svg x-show="open" class="h-5 w-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m6 6 12 12M18 6 6 18"/></svg>
    </button>
    <div x-show="open" x-transition.opacity @click="open = false" class="fixed inset-0 z-20 bg-slate-950/70 lg:hidden" aria-hidden="true"></div>
<aside id="user-sidebar" x-bind:class="open ? 'flex' : 'hidden'" class="fixed inset-y-0 left-0 z-30 w-[210px] flex-col border-r border-white/10 bg-slate-900 p-4 lg:flex">
    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 px-2 text-sm font-bold text-white">
        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-teal-400 text-sm font-black text-slate-950">H</span>
        HealthRisk AI
    </a>
    <p class="mt-7 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Overview</p>
    <nav class="mt-2 space-y-1">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/5 hover:text-white"><svg class="h-4 w-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 21v-6h6v6"/></svg><span>Home</span></a>
        <a href="{{ route('check-in') }}" wire:navigate class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/5 hover:text-white"><svg class="h-4 w-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5v14M5 12h14"/></svg><span>Health check</span></a>
        <a href="{{ route('history') }}" wire:navigate class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/5 hover:text-white"><svg class="h-4 w-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12a9 9 0 1 0 3-6.7M3 4v5h5M12 7v5l3 2"/></svg><span>History</span></a>
        <a href="{{ route('profile') }}" wire:navigate class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/5 hover:text-white"><svg class="h-4 w-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.2" stroke-width="1.8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 20a7 7 0 0 1 14 0"/></svg><span>My profile</span></a>
    </nav>
    <p class="mt-6 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Preventive care</p>
    <nav class="mt-2 space-y-1">
        <a href="{{ route('goals') }}" wire:navigate class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/5 hover:text-white"><svg class="h-4 w-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3 14.8 9l6.2.6-4.7 4.1 1.4 6.1L12 16.6 6.3 19.8l1.4-6.1L3 9.6 9.2 9 12 3Z"/></svg><span>Goals</span></a>
        <a href="{{ route('wearables') }}" wire:navigate class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/5 hover:text-white"><svg class="h-4 w-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 13h3l2-7 4 12 2-5h5"/></svg><span>Activity</span></a>
        <a href="{{ route('feedback') }}" wire:navigate class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/5 hover:text-white"><svg class="h-4 w-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 5h14v10H9l-4 4V5Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 9h8M8 12h5"/></svg><span>Feedback</span></a>
    </nav>
    <div class="mt-auto rounded-lg border border-teal-400/20 bg-teal-400/5 p-3">
        <p class="text-[10px] font-bold uppercase tracking-widest text-teal-300">Screening status</p>
        <p class="mt-2 text-xs leading-5 text-slate-400">Educational estimates for your next informed decision.</p>
    </div>
</aside>
</div>
