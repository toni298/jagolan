<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLandingPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Theme
            'theme' => ['nullable', 'string', 'in:default,red,green'],

            // Hero
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:1000'],
            'hero_image' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            // About
            'about_title' => ['nullable', 'string', 'max:255'],
            'about_description' => ['nullable', 'string', 'max:5000'],

            // Certificates (repeater)
            'certificates' => ['nullable', 'array', 'max:30'],
            'certificates.*.name' => ['nullable', 'string', 'max:255'],
            'certificates.*.description' => ['nullable', 'string', 'max:1000'],
            'certificates.*.existing_image' => ['nullable', 'string', 'max:255'],
            'certificates.*.image' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            // Testimonials (repeater synced by id)
            'testimonials' => ['nullable', 'array', 'max:50'],
            'testimonials.*.id' => ['nullable', 'integer'],
            'testimonials.*.name' => ['required', 'string', 'max:255'],
            'testimonials.*.position' => ['nullable', 'string', 'max:255'],
            'testimonials.*.message' => ['required', 'string', 'max:2000'],
            'testimonials.*.rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'testimonials.*.existing_photo' => ['nullable', 'string', 'max:255'],
            'testimonials.*.photo' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function attributes(): array
    {
        return [
            'hero_title' => 'judul hero',
            'hero_subtitle' => 'subjudul hero',
            'hero_image' => 'banner image',
            'about_title' => 'judul tentang kami',
            'about_description' => 'deskripsi tentang kami',
            'testimonials.*.name' => 'nama testimoni',
            'testimonials.*.message' => 'pesan testimoni',
        ];
    }
}
