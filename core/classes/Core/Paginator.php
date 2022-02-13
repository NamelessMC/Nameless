<?php
/**
 * Paginator class
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class Paginator {

    private int $_limit;
    private int $_page;
    private int $_total;
    private array $_class;
    private string $_leftContent;
    private string $_rightContent;

    public function __construct(array $class = [], $leftContent = "&laquo;", $rightContent = "&raquo;") {
        if (!count($class)) {
            $this->_class = ['ul' => 'pagination d-inline-flex', 'li' => 'page-item {x}', 'a' => 'page-link'];
        } else {
            $this->_class = $class;
        }

        $this->_leftContent = $leftContent;
        $this->_rightContent = $rightContent;
    }

    /**
     * Generate object of provided data
     *
     * @param array $data Data to paginate.
     * @param int $limit
     * @param int $page
     * @param int $total
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
     *
     * @return string Generated HTML to display in template.
     */
    public function generate(int $links, string $href = '?'): string {
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
            if ($this->_page == 1) $html .= '#';
            else $html .= $href . 'p=' . ($this->_page - 1);
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
            if ($this->_page == $last) $html .= '#';
            else $html .= $href . 'p=' . ($this->_page + 1);
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
     * Set values of instance variables, alternative function (as they are set in getLimited()).
     * Not used internally.
     *
     * @param int $total
     * @param int $limit
     * @param int $page
     */
    public function setValues(int $total, int $limit, int $page): void {
        $this->_total = $total;
        $this->_limit = $limit;
        $this->_page = $page;
    }
}
