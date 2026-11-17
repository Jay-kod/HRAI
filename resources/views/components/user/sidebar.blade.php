<aside class="fixed inset-y-0 left-0 z-30 hidden w-[210px] flex-col border-r border-white/10 bg-slate-900 p-4 lg:flex">
    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 px-2 text-sm font-bold text-white">
        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-teal-400 text-sm font-black text-slate-950">H</span>
        HealthRisk AI
    </a>
    <a href="{{ route('check-in') }}" wire:navigate class="mt-6 flex items-center justify-center rounded-lg bg-teal-400 px-3 py-2 text-xs font-bold text-slate-950 hover:bg-teal-300">+ New check-in</a>
    <p class="mt-7 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Overview</p>
    <nav class="mt-2 space-y-1">
        <a href="{{ route('dashboard') }}" wire:navigate class="block rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/5 hover:text-white">Dashboard</a>
        <a href="{{ route('history') }}" wire:navigate class="block rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/5 hover:text-white">History</a>
        <a href="{{ route('check-in') }}" wire:navigate class="block rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/5 hover:text-white">Check-ins</a>
        <a href="{{ route('profile') }}" wire:navigate class="block rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/5 hover:text-white">Profile</a>
    </nav>
    <p class="mt-6 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Preventive care</p>
    <nav class="mt-2 space-y-1">
        <a href="{{ route('goals') }}" wire:navigate class="block rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/5 hover:text-white">Health goals</a>
        <a href="{{ route('wearables') }}" wire:navigate class="block rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/5 hover:text-white">Activity data</a>
        <a href="{{ route('feedback') }}" wire:navigate class="block rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/5 hover:text-white">Feedback</a>
    </nav>
    <div class="mt-auto rounded-lg border border-teal-400/20 bg-teal-400/5 p-3">
        <p class="text-[10px] font-bold uppercase tracking-widest text-teal-300">Screening status</p>
        <p class="mt-2 text-xs leading-5 text-slate-400">Educational estimates for your next informed decision.</p>
    </div>
</aside>
