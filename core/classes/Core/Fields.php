<?php
/**
 * Management of input fields
 *
 * @package NamelessMC\Core
 * @author Partydragen
 * @version 2.0.0-pr13
 * @license MIT
 */
class Fields {

    public const TEXT = 1;
    public const TEXTAREA = 2;
    public const DATE = 3;
    public const PASSWORD = 4;
    public const SELECT = 5;
    public const NUMBER = 6;
    public const EMAIL = 7;
    public const RADIO = 8;
    public const CHECKBOX = 9;

    /**
     * @var array Array of all the registered fields.
     */
    private array $_fields = [];

    /**
     * Add a field to this fields instance.
     *
     * @param string $key Unique name for the field item.
     * @param int $type Field type.
     * @param string $label The label for this field.
     * @param bool $required Require user to fill this field.
     * @param string|array $value Default value for this field.
     * @param string|null $placeholder Field placeholder.
     * @param string|null $info Field information.
     * @param int|null $order Field order.
     */
    public function add(string $key, int $type, string $label, bool $required = false, $value = '', ?string $placeholder = null, ?string $info = null, ?int $order = null): void {
        $this->_fields[$key] = [
            'name' => $label,
            'type' => $type,
            'value' => $value,
            'required' => $required,
            'placeholder' => $placeholder ?? $label,
            'info' => $info ?? '',
            'options' => [],
            'order' => $order ?? count($this->_fields)
        ];
    }

    /**
     * Add a option to a field.
     *
     * @param string $field Add the option to this field.
     * @param string $value Field value.
     * @param string $option The option to display.
     */
    public function addOption(string $field, string $value, string $option): void {
        if (isset($this->_fields[$field])) {
            $this->_fields[$field]['options'][] = [
                'value' => $value,
                'option' => $option
            ];
        }
    }

    /**
     * List all fields, sorted by their order.
     *
     * @return array List of fields.
     */
    public function getAll(): iterable {
        $fields = $this->_fields;

        uasort($fields, static function ($a, $b) {
            return $a['order'] - $b['order'];
        });

        return $fields;
    }
}