<?php
namespace local_qlapi\type;

use local_qlapi\type\local_qlapi_categorytype;
use local_qlapi\type\local_qlapi_querytype;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;

/**
 * Class Types
 *
 * Registry for types
 *
 * @package App
 */
class local_qlapi_types
{
    // Object types:
    private static $category;
    private static $course;
    private static $query;
    private static $mutation;


    /**
     * @return UserType
     */
    public static function category()
    {
        return self::$category ?: (self::$category = new local_qlapi_categorytype());
    }

    /**
     * @return QueryType
     */
    public static function query()
    {
        return self::$query ?: (self::$query = new local_qlapi_querytype());
    }

    /**
     * @return QueryType
     */
    public static function mutation()
    {
        return self::$mutation ?: (self::$mutation = new MutationType());
    }

    // Internal types

    /**
     * @return \GraphQL\Type\Definition\BooleanType
     */
    public static function boolean()
    {
        return Type::boolean();
    }

    /**
     * @return \GraphQL\Type\Definition\FloatType
     */
    public static function float()
    {
        return Type::float();
    }

    /**
     * @return \GraphQL\Type\Definition\IDType
     */
    public static function id()
    {
        return Type::id();
    }

    /**
     * @return \GraphQL\Type\Definition\IntType
     */
    public static function int()
    {
        return Type::int();
    }

    /**
     * @return \GraphQL\Type\Definition\StringType
     */
    public static function string()
    {
        return Type::string();
    }

    /**
     * @param Type $type
     * @return ListOfType
     */
    public static function listOf($type)
    {
        return new ListOfType($type);
    }

    /**
     * @param Type $type
     * @return NonNull
     */
    public static function nonNull($type)
    {
        return new NonNull($type);
    }
}