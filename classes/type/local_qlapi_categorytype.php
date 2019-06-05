<?php
namespace local_qlapi\type;

use local_qlapi\data\local_qlapi_datasource;
use local_qlapi\type\local_qlapi_types;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Class local_qlapi_categorytype
 * @package local_qlapi
 */
class local_qlapi_categorytype extends ObjectType
{
    private $courses = [];
    public function __construct()
    {
        $config = [
            'name' => 'Category',
            'fields' => [
                'id' => Type::nonNull(local_qlapi_types::int()),
                'name' => Type::nonNull(local_qlapi_types::string())
            ]
        ];
        parent::__construct($config);
    }
}