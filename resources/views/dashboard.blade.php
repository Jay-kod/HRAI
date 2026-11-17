<x-app-layout>
    @php
        $user = auth()->user();
        $assessmentCount = $user->riskAssessments()->count();
        $goalCount = $user->healthGoals()->count();
        $wearableCount = $user->wearableSessions()->count();
        $latestAssessment = $user->riskAssessments()->latest('assessed_at')->first();
        $profileComplete = $user->hasCompletedHealthProfile();
    @endphp

    <div class="min-h-[calc(100vh-4rem)] bg-slate-950">
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

            <div class="mt-4 grid gap-4 lg:grid-cols-[1.7fr_1fr]">
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
    </div>
</x-app-layout>
