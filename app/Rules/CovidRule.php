<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

use App\Helper\Utilites;

use App\Models\History;

class CovidRule implements Rule
{
    protected $user_type = 'visitor'; 
    public $length = 10;

    public $lengthCheck = false;

    public function __construct($user_type, $length)
    {
        $this->length = $length;
        $this->user_type = $user_type;
        $this->utilites = app(Utilites::class);
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
        return ($this->findActiveVisitorsCountByUnitId($this->user_type, $value) < $this->length);
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

    private function findActiveVisitorsCountByUnitId($user_type, $id){

        if(empty($id)) {
            return 0;
        }

        $requst_params = [
            "user_type" => $user_type,
            "id" => $id,
        ];

        return $this->utilites->getActiveVisitors($requst_params)->count(); 
    }
}
