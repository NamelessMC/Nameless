<?php
/**
 * Contains methods to register + apply API route binding transformers.
 *
 * @package NamelessMC\Endpoints
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
trait ManagesTransformers {

    /**
     * @var array Mapping of key names to closures to transform a variable into an object (ie, a user ID to a User object)
     */
    private static array $_transformers = [];

    /**
     * Get all registered transformers
     *
     * @return array All transformers.
     */
    public static function getAllTransformers(): array {
        return self::$_transformers;
    }

    /**
     * Register a transformer for API route binding.
     *
     * @param string $type The name of the transformer. This is used to identify the transformer when binding.
     * @param string $module The name of the module that registered the transformer.
     * @param Closure(Nameless2API, string): mixed $transformer Function which converts the value in the URL to the desired type.
     */
    public static function registerTransformer(string $type, string $module, Closure $transformer): void {
        if (isset(self::$_transformers[$type])) {
            throw new InvalidArgumentException("A transformer with for the type '$type' has already been registered by the '" . self::$_transformers[$type]['module'] . "' module.");
        }

        $reflection = new ReflectionFunction($transformer);
        $reflectionParams = $reflection->getParameters();
        if (count($reflectionParams) !== 2) {
            throw new InvalidArgumentException('Endpoint variable transformer must take 2 arguments (Nameless2API and the raw variable).');
        }

        // if they've provided a typehint for the first argument, make sure it's taking Nameless2API
        $param = $reflectionParams[0];
        if ($param->getType() instanceof ReflectionNamedType && $param->getType()->getName() !== Nameless2API::class) {
            throw new InvalidArgumentException('Endpoint variable transformer must take Nameless2API as the first argument.');
        }

        // check that the second argument is a string
        $param = $reflectionParams[1];
        if ($param->getType() instanceof ReflectionNamedType && $param->getType()->getName() !== 'string') {
            throw new InvalidArgumentException('Endpoint variable transformer must take a string as the second argument.');
        }

        self::$_transformers[$type] = [
            'module' => $module,
            'transformer' => $transformer,
        ];
    }

    /**
     * Convert a value through a transformer based on its type. If no transformer is found, the value is returned as-is.
     *
     * @param Nameless2API $api Instance of API to provide the transformer.
     * @param string $type The type to use.
     * @param string $value The value to convert.
     * @return mixed The converted value.
     */
    public static function transform(Nameless2API $api, string $type, string $value) {
        if (array_key_exists($type, self::$_transformers)) {
            return self::$_transformers[$type]['transformer']($api, $value);
        }

        return $value;
    }
}
