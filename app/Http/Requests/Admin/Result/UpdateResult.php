<?php namespace App\Http\Requests\Admin\Result;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateResult extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return  bool
     */
    public function authorize()
    {
        return Gate::allows('admin.result.edit', $this->result);
    }

/**
     * Get the validation rules that apply to the request.
     *
     * @return  array
     */
    public function rules()
    {
        return [
            'path' => ['sometimes', 'string'],
            'user_id' => ['sometimes', 'integer'],
            
        ];
    }
}
