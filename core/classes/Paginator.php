<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Paginator class
 */

class Paginator {

    private $_limit,
        $_page,
        $_total,
        $_class;

    public function __construct($class = array()) {
        if (!count($class)) {
            $this->_class = array('ul' => 'pagination d-inline-flex', 'li' => 'page-item {x}', 'a' => 'page-link');
        } else {
            $this->_class = $class;
        }
    }

    /**
     * Generate object of provided data
     *
     * @param array $data Data to paginate.
     * @param int|null $limit
     * @param int|null $page
     * @param int|null $total
     * @return object
     */
    public function getLimited($data, $limit = 10, $page = 1, $total = 10) {
        $this->_limit = $limit;
        $this->_page = (int) $page;

        $return = array();

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
     * @param string|null $href URL prefix to use when next page is clicked.
     * @return string Generated HTML to display in template.
     */
    public function generate($links, $href = '?') {
        $last = ceil($this->_total / $this->_limit);

        $start = (($this->_page - $links) > 0) ? $this->_page - $links : 1;
        $end = (($this->_page + $links) < $last) ? $this->_page + $links : $last;

        if (isset($this->_class['div']) && !empty($this->_class['div']))
            $html = '<div class="' . $this->_class['div'] . '">';
        else
            $html = '<ul class="' . $this->_class['ul'] . '">';

        if (empty($this->_class['ul'])) {
            $class = str_replace('{x}', ($this->_page == 1 ? ' disabled ' : ''), ($this->_class['a']));

            $html .= '<a class="' . $class . '" href="' . (($this->_page == 1) ? '#' : $href . 'p=' . ($this->_page - 1)) . '">&laquo;</a>';
        } else {
            $class = str_replace('{x}', ($this->_page == 1) ? ' disabled' : '', $this->_class['li']);

            $html .= '<li class="' . $class . '"><a class="' . str_replace('{x}', ($this->_page == 1 ? ' disabled ' : ''), $this->_class['a']) . '" href="';
            if ($this->_page == 1) $html .= '#';
            else $html .= $href . 'p=' . ($this->_page - 1);
            $html .= '">&laquo;</a></li>';
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
            $html .= '<a class="' . str_replace('{x}', ($this->_page == $last) ? ' disabled ' : '', $this->_class['a']) . '" href="' . (($this->_page == $last) ? '#' : $href . 'p=' . ($this->_page + 1)) . '">&raquo;</a>';
        } else {
            $html .= '<li class="' . str_replace('{x}', ($this->_page == $last) ? ' disabled ' : '', $this->_class['li']) . '"><a class="' . str_replace('{x}', ($this->_page == $last) ? ' disabled ' : '', $this->_class['a']) . '" href="';
            if ($this->_page == $last) $html .= '#';
            else $html .= $href . 'p=' . ($this->_page + 1);
            $html .= '">&raquo;</a></li>';
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
    public function setValues($total, $limit, $page) {
        $this->_total = $total;
        $this->_limit = $limit;
        $this->_page = $page;
    }
}
