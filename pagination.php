<?php

function pagination($limit, $total, $page_number = NULL, array $config = array(), $callback = null)
{

    $page_number = (empty(trim($page_number)) || $page_number == NULL) ? 1 : $page_number;
    $total_pages = ceil($total / $limit);
    $config['link_limit'] = (isset($config['link_limit'])) ? $config['link_limit'] : 3;
    $pagLink = "";

    $startIndex = ($page_number -  $config['link_limit']);
    $endIndex = ($page_number +  $config['link_limit']);
    $loopLength = $endIndex - $startIndex + 1;

    $link_arr = array();

    if($callback && $total_pages <= 0){
        $callback(['error' => 'found total page number in 0']);
    }


    if($callback && $total_pages < $page_number){
        $callback(['error' => 'pagination initialized with invalid page number']);
    }

    if (isset($config['link_limit']) && $total_pages > 0 && $total_pages >= $page_number) {

        $link_arr['first'] = 'first';

        $link_arr['prev'] = '<';

        if ($total_pages < $loopLength) {
            $i = 1;
            $endIndex = $total_pages;

            for ($i; $i <= $endIndex; $i++) {

                $link_arr[$i] = (string) $i;
            }
        } else if ($endIndex > $total_pages) {

            $i = $total_pages - ($config['link_limit'] * 2);
            $endIndex = $total_pages;

            for ($i; $i <= $endIndex; $i++) {

                $link_arr[$i] = (string) $i;
            }
        } else if ($startIndex > 0) {

            $i = $startIndex;

            for ($i; $i <= $endIndex; $i++) {

                $link_arr[$i] = (string) $i;
            }
        } else {

            $i = 1;
            $endIndex = ($config['link_limit'] * 2) + 1;

            for ($i; $i <= $endIndex; $i++) {

                $link_arr[$i] = (string) $i;
            }
        }


        $link_arr['next'] = '>';

        $link_arr['last'] = 'last';
    }

    $data = $link_arr;

    $last_key = $total_pages;
    $el = "";
    $el .= $config['start_tag'];
    foreach ($data as $key => $value) {

        if ($key == $page_number) {
            $el .=  str_replace(['{value}'], [$value], $config['active_link']);
        } else if ($key == 'prev') {
            $el .=  str_replace(['{link}', '{value}'], [((1 >= $page_number) ? $page_number : ($page_number - 1)), $value], $config['link']);
        } else if ($key == 'next') {
            $el .=  str_replace(['{link}', '{value}'], [((!($last_key > $page_number)) ? $page_number : ($page_number + 1)), $value], $config['link']);
        } else if ($key == 'first') {
            $el .=  str_replace(['{link}', '{value}'], [1, 'first'], $config['link']);
        } else if ($key == 'last') {
            $el .=  str_replace(['{link}', '{value}'], [$last_key, 'last'], $config['link']);
        } else {
            $el .=  str_replace(['{link}', '{value}'], [trim($key), $value], $config['link']);
        }
    };
    $el .= $config['end_tag'];
    echo $el;
}

?>


<style>
    .pagination {
        display: flex;
        list-style: none;
        justify-content: center;
        height: 100vh;
        align-items: center;
    }

    .pagination .item {
        padding: 0px 6px;
        padding-top: 2px;
        border: solid 1px #00f;
        margin: 0 4px;

    }

    .pagination .item a {
        text-decoration: none;
        color: #00f;
    }

    .pagination .item.active {
        background: #00f;
        color: #fff;
    }

    .pagination .item.active a:hover {
        color: #fff;
    }

    .pagination .item.active a {
        color: #fff;
    }

    .pagination .item:hover {
        background: #d6d6ff;
    }
</style>

<?php
$pagination_config = array(
    'start_tag' => '<ul class="pagination">',
    'link' => '<li class="item"><a href="?page={link}">{value}</a></li>',
    'active_link' => '<li class="item active"><a href="javascript:{}">{value}</a></li>',
    'end_tag' => '</ul>',
    'link_limit' => 4,
);
pagination(10, 0, @$_GET['page'], $pagination_config);
?>