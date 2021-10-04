<?php

namespace Modules\Ecommerce\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Ecommerce\App\Enums\StatusEnum;
use Modules\Ecommerce\App\Models\AttributeSet;

class AttributeDatabaseSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $arrays = [
            [
                'title'                     => 'SIZE',
                'slug'                      => 'size',
                'status'                    => StatusEnum::PUBLISHED(),
                'order'                     => 2,
                'display_layout'            => 'radio',
                'is_searchable'             => true,
                'is_comparable'             => true,
                'is_use_in_product_listing' => true,
                'attributes'                => [
                    [
                        'title'             => 'L',
                        'slug'              => 'l-size',
                        'status'            => StatusEnum::PUBLISHED(),
                        'order'             => 1,
                        'is_default'        => true,
                    ],
                    [
                        'title'             => 'M',
                        'slug'              => 'm-size',
                        'status'            => StatusEnum::PUBLISHED(),
                        'order'             => 2,
                        'is_default'        => false,
                    ],
                    [
                        'title'             => 'S',
                        'slug'              => 's-size',
                        'status'            => StatusEnum::PUBLISHED(),
                        'order'             => 3,
                        'is_default'        => false,
                    ],
                    [
                        'title'             => 'XS',
                        'slug'              => 'xs-size',
                        'status'            => StatusEnum::PUBLISHED(),
                        'order'             => 3,
                        'is_default'        => false,
                    ]
                ]
            ],
            [
                'title'                     => 'COLOR',
                'slug'                      => 'color',
                'status'                    => StatusEnum::PUBLISHED(),
                'order'                     => 1,
                'display_layout'            => 'color',
                'is_searchable'             => true,
                'is_comparable'             => true,
                'is_use_in_product_listing' => true,
                'attributes'                => [
                    [
                        'title'             => 'RED',
                        'slug'              => 'red',
                        'color'             => '#FF0000',
                        'status'            => StatusEnum::PUBLISHED(),
                        'order'             => 1,
                        'is_default'        => true,
                    ],
                    [
                        'title'             => 'GREEN',
                        'slug'              => 'green',
                        'color'             => '#32a852',
                        'status'            => StatusEnum::PUBLISHED(),
                        'order'             => 2,
                        'is_default'        => false,
                    ],
                    [
                        'title'             => 'BLUE',
                        'slug'              => 'blue',
                        'color'             => '#2a94f7',
                        'status'            => StatusEnum::PUBLISHED(),
                        'order'             => 3,
                        'is_default'        => false,
                    ]
                ]
            ],
            [
                'title'                     => 'Ram',
                'slug'                      => 'ram',
                'status'                    => StatusEnum::PUBLISHED(),
                'order'                     => 3,
                'display_layout'            => 'radio',
                'is_searchable'             => true,
                'is_comparable'             => true,
                'is_use_in_product_listing' => true,
                'attributes'                => [
                    [
                        'title'             => '4GB',
                        'slug'              => '4GB',
                        'status'            => StatusEnum::PUBLISHED(),
                        'order'             => 1,
                        'is_default'        => true,
                    ],
                    [
                        'title'             => '8GB',
                        'slug'              => '8GB',
                        'status'            => StatusEnum::PUBLISHED(),
                        'order'             => 2,
                        'is_default'        => false,
                    ],
                    [
                        'title'             => '16GB',
                        'slug'              => '16GB',
                        'status'            => StatusEnum::PUBLISHED(),
                        'order'             => 3,
                        'is_default'        => false,
                    ]
                ]
            ],
            [
                'title'                     => 'GPU',
                'slug'                      => 'gpu',
                'status'                    => StatusEnum::PUBLISHED(),
                'order'                     => 4,
                'display_layout'            => 'radio',
                'is_searchable'             => true,
                'is_comparable'             => true,
                'is_use_in_product_listing' => true,
                'attributes'                => [
                    [
                        'title'             => 'VR 4GB',
                        'slug'              => 'VR4GB',
                        'status'            => StatusEnum::PUBLISHED(),
                        'order'             => 1,
                        'is_default'        => true,
                    ],
                    [
                        'title'             => 'VR8GB',
                        'slug'              => 'VR8GB',
                        'status'            => StatusEnum::PUBLISHED(),
                        'order'             => 2,
                        'is_default'        => false,
                    ],
                    [
                        'title'             => 'VR16GB',
                        'slug'              => 'VR16GB',
                        'status'            => StatusEnum::PUBLISHED(),
                        'order'             => 3,
                        'is_default'        => false,
                    ]
                ]
            ],
            [
                'title'                     => 'Kamera',
                'slug'                      => 'kamera',
                'status'                    => StatusEnum::PUBLISHED(),
                'order'                     => 5,
                'display_layout'            => 'radio',
                'is_searchable'             => true,
                'is_comparable'             => true,
                'is_use_in_product_listing' => true,
                'attributes'                => [
                    [
                        'title'             => '4.5mp',
                        'slug'              => '4-5mp',
                        'status'            => StatusEnum::PUBLISHED(),
                        'order'             => 1,
                        'is_default'        => true,
                    ],
                    [
                        'title'             => '12.5mp',
                        'slug'              => '12-5mp',
                        'status'            => StatusEnum::PUBLISHED(),
                        'order'             => 2,
                        'is_default'        => false,
                    ],
                    [
                        'title'             => '23mp',
                        'slug'              => '23mp',
                        'status'            => StatusEnum::PUBLISHED(),
                        'order'             => 3,
                        'is_default'        => false,
                    ]
                ]
            ]
        ];


        if (! AttributeSet::count()){
            foreach ($arrays as $key => $array){
                $attributes = $array['attributes'];

                unset($array['attributes']);

                $attributeSet = AttributeSet::create($array);

                foreach ($attributes as $attribute){
                    $attributeSet->attributes()->create($attribute);
                }
            }
        }

    }
}
