<?php

$result = array();

$result[] = array('id'=>1, 'title' => 'hello письмо');
$result[] = array('id'=>2, 'title' => 'hello письмо2');
$result[] = array('id'=>3, 'title' => 'hello письмо3');




$out = array(
    'success' => true,
    'result' => $result
);


echo json_encode($out);