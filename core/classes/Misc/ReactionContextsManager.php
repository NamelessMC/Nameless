<?php
/**
 * Manages registering and retrieving reaction contexts.
 *
 * @package NamelessMC\Misc
 * @author Aberdeener
 * @version 2.2.0
 * @license MIT
 * @see ReactionContext
 */
class ReactionContextsManager extends Instanceable
{
    /** @var ReactionContext[] */
    private array $_contexts = [];

    /**
     * Register a new reaction context.
     *
     * @param ReactionContext $context Reaction context to register.
     */
    public function provideContext(ReactionContext $context): void
    {
        $this->_contexts[$context->name()] = $context;
    }

    /**
     * Get a reaction context by name.
     *
     * @param  string               $name Name of reaction context to get.
     * @return ReactionContext|null Reaction context with the given name, or throws error if it does not exist.
     */
    public function getContext(string $name): ?ReactionContext
    {
        if (!isset($this->_contexts[$name])) {
            throw new InvalidArgumentException('Invalid reaction context name: ' . $name);
        }

        return $this->_contexts[$name];
    }

    /**
     * Get all registered reaction contexts.
     *
     * @return ReactionContext[] All registered reaction contexts.
     */
    public function getContexts(): array
    {
        return $this->_contexts;
    }

    /**
     * Get all valid reaction context names.
     *
     * @return string[] All valid reaction context names.
     */
    public function validContextNames(): array
    {
        return array_map(static function (ReactionContext $context) {
            return $context->name();
        }, $this->enabledContexts());
    }

    /**
     * Get all valid reaction context friendly names.
     *
     * @param  Language $language Language to translate friendly names in.
     * @return string[] All valid reaction context friendly names.
     */
    public function validContextFriendlyNames(Language $language): array
    {
        return array_map(static function (ReactionContext $context) use ($language) {
            return $context->friendlyName($language);
        }, $this->enabledContexts());
    }

    /**
     * Get all enabled reaction contexts.
     *
     * @return ReactionContext[] All enabled reaction contexts.
     */
    private function enabledContexts(): array
    {
        return array_filter($this->_contexts, static function (ReactionContext $context) {
            return $context->isEnabled();
        });
    }
}
