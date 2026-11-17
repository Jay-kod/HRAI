@props(['active' => ''])
<aside class="hidden border-r border-white/10 bg-slate-900 p-4 lg:flex lg:flex-col">
    <a href="{{ route('admin.overview') }}" wire:navigate class="flex items-center gap-2 px-2 text-sm font-bold text-white"><span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-300 text-sm font-black text-slate-950">H</span> HealthRisk AI</a>
    <p class="mt-8 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Administration</p>
    <nav class="mt-2 space-y-1">
        <a href="{{ route('admin.overview') }}" wire:navigate class="{{ $active === 'overview' ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }} block rounded-lg px-3 py-2 text-sm font-semibold">Overview</a>
        <a href="{{ route('admin.model') }}" wire:navigate class="{{ $active === 'model' ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }} block rounded-lg px-3 py-2 text-sm font-semibold">Model performance</a>
        <a href="{{ route('admin.templates') }}" wire:navigate class="{{ $active === 'templates' ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }} block rounded-lg px-3 py-2 text-sm font-semibold">Recommendation templates</a>
        <a href="{{ route('admin.rules') }}" wire:navigate class="{{ $active === 'rules' ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }} block rounded-lg px-3 py-2 text-sm font-semibold">Risk rules</a>
        <a href="{{ route('admin.feedback') }}" wire:navigate class="{{ $active === 'feedback' ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }} block rounded-lg px-3 py-2 text-sm font-semibold">Usability feedback</a>
        <a href="{{ route('admin.users') }}" wire:navigate class="{{ $active === 'users' ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }} block rounded-lg px-3 py-2 text-sm font-semibold">Users</a>
    </nav>
    <a href="{{ route('admin.overview') }}" wire:navigate class="mt-auto rounded-lg border border-amber-300/20 bg-amber-300/5 p-3 text-xs text-amber-200">Admin overview &rarr;</a>
</aside>
