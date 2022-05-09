<?php
/**
 * Integrations class
 *
 * @package NamelessMC\Integrations
 * @author Partydragen
 * @version 2.0.0-pr13
 * @license MIT
 */
class Integrations extends Instanceable {

    /**
     * @var IntegrationBase[] $integrations The array of integrations
     */
    private array $_integrations = [];

    /**
     * Register an integration to the integration list.
     *
     * @param IntegrationBase $integration Instance of intagration to register.
     */
    public function registerIntegration(IntegrationBase $integration): void {
        $this->_integrations[$integration->getName()] = $integration;
    }

    /**
     * Get a integration by name.
     *
     * @param string $name Name of integration to get.
     *
     * @return IntegrationBase|null Instance of integration with same name, null if it doesnt exist.
     */
    public function getIntegration(string $name): ?IntegrationBase {
        foreach ($this->_integrations as $integration) {
            if (strcasecmp($name, $integration->getName()) == 0) {
                return $integration;
            }
        }

        return null;
    }

    /**
     * List all integrations, sorted by their order.
     *
     * @return IntegrationBase[] List of integrations.
     */
    public function getAll(): iterable {
        $integrations = $this->_integrations;

        uasort($integrations, static function (IntegrationBase $a, IntegrationBase $b) {
            return $a->getOrder() - $b->getOrder();
        });

        return $integrations;
    }

    /**
     * List all enabled integrations, sorted by their order.
     *
     * @return IntegrationBase[] List of integrations.
     */
    public function getEnabledIntegrations(): iterable {
        $integrations = $this->_integrations;

        $enabled_integrations = [];
        foreach ($integrations as $integration) {
            if ($integration->isEnabled()) {
                $enabled_integrations[] = $integration;
            }
        }

        uasort($enabled_integrations, static function ($a, $b) {
            return $a->getOrder() - $b->getOrder();
        });

        return $enabled_integrations;
    }
}
