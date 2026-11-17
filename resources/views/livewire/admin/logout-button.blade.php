<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public bool $confirming = false;

    public function confirmLogout(): void
    {
        $this->confirming = true;
    }

    public function cancelLogout(): void
    {
        $this->confirming = false;
    }

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect(route('admin.login'), navigate: true);
    }
}; ?>

<div>
    <button type="button" wire:click="confirmLogout" class="flex w-full items-center gap-2 rounded-lg border border-rose-300/20 bg-rose-300/5 px-3 py-2 text-sm font-semibold text-rose-200 hover:bg-rose-300/10">
        <svg class="h-4 w-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 5v-2H5v18h10v-2M10 12h11m0 0-4-4m4 4-4 4"/></svg>
        <span>Log out</span>
    </button>

    @if ($confirming)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 px-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="admin-logout-title">
            <div class="w-full max-w-sm rounded-2xl border border-white/10 bg-slate-900 p-6 shadow-2xl">
                <h2 id="admin-logout-title" class="text-lg font-bold text-white">Sign out of the admin workspace?</h2>
                <p class="mt-2 text-sm leading-6 text-slate-400">You will return to the administrator login page.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="cancelLogout" class="rounded-lg border border-white/10 px-4 py-2 text-sm font-semibold text-slate-300 hover:bg-white/5">Cancel</button>
                    <button type="button" wire:click="logout" wire:loading.attr="disabled" class="rounded-lg bg-rose-500 px-4 py-2 text-sm font-bold text-white hover:bg-rose-400 disabled:opacity-60"><span wire:loading.remove>Log out</span><span wire:loading>Logging out...</span></button>
                </div>
            </div>
        </div>
    @endif
</div>
