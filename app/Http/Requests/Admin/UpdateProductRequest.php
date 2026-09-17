<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'              => ['nullable', 'exists:product_categories,id'],
            'name'                     => ['required', 'string', 'max:255'],
            'title'                    => ['nullable', 'string', 'max:255'],
            'slug'                     => ['nullable', 'string', 'max:255'],
            'description'              => ['nullable', 'string'],
            'status'                   => ['required', 'in:active,inactive'],
            'sort_order'               => ['nullable', 'integer'],

            'banner_image'             => ['nullable', 'image', 'max:10240'],
            'gallery_images'           => ['nullable', 'array'],
            'gallery_images.*'         => ['image', 'max:10240'],
            'gallery_existing'         => ['nullable', 'array'],
            'gallery_primary'          => ['nullable', 'string'],

            'facilities'               => ['nullable', 'array'],
            'facilities.*.name'        => ['nullable', 'string', 'max:255'],
            'facilities.*.value'       => ['nullable', 'string', 'max:255'],
            'facilities.*.unit'        => ['nullable', 'string', 'max:255'],

            'variants'                          => ['nullable', 'array'],
            'variants.*.id'                     => ['nullable', 'string'],
            'variants.*.name'                   => ['nullable', 'string', 'max:255'],
            'variants.*.title'                  => ['nullable', 'string', 'max:255'],
            'variants.*.slug'                   => ['nullable', 'string', 'max:255'],
            'variants.*.description'            => ['nullable', 'string'],
            'variants.*.status'                 => ['nullable', 'in:active,inactive'],
            'variants.*.banner_image'           => ['nullable', 'image', 'max:10240'],
            'variants.*.gallery_images'         => ['nullable', 'array'],
            'variants.*.gallery_images.*'       => ['image', 'max:10240'],
            'variants.*.images_existing'        => ['nullable', 'array'],
            'variants.*.facilities'             => ['nullable', 'array'],
            'variants.*.facilities.*.name'      => ['nullable', 'string', 'max:255'],
            'variants.*.facilities.*.value'     => ['nullable', 'string', 'max:255'],
            'variants.*.facilities.*.unit'      => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Nama produk wajib diisi.',
            'status.required'      => 'Status wajib dipilih.',
            'category_id.exists'   => 'Kategori produk tidak valid.',
            'banner_image.image'   => 'Banner harus berupa gambar.',
            'gallery_images.*.image' => 'File galeri harus berupa gambar.',
        ];
    }
}
