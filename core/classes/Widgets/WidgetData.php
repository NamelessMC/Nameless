<?php

class WidgetData
{
    public string $location;
    public int $order;
    public array $pages;

    public function __construct(object $data)
    {
        $this->location = $data->location;
        $this->order = $data->order;
        $this->pages = $data->pages === null
            ? []
            : (is_array($data->pages)
                ? $data->pages
                : json_decode($data->pages, true));
    }
}
