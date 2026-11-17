<x-app-layout>
    @php
        $user = auth()->user();
        $assessmentCount = $user->riskAssessments()->count();
        $goalCount = $user->healthGoals()->count();
        $wearableCount = $user->wearableSessions()->count();
        $latestAssessment = $user->riskAssessments()->latest('assessed_at')->first();
        $profileComplete = $user->hasCompletedHealthProfile();
    @endphp

    <div class="min-h-screen bg-slate-950 lg:grid lg:grid-cols-[210px_1fr]">
        <aside class="hidden border-r border-white/10 bg-slate-900 p-4 lg:flex lg:flex-col">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 px-2 text-sm font-bold text-white">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-teal-400 text-sm font-black text-slate-950">H</span>
                HealthRisk AI
            </a>
            <a href="{{ route('check-in') }}" wire:navigate class="mt-6 flex items-center justify-center rounded-lg bg-teal-400 px-3 py-2 text-xs font-bold text-slate-950 hover:bg-teal-300">+ New check-in</a>
            <p class="mt-7 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Overview</p>
            <nav class="mt-2 space-y-1">
                <a href="{{ route('dashboard') }}" wire:navigate class="block rounded-lg bg-white/10 px-3 py-2 text-sm font-semibold text-white">Dashboard</a>
                <a href="{{ route('history') }}" wire:navigate class="block rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/5 hover:text-white">History</a>
                <a href="{{ route('check-in') }}" wire:navigate class="block rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-white/5 hover:text-white">Check-ins</a>
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
        <main class="min-w-0">
        <div class="border-b border-white/10 bg-slate-900/70 px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold text-slate-300">Dashboard</p>
                <a href="{{ route('profile') }}" wire:navigate class="flex items-center gap-2 text-xs text-slate-400 hover:text-white"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-teal-400 font-bold text-slate-950">{{ strtoupper(substr($user->name, 0, 1)) }}</span>{{ $user->name }}</a>
            </div>
        </div>
        <div class="mx-auto max-w-[1500px] px-4 py-6 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-teal-400">Personal health workspace</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-white">Good to see you, {{ $user->name }}</h1>
                    <p class="mt-1 text-sm text-slate-400">Your preventive-care snapshot at a glance.</p>
                </div>
                <a href="{{ route('check-in') }}" wire:navigate class="inline-flex items-center gap-2 rounded-lg bg-teal-500 px-4 py-2.5 text-sm font-bold text-slate-950 transition hover:bg-teal-400">
                    <span class="text-lg leading-none">+</span> New check-in
                </a>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl border border-white/10 bg-slate-900 p-4 shadow-lg shadow-black/10">
                    <p class="text-xs font-medium text-slate-400">Screenings completed</p>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $assessmentCount }}</p>
                    <p class="mt-2 text-xs text-teal-300">Keep your health picture current</p>
                </div>
                <div class="rounded-xl border border-white/10 bg-slate-900 p-4 shadow-lg shadow-black/10">
                    <p class="text-xs font-medium text-slate-400">Health goals</p>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $goalCount }}</p>
                    <p class="mt-2 text-xs text-slate-400">Personal targets in progress</p>
                </div>
                <div class="rounded-xl border border-white/10 bg-slate-900 p-4 shadow-lg shadow-black/10">
                    <p class="text-xs font-medium text-slate-400">Activity sessions</p>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $wearableCount }}</p>
                    <p class="mt-2 text-xs text-slate-400">Wearable data recorded</p>
                </div>
                <div class="rounded-xl border border-white/10 bg-slate-900 p-4 shadow-lg shadow-black/10">
                    <p class="text-xs font-medium text-slate-400">Profile status</p>
                    <p class="mt-2 text-2xl font-bold {{ $profileComplete ? 'text-teal-300' : 'text-amber-300' }}">{{ $profileComplete ? 'Ready' : 'Incomplete' }}</p>
                    <p class="mt-2 text-xs text-slate-400">{{ $profileComplete ? 'Ready for your next check-in' : 'Complete your profile to begin' }}</p>
                </div>
            </div>

            @unless ($profileComplete)
                <div class="mt-4 flex flex-wrap items-center justify-between gap-4 rounded-xl border border-amber-400/30 bg-amber-400/10 px-4 py-3">
                    <div>
                        <p class="text-sm font-bold text-amber-200">Complete your health profile</p>
                        <p class="mt-1 text-xs text-amber-100/70">Add your basic details before starting a check-in.</p>
                    </div>
                    <a href="{{ route('profile') }}" wire:navigate class="rounded-lg bg-amber-300 px-3 py-2 text-xs font-bold text-slate-950 hover:bg-amber-200">Update profile</a>
                </div>
            @endunless

            <section class="mt-4 overflow-hidden rounded-xl border border-white/10 bg-slate-900">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 px-5 py-4">
                    <div><h2 class="text-base font-bold text-white">Screening trend</h2><p class="mt-1 text-xs text-slate-400">Your overall screening score over recent check-ins.</p></div>
                    <span class="rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-slate-400">All time</span>
                </div>
                <div class="relative h-48 px-5 py-5">
                    <div class="absolute inset-x-5 top-8 border-t border-dashed border-white/10"></div>
                    <div class="absolute inset-x-5 top-1/2 border-t border-dashed border-white/10"></div>
                    <div class="absolute inset-x-5 bottom-8 border-t border-dashed border-white/10"></div>
                    <svg class="relative h-full w-full" viewBox="0 0 900 150" preserveAspectRatio="none" role="img" aria-label="Illustrative screening trend">
                        <defs><linearGradient id="trend-fill" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#2dd4bf" stop-opacity=".25"/><stop offset="1" stop-color="#2dd4bf" stop-opacity="0"/></linearGradient></defs>
                        <path d="M0 112 C55 118 70 95 120 102 S185 110 230 84 S290 97 340 76 S405 83 455 62 S520 78 570 55 S630 68 680 46 S740 60 785 38 S850 50 900 28 L900 150 L0 150 Z" fill="url(#trend-fill)"/>
                        <path d="M0 112 C55 118 70 95 120 102 S185 110 230 84 S290 97 340 76 S405 83 455 62 S520 78 570 55 S630 68 680 46 S740 60 785 38 S850 50 900 28" fill="none" stroke="#5eead4" stroke-linecap="round" stroke-width="3"/>
                    </svg>
                </div>
                <div class="flex justify-between border-t border-white/10 px-5 py-3 text-[11px] text-slate-500"><span>Earlier check-ins</span><span class="text-teal-300">View history &rarr;</span><span>Latest check-in</span></div>
            </section>

            <div class="mt-4 grid gap-4 lg:grid-cols-[1.35fr_1fr_1fr]">
                <section class="overflow-hidden rounded-xl border border-white/10 bg-slate-900">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 px-5 py-4">
                        <div>
                            <h2 class="text-base font-bold text-white">Latest screening</h2>
                            <p class="mt-1 text-xs text-slate-400">Your most recent educational risk snapshot.</p>
                        </div>
                        @if ($latestAssessment)
                            <x-risk-level-badge :level="$latestAssessment->overall_level->value" />
                        @endif
                    </div>
                    <div class="p-5">
                        @if ($latestAssessment)
                            <div class="flex flex-wrap items-end justify-between gap-4">
                                <div>
                                    <p class="text-xs text-slate-400">{{ $latestAssessment->assessed_at->format('j M Y, H:i') }}</p>
                                    <p class="mt-2 text-5xl font-bold text-white">{{ number_format((float) $latestAssessment->overall_score, 1) }}<span class="text-xl text-slate-500">/100</span></p>
                                </div>
                                <a href="{{ route('assessments.show', $latestAssessment) }}" wire:navigate class="text-sm font-semibold text-teal-300 hover:text-teal-200">View full result <span aria-hidden="true">&rarr;</span></a>
                            </div>
                            <div class="mt-5 h-2 overflow-hidden rounded-full bg-slate-800">
                                <div class="h-full rounded-full bg-gradient-to-r from-teal-400 to-emerald-300" style="width: {{ min(100, max(0, (float) $latestAssessment->overall_score)) }}%"></div>
                            </div>
                            <p class="mt-3 text-xs text-slate-500">This is an educational screening estimate, not a medical diagnosis.</p>
                        @else
                            <p class="text-sm text-slate-400">Complete a check-in to start your screening trend.</p>
                            <a href="{{ route('check-in') }}" wire:navigate class="mt-4 inline-flex rounded-lg bg-teal-500 px-4 py-2 text-sm font-bold text-slate-950 hover:bg-teal-400">Start check-in</a>
                        @endif
                    </div>
                </section>

                <section class="rounded-xl border border-white/10 bg-slate-900">
                    <div class="border-b border-white/10 px-5 py-4">
                        <h2 class="text-base font-bold text-white">Activity data</h2>
                        <p class="mt-1 text-xs text-slate-400">Recent wearable sessions.</p>
                    </div>
                    <div class="p-5">
                        <p class="text-4xl font-bold text-white">{{ $wearableCount }}</p>
                        <p class="mt-2 text-xs text-slate-500">sessions recorded</p>
                        <a href="{{ route('wearables') }}" wire:navigate class="mt-5 inline-flex text-sm font-semibold text-teal-300 hover:text-teal-200">View activity data &rarr;</a>
                    </div>
                </section>

                <section class="rounded-xl border border-white/10 bg-slate-900">
                    <div class="border-b border-white/10 px-5 py-4">
                        <h2 class="text-base font-bold text-white">Quick actions</h2>
                        <p class="mt-1 text-xs text-slate-400">Shortcuts to useful parts of your workspace.</p>
                    </div>
                    <div class="divide-y divide-white/10">
                        <a href="{{ route('check-in') }}" wire:navigate class="flex items-center justify-between px-5 py-4 transition hover:bg-white/5">
                            <span><span class="block text-sm font-semibold text-slate-200">Run a check-in</span><span class="mt-1 block text-xs text-slate-500">Update your screening estimate</span></span>
                            <span class="text-teal-300" aria-hidden="true">&rarr;</span>
                        </a>
                        <a href="{{ route('goals') }}" wire:navigate class="flex items-center justify-between px-5 py-4 transition hover:bg-white/5">
                            <span><span class="block text-sm font-semibold text-slate-200">Manage goals</span><span class="mt-1 block text-xs text-slate-500">Track preventive-care targets</span></span>
                            <span class="text-teal-300" aria-hidden="true">&rarr;</span>
                        </a>
                        <a href="{{ route('wearables') }}" wire:navigate class="flex items-center justify-between px-5 py-4 transition hover:bg-white/5">
                            <span><span class="block text-sm font-semibold text-slate-200">Activity data</span><span class="mt-1 block text-xs text-slate-500">Review wearable sessions</span></span>
                            <span class="text-teal-300" aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </section>
            </div>

            <div class="mt-4 rounded-xl border border-teal-400/20 bg-teal-400/5 px-5 py-4">
                <x-health-disclaimer class="text-slate-400" />
            </div>
        </div>
        </main>
    </div>
</x-app-layout>
