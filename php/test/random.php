<?php

Singleton::register(array(
  'name' => "button",
  "variations" => array(
    "blue" => array(
      "probability" => 1.0 / 12.0
    ),
    "green" => array(
      "probability" => 1.0 / 6.0
    ),
    "red" => array(
    ),
    "yellow" => array(
    ),
  )
));

$randomPlacements = array();
foreach (range(0, 100000) as $number) {
  $name = Singleton::get("button")->randomVariant()->name;
  $randomPlacements[$name] = array_key_exists($name, $randomPlacements) ? $randomPlacements[$name] : 0;
  $randomPlacements[$name]++;
}

// todo assert random is within some reasonable random
