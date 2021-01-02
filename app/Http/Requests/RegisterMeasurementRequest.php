<?php

namespace App\Http\Requests;

use Cake\Chronos\Chronos;
use Co2Control\Payloads\MeasurementPayload;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class RegisterMeasurementRequest extends Request implements MeasurementPayload
{
    public function validate()
    {
        parent::validate();

        $uuid = $this->request->route('uuid');
        $validator = Validator::make([['uuid' => $uuid]], [
            function ($attribute, $value, $fail) {
                if (! Uuid::isValid($value['uuid'])) {
                    $fail('The uuid provided for the Sensor is not valid');
                }
            },
        ]);

        $validator->validate();
    }

    public function c02Level(): int
    {
        return $this->request->input('co2');
    }

    public function registeredAt(): Chronos
    {
        return Chronos::createFromFormat(Chronos::ATOM, $this->request->input('time'));
    }

    protected function rules(): array
    {
        return [
            'co2' => 'required',
            'time' => 'required|date|date_format:Y-m-d\TH:i:sP',
        ];
    }
}
