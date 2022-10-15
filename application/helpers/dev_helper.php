<?php
function pre($x, $d='')
{
    echo "<pre>";
    print_r($x);
    if ($d == 'd') {
        die();
    }
}
