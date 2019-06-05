<?php
namespace local_qlapi\type;

use local_qlapi\type\local_qlapi_types;
use local_qlapi\data\local_qlapi_datasource;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class local_qlapi_querytype extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Query',
            'fields' => [
                'message' => [
                    'type' => Type::string(),
                    'args' => [
                        'name' => Type::string(),
                    ],
                    'resolve' => function ($root, $args) {
                        return 'hello ' . $args['name'];
                    }
                ],
                'categories' => [
                    'type' => local_qlapi_types::listOf(local_qlapi_types::category()),
                    'description' => 'List categories',
                    'resolve' => function ($root, $args) {
                        return local_qlapi_datasource::local_qlapi_get_categories();
                    }
                ],
            ]
        ];
        parent::__construct($config);
    }
}
