<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\History;

class CovidRule implements Rule
{
    public $length = 10;

    public $lengthCheck = false;

    public function __construct($length)
    {
        $this->length = $length;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ($this->findActiveVisitorsCountByUnitId($value) < $this->length);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Access denied, Due to covid-19 measures unit has a maximum up to '.$this->length.' visitors allowed.';
    }

    private function findActiveVisitorsCountByUnitId($id){

        if(empty($id)) {
            return 0;
        }

        return History::where('unit_id', $id)
                    ->whereNull('exited_at')
                    ->whereNull('deleted_at')
                    ->count();
    }
}
