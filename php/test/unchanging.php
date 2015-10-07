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

// todo assert these are all the same
$name1 = Singleton::test("button")->name;
$name2 = Singleton::test("button")->name;
$name3 = Singleton::test("button")->name;
$name4 = Singleton::test("button")->name;
$name5 = Singleton::test("button")->name;
