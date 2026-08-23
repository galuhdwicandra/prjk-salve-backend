<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoyaltySettingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isManager() ?? false;
    }

    public function rules(): array
    {
        return [
            'target' => ['required', 'integer', 'min:1', 'max:20'],
            'stamp_per' => ['required', Rule::in(['transaksi', 'kunjungan'])],
            'rewards' => ['array'],
            'rewards.*.at' => ['required', 'integer', 'min:1'],
            'rewards.*.free.on' => ['boolean'],
            'rewards.*.free.products' => ['array'],
            'rewards.*.free.products.*' => ['uuid'],
            'rewards.*.disc.on' => ['boolean'],
            'rewards.*.disc.mode' => [Rule::in(['rp', 'pct'])],
            'rewards.*.disc.amount' => ['numeric', 'min:0'],
            'rewards.*.disc.basis' => [Rule::in(['before', 'after'])],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $target = (int) $this->input('target', 0);

            foreach ((array) $this->input('rewards', []) as $i => $reward) {
                if ((int) ($reward['at'] ?? 0) > $target) {
                    $v->errors()->add("rewards.$i.at", 'Titik stamp melebihi target.');
                }

                $free = (bool) data_get($reward, 'free.on', false);
                $disc = (bool) data_get($reward, 'disc.on', false);

                if (! $free && ! $disc) {
                    $v->errors()->add("rewards.$i", 'Reward harus punya free service atau extra discount.');
                }
            }
        });
    }
}
