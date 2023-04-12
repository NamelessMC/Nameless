<?php
/**
 * Helps paginate data to an HTML table easily.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @author graphiteisaac
 * @version 2.0.0-pr13
 * @license MIT
 */
class Paginator {

    /**
     * @var int The number of items per page.
     */
    private int $_limit;

    /**
     * @var int The current page.
     */
    private int $_page;

    /**
     * @var int The total number of items.
     */
    private int $_total;

    /**
     * @var array<string, string> The classes to use for `ul`, `li` and `a` HTML element styling.
     */
    private array $_class;

    /**
     * @var string The element to use for the left arrow.
     */
    private string $_leftContent;

    /**
     * @var string The element to use for the right arrow.
     */
    private string $_rightContent;

    /**
     * @param array|null $class Optional array of class names, if provided the required element keys are ul, li and a
     * @param string|null $leftContent Optional string to display in "previous" button, default &laquo;
     * @param string|null $rightContent Optional string to display in "next" button, default &raquo;
     */
    public function __construct(?array $class = [], ?string $leftContent = null, ?string $rightContent = null) {
        if (!is_array($class) || !count($class)) {
            $this->_class = ['ul' => 'pagination d-inline-flex', 'li' => 'page-item {x}', 'a' => 'page-link'];
        } else {
            $this->_class = $class;
        }

        $this->_leftContent = $leftContent ?? '&laquo;';
        $this->_rightContent = $rightContent ?? '&raquo;';
    }

    /**
     * Generate object of provided data
     *
     * @param array $data Data to paginate.
     * @param int $limit Number of items per page.
     * @param int $page Current page.
     * @param int $total Total number of items.
     *
     * @return object
     */
    public function getLimited(array $data, int $limit = 10, int $page = 1, int $total = 10): object {
        $this->_limit = $limit;
        $this->_page = $page;

        $return = [];

        for ($i = ($this->_page != 1 ? (($this->_page - 1) * $limit) : 0); $i < ($this->_page * $limit); $i++) {
            if (!isset($data[$i])) {
                break;
            }

            $return[] = $data[$i];
        }

        $this->_total = $total;

        $result = new stdClass();
        $result->page = $this->_page;
        $result->limit = $this->_limit;
        $result->total = $this->_total;
        $result->data = $return;

        return $result;
    }

    /**
     * Generate HTML for data to be presented with.
     *
     * @param int $links Number of links to be shown on each page.
     * @param string $href URL prefix to use when next page is clicked.
     * @return string Generated HTML to display in template.
     */
    public function generate(int $links, string $href = '?'): string {
        $href .= '&';

        $last = ceil($this->_total / $this->_limit);

        $start = (($this->_page - $links) > 0) ? $this->_page - $links : 1;
        $end = (($this->_page + $links) < $last) ? $this->_page + $links : $last;

        if (isset($this->_class['div']) && !empty($this->_class['div'])) {
            $html = '<div class="' . $this->_class['div'] . '">';
        } else {
            $html = '<ul class="' . $this->_class['ul'] . '">';
        }

        if (empty($this->_class['ul'])) {
            $class = str_replace('{x}', ($this->_page == 1 ? ' disabled ' : ''), ($this->_class['a']));

            $html .= '<a class="' . $class . '" href="' . (($this->_page == 1) ? '#' : $href . 'p=' . ($this->_page - 1)) . '">' . $this->_leftContent . '</a>';
        } else {
            $class = str_replace('{x}', ($this->_page == 1) ? ' disabled' : '', $this->_class['li']);

            $html .= '<li class="' . $class . '"><a class="' . str_replace('{x}', ($this->_page == 1 ? ' disabled ' : ''), $this->_class['a']) . '" href="';
            if ($this->_page == 1) {
                $html .= '#';
            } else {
                $html .= $href . 'p=' . ($this->_page - 1);
            }
            $html .= '">' . $this->_leftContent . '</a></li>';
        }

        if ($start > 1) {
            if (empty($this->_class['ul'])) {
                $html .= '<a class="' . str_replace('{x}', '', $this->_class['a']) . '" href="' . $href . 'p=1">1</a>';
                $html .= '<a class="' . str_replace('{x}', ' disabled ', $this->_class['a']) . '" href="#">...</a>';
            } else {
                $html .= '<li class="' . str_replace('{x}', '', $this->_class['li']) . '"><a class="' . str_replace('{x}', '', $this->_class['a']) . '" href="' . $href . 'p=1">1</a></li>';
                $html .= '<li class="' . str_replace('{x}', ' disabled ', $this->_class['li']) . '"><a href="#" class="' . str_replace('{x}', ' disabled ', $this->_class['a']) . '">...</a></li>';
            }
        }

        for ($i = $start; $i <= $end; $i++) {
            if (empty($this->_class['ul'])) {
                $class = str_replace('{x}', ($this->_page == $i) ? ' active ' : '', $this->_class['a']);
                $html .= '<a class="' . $class . '" href="' . $href . 'p=' . $i . '">' . $i . '</a>';
            } else {
                $class = str_replace('{x}', ($this->_page == $i) ? ' active ' : '', $this->_class['li']);
                $html .= '<li class="' . $class . '"><a class="' . str_replace('{x}', ($this->_page == $i) ? ' active ' : '', $this->_class['a']) . '" href="' . $href . 'p=' . $i . '">' . $i . '</a></li>';
            }
        }

        if ($end < $last) {
            if (empty($this->_class['ul'])) {
                $html .= '<a class="' . str_replace('{x}', ' disabled ', $this->_class['a']) . '">...</a>';
                $html .= '<a class="' . str_replace('{x}', '', $this->_class['a']) . '" href="' . $href . 'p=' . $last . '">' . $last . '</a>';
            } else {
                $html .= '<li class="' . str_replace('{x}', ' disabled ', $this->_class['li']) . '"><a href="#" class="' . str_replace('{x}', ' disabled ', $this->_class['a']) . '">...</a></li>';
                $html .= '<li class="' . str_replace('{x}', '', $this->_class['li']) . '"><a class="' . str_replace('{x}', '', $this->_class['a']) . '" href="' . $href . 'p=' . $last . '">' . $last . '</a></li>';
            }
        }

        if (empty($this->_class['ul'])) {
            $html .= '<a class="' . str_replace('{x}', ($this->_page == $last) ? ' disabled ' : '', $this->_class['a']) . '" href="' . (($this->_page == $last) ? '#' : $href . 'p=' . ($this->_page + 1)) . '">' . $this->_rightContent . '</a>';
        } else {
            $html .= '<li class="' . str_replace('{x}', ($this->_page == $last) ? ' disabled ' : '', $this->_class['li']) . '"><a class="' . str_replace('{x}', ($this->_page == $last) ? ' disabled ' : '', $this->_class['a']) . '" href="';
            if ($this->_page == $last) {
                $html .= '#';
            } else {
                $html .= $href . 'p=' . ($this->_page + 1);
            }
            $html .= '">' . $this->_rightContent . '</a></li>';
        }

        if (isset($this->_class['div']) && !empty($this->_class['div'])) {
            $html .= '</div>';
        } else {
            $html .= '</ul>';
        }

        return $html;
    }

    /**
     * Set values of instance variables, alternative function if we don't have all the $data
     *
     * @param int $total Total number of items
     * @param int $limit Number of items per page
     * @param int $page Current page
     */
    public function setValues(int $total, int $limit, int $page): void {
        $this->_total = $total;
        $this->_limit = $limit;
        $this->_page = $page;
    }
}
