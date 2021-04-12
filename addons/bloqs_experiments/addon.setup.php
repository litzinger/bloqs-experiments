<?php

if (! defined('BLOQS_EXPERIMENTS_VERSION')) {
    define('BLOQS_EXPERIMENTS_NAME', 'Bloqs Experiments');
    define('BLOQS_EXPERIMENTS_VERSION', '1.0.0');
}

return [
    'author'      => 'BoldMinded',
    'author_url'  => 'https://boldminded.com/add-ons/experiments',
    'docs_url'    => 'http://docs.boldminded.com/experiments',
    'name'        => BLOQS_EXPERIMENTS_NAME,
    'description' => 'A/B Experiments for Bloqs',
    'version'     => BLOQS_EXPERIMENTS_VERSION,
    'namespace'   => 'BoldMinded\BloqsExperiments',
    'settings_exist' => false,
];
