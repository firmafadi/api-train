<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use Rebing\GraphQL\Support\Type as GraphQLType;
use Type;

class GetBook extends GraphQLType
{
    protected $attributes = [
        'name' => 'GetBook',
        'description' => 'A type'
    ];

    public function fields(): array
    {
        return [
            "title" => [
                "type" => Type::string(),
                "description" => 'title of book',
            ],
            "author" => [
                "type" => Type::string(),
                "description" => 'author of book',
            ],
            "release_date" => [
                "type" => Type::date(),
                "desription" => 'release date'
            ],
            "publisher" => [
                "type" => Type::string(),
                "description" => 'publisher of book',
            ],
            "price" => [
                "type" => Type::int(),
                "description" => 'price of book',
            ],
        ];
    }
}
