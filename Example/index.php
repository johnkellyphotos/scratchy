<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Src/autoloader.php";

use elements\form;
use elements\h1;
use elements\p;
use component\PasswordInput;
use component\SubmitButton;
use component\TextInput;
use component\WebPage;

const _INDENT_ = true; # set to false in order to remove white space and new lines

$form = new form(
    attributes: [
        'action' => '/',
        'method' => 'POST',
    ]
);

$webPage = new WebPage(
    new h1('Welcome to Scratchy!'),
    new p('Scratchy let\'s you create consistent HTML from a standardized format and library.'),
    $form,
);

$form->append(new TextInput('Username', 'Enter your username.', attributes: ['required']));
$form->append(new PasswordInput('Password', 'Enter your password.'));

$form->append(new SubmitButton(content: 'Submit'));

$webPage->output();