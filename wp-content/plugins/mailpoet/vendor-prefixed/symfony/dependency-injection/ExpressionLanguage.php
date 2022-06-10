<?php
 namespace MailPoetVendor\Symfony\Component\DependencyInjection; if (!defined('ABSPATH')) exit; use MailPoetVendor\Psr\Cache\CacheItemPoolInterface; use MailPoetVendor\Symfony\Component\ExpressionLanguage\ExpressionLanguage as BaseExpressionLanguage; if (!\class_exists(\MailPoetVendor\Symfony\Component\ExpressionLanguage\ExpressionLanguage::class)) { return; } class ExpressionLanguage extends \MailPoetVendor\Symfony\Component\ExpressionLanguage\ExpressionLanguage { public function __construct(\MailPoetVendor\Psr\Cache\CacheItemPoolInterface $cache = null, array $providers = [], callable $serviceCompiler = null) { \array_unshift($providers, new \MailPoetVendor\Symfony\Component\DependencyInjection\ExpressionLanguageProvider($serviceCompiler)); parent::__construct($cache, $providers); } } 