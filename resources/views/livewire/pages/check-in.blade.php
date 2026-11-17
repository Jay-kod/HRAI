<?php

use App\Actions\RunAssessment;
use App\Models\BiometricData;
use App\Models\LifestyleSurvey;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use function Livewire\Volt\layout;

new class extends Component
{
    public int $stage = 1;
    public ?float $height_cm = null;
    public ?float $weight_kg = null;
    public ?int $systolic = null;
    public ?int $diastolic = null;
    public ?float $fasting_glucose_mgdl = null;
    public ?int $resting_heart_rate = null;
    public bool $smoker = false;
    public bool $heavy_alcohol = false;
    public bool $phys_activity = false;
    public bool $fruit = false;
    public bool $veggies = false;
    public ?int $sleep_hours = null;
    public ?int $stress_level = null;
    public bool $family_history = false;
    public bool $stroke = false;
    public array $diagnosed_conditions = [];

    public function mount(): void
    {
        if (! Auth::user()->hasCompletedHealthProfile()) {
            $this->redirectRoute('profile');
        }
    }

    public function nextStage(): void
    {
        $this->validateStage();
        $this->stage = min(4, $this->stage + 1);
    }

    public function previousStage(): void
    {
        $this->stage = max(1, $this->stage - 1);
    }

    public function submit(RunAssessment $runAssessment): void
    {
        $this->validate([
            'height_cm' => ['required', 'numeric', 'between:100,250'],
            'weight_kg' => ['required', 'numeric', 'between:25,300'],
            'systolic' => ['nullable', 'integer', 'between:70,250'],
            'diastolic' => ['nullable', 'integer', 'between:40,150'],
            'fasting_glucose_mgdl' => ['nullable', 'numeric', 'between:40,600'],
            'resting_heart_rate' => ['nullable', 'integer', 'between:30,220'],
            'sleep_hours' => ['nullable', 'integer', 'between:0,24'],
            'stress_level' => ['nullable', 'integer', 'between:1,5'],
            'diagnosed_conditions' => ['array'],
        ]);

        $user = Auth::user();
        $bmi = round($this->weight_kg / (($this->height_cm / 100) ** 2), 2);
        $biometricData = BiometricData::create([
            'user_id' => $user->id,
            'assessed_at' => now(),
            'height_cm' => $this->height_cm,
            'weight_kg' => $this->weight_kg,
            'bmi' => $bmi,
            'systolic' => $this->systolic,
            'diastolic' => $this->diastolic,
            'fasting_glucose_mgdl' => $this->fasting_glucose_mgdl,
            'resting_heart_rate' => $this->resting_heart_rate,
        ]);
        $survey = LifestyleSurvey::create([
            'user_id' => $user->id,
            'biometric_data_id' => $biometricData->id,
            'smoker' => $this->smoker,
            'heavy_alcohol' => $this->heavy_alcohol,
            'phys_activity' => $this->phys_activity,
            'fruit' => $this->fruit,
            'veggies' => $this->veggies,
            'sleep_hours' => $this->sleep_hours,
            'stress_level' => $this->stress_level,
            'family_history' => $this->family_history,
            'stroke' => $this->stroke,
            'diagnosed_conditions' => $this->diagnosed_conditions,
        ]);

        $assessment = $runAssessment->execute($user, $biometricData, $survey);
        $this->redirectRoute('assessments.show', ['assessment' => $assessment], navigate: true);
    }

    private function validateStage(): void
    {
        if ($this->stage === 1) {
            $this->validate([
                'height_cm' => ['required', 'numeric', 'between:100,250'],
                'weight_kg' => ['required', 'numeric', 'between:25,300'],
                'systolic' => ['nullable', 'integer', 'between:70,250'],
                'diastolic' => ['nullable', 'integer', 'between:40,150'],
                'fasting_glucose_mgdl' => ['nullable', 'numeric', 'between:40,600'],
                'resting_heart_rate' => ['nullable', 'integer', 'between:30,220'],
            ]);
        }

        if ($this->stage === 2) {
            $this->validate([
                'sleep_hours' => ['nullable', 'integer', 'between:0,24'],
                'stress_level' => ['nullable', 'integer', 'between:1,5'],
            ]);
        }
    }
}; ?>

<?php layout('layouts.app'); ?>

<div class="mx-auto max-w-3xl space-y-6 px-4 py-8 sm:px-6">
    <h2 class="text-2xl font-semibold text-gray-900">Health check-in</h2>
        <div class="flex items-center justify-between text-sm font-medium text-gray-600" aria-label="Check-in progress">
            @foreach ([1 => 'Measurements', 2 => 'Lifestyle', 3 => 'History', 4 => 'Review'] as $number => $label)
                <span class="{{ $stage >= $number ? 'text-indigo-700' : '' }}">{{ $number }}. {{ $label }}</span>
            @endforeach
        </div>

        <form wire:submit="{{ $stage === 4 ? 'submit' : 'nextStage' }}" class="space-y-6 rounded-lg bg-white p-6 shadow-sm">
            @if ($stage === 1)
                <div>
                    <h3 class="text-lg font-semibold">Measurements</h3>
                    <p class="mt-1 text-base text-gray-600">Use your most recent measurements where available.</p>
                </div>
                <div class="grid gap-6 sm:grid-cols-2">
                    <div><x-input-label for="height_cm" value="Height (cm)" /><x-text-input wire:model="height_cm" id="height_cm" type="number" step="0.1" class="mt-1 block w-full" /><x-input-error :messages="$errors->get('height_cm')" class="mt-2" /></div>
                    <div><x-input-label for="weight_kg" value="Weight (kg)" /><x-text-input wire:model="weight_kg" id="weight_kg" type="number" step="0.1" class="mt-1 block w-full" /><x-input-error :messages="$errors->get('weight_kg')" class="mt-2" /></div>
                    <div><x-input-label for="systolic" value="Systolic blood pressure (optional)" /><x-text-input wire:model="systolic" id="systolic" type="number" class="mt-1 block w-full" /><x-input-error :messages="$errors->get('systolic')" class="mt-2" /></div>
                    <div><x-input-label for="diastolic" value="Diastolic blood pressure (optional)" /><x-text-input wire:model="diastolic" id="diastolic" type="number" class="mt-1 block w-full" /><x-input-error :messages="$errors->get('diastolic')" class="mt-2" /></div>
                    <div><x-input-label for="fasting_glucose_mgdl" value="Fasting glucose mg/dL (optional)" /><x-text-input wire:model="fasting_glucose_mgdl" id="fasting_glucose_mgdl" type="number" step="0.1" class="mt-1 block w-full" /></div>
                    <div><x-input-label for="resting_heart_rate" value="Resting heart rate (optional)" /><x-text-input wire:model="resting_heart_rate" id="resting_heart_rate" type="number" class="mt-1 block w-full" /></div>
                </div>
            @elseif ($stage === 2)
                <div><h3 class="text-lg font-semibold">Lifestyle</h3><p class="mt-1 text-base text-gray-600">These answers help explain the screening estimate.</p></div>
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach (['smoker' => 'I currently smoke', 'heavy_alcohol' => 'I report heavy alcohol use', 'phys_activity' => 'I am physically active', 'fruit' => 'I regularly eat fruit', 'veggies' => 'I regularly eat vegetables'] as $field => $label)
                        <label class="flex items-center gap-3 text-base"><input wire:model="{{ $field }}" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm" />{{ $label }}</label>
                    @endforeach
                </div>
                <div class="grid gap-6 sm:grid-cols-2">
                    <div><x-input-label for="sleep_hours" value="Typical sleep hours" /><x-text-input wire:model="sleep_hours" id="sleep_hours" type="number" class="mt-1 block w-full" /><x-input-error :messages="$errors->get('sleep_hours')" class="mt-2" /></div>
                    <div><x-input-label for="stress_level" value="Stress level (1–5)" /><x-text-input wire:model="stress_level" id="stress_level" type="number" min="1" max="5" class="mt-1 block w-full" /><x-input-error :messages="$errors->get('stress_level')" class="mt-2" /></div>
                </div>
            @elseif ($stage === 3)
                <div><h3 class="text-lg font-semibold">Medical history</h3><p class="mt-1 text-base text-gray-600">Select anything that applies.</p></div>
                <div class="space-y-4 text-base">
                    <label class="flex items-center gap-3"><input wire:model="family_history" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm" />Family history of relevant conditions</label>
                    <label class="flex items-center gap-3"><input wire:model="stroke" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm" />History of stroke</label>
                    <fieldset><legend class="font-medium">Known conditions</legend><div class="mt-2 space-y-2"><label class="flex items-center gap-3"><input wire:model="diagnosed_conditions" value="diabetes" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm" />Diabetes</label><label class="flex items-center gap-3"><input wire:model="diagnosed_conditions" value="hypertension" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm" />Hypertension</label></div></fieldset>
                </div>
            @else
                <div><h3 class="text-lg font-semibold">Review and submit</h3><p class="mt-1 text-base text-gray-600">Review your entries before running the educational screening.</p></div>
                <dl class="grid gap-3 text-base sm:grid-cols-2"><div><dt class="font-medium text-gray-500">Height / weight</dt><dd>{{ $height_cm }} cm / {{ $weight_kg }} kg</dd></div><div><dt class="font-medium text-gray-500">Blood pressure</dt><dd>{{ $systolic ?? 'Not provided' }} / {{ $diastolic ?? 'Not provided' }}</dd></div><div><dt class="font-medium text-gray-500">Activity</dt><dd>{{ $phys_activity ? 'Active' : 'Not active' }}</dd></div><div><dt class="font-medium text-gray-500">Known conditions</dt><dd>{{ count($diagnosed_conditions) ? implode(', ', $diagnosed_conditions) : 'None selected' }}</dd></div></dl>
                <x-health-disclaimer class="rounded-md bg-gray-50 p-4" />
            @endif

            <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                @if ($stage > 1)<x-secondary-button type="button" wire:click="previousStage">Back</x-secondary-button>@else<div></div>@endif
                <x-primary-button type="submit">{{ $stage === 4 ? 'Run screening' : 'Continue' }}</x-primary-button>
            </div>
        </form>
</div>
