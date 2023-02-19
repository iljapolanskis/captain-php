<?php

use Dotenv\Dotenv;

const ROOT_DIR = __DIR__ . '/../';
const STORAGE_PATH = ROOT_DIR . 'storage';
const VIEW_PATH = ROOT_DIR . 'views';
const ENTITY_PATH = ROOT_DIR . 'app/Entity';
const CONFIG_PATH = ROOT_DIR . 'config';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
