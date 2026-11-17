<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold">Welcome back, {{ auth()->user()->name }}</h3>
                        <x-health-disclaimer class="mt-2" />
                    </div>
                </div>

                @unless (auth()->user()->hasCompletedHealthProfile())
                    <div class="border border-amber-300 bg-amber-50 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-amber-900">Complete your health profile</h3>
                            <p class="mt-2 text-base text-amber-800">Add your date of birth, gender, and contact details before starting a check-in.</p>
                            <a href="{{ route('profile') }}" class="mt-4 inline-flex items-center rounded-md bg-amber-800 px-4 py-2 text-base font-medium text-white hover:bg-amber-900" wire:navigate>
                                Update profile
                            </a>
                        </div>
                    </div>
                @endunless

                <div class="grid gap-6 md:grid-cols-3">
                    <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                        <p class="text-sm font-medium text-gray-500">Assessments</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ auth()->user()->riskAssessments()->count() }}</p>
                    </div>
                    <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                        <p class="text-sm font-medium text-gray-500">Health goals</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ auth()->user()->healthGoals()->count() }}</p>
                    </div>
                    <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                        <p class="text-sm font-medium text-gray-500">Profile status</p>
                        <p class="mt-2 text-lg font-semibold text-gray-900">{{ auth()->user()->hasCompletedHealthProfile() ? 'Complete' : 'Incomplete' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
