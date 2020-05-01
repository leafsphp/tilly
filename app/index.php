<?php

require "./bootstrap.php";

use Leaf\Helpers\Tilly;

$collection = new Tilly\Collection;


echo json_encode($collection->slice([1, 2, 3, 5, 7, 1, 3, 44], 2, 5));
