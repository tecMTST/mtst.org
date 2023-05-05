<?php
define( 'WP_CACHE', false ); // Added by WP Rocket



 // Added by WP Rocket

/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa usar o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do banco de dados
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Configurações do banco de dados - Você pode pegar estas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define( 'DB_NAME', 'mtst' );

/** Usuário do banco de dados MySQL */
define( 'DB_USER', 'root' );

/** Senha do banco de dados MySQL */
define( 'DB_PASSWORD', '' );

/** Nome do host do MySQL */
define( 'DB_HOST', 'localhost' );

/** Charset do banco de dados a ser usado na criação das tabelas. */
define( 'DB_CHARSET', 'utf8mb4' );

/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define( 'DB_COLLATE', '' );

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para invalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'U9(E4iM?KO4(K|Fp37&pk/+!ZlC(uxP2il+t~!a})K6F.`@G+:6M>W)<@Q5y?-i%' );
define( 'SECURE_AUTH_KEY',  ')jx%-V!IG2kOUlEK7 Ak#V/=)@VDx4z(,7<.B.f*N,z#7ZZ96S-d2D>edA`qI4QV' );
define( 'LOGGED_IN_KEY',    'hWvv_SmlaT5<[fs,A0?9>gLK=fn?Mf)n*rt-b$Q1Xo e^1TCIfPX+,TdQLaC!@%%' );
define( 'NONCE_KEY',        'lVEP*=%uze,07ofF[A;D<g@mq{nP5q6g/!kD-Vb!9(J*-56,WcNLhp;A*Js48B1U' );
define( 'AUTH_SALT',        'N:uqW.K8>Y!:E2cj5qh#Sr6rFSG(LyxFN:no-$Lejt|#)k=O8 VrqF3pcE!b2=9,' );
define( 'SECURE_AUTH_SALT', 'Wq{yCo;d]=daL>YC|ye68xi;D;ypY)hf(nc8BsK*wvsxXsJ6eG{[cq^6]|b@c.f6' );
define( 'LOGGED_IN_SALT',   '2mP/)3uB(P~6;Ao`GoCvd)KBcn$.3Nn1PXH@5OvFij3[X:fg8P1qgID)fxp@T_{Z' );
define( 'NONCE_SALT',       '^O^NOm+%)EAYdB&r%|uY_PM xvf6Z{K~##NJ0nEz?{@K=rg7t;lB-4s=r#FS?-$E' );

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * um prefixo único para cada um. Somente números, letras e sublinhados!
 */
$table_prefix = 'wp_';

/**
 * Para desenvolvedores: Modo de debug do WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
// ini_set('display_errors','Off');
// ini_set('error_reporting', E_ALL );
define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', true);

/* Adicione valores personalizados entre esta linha até "Isto é tudo". */



/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Configura as variáveis e arquivos do WordPress. */
require_once ABSPATH . 'wp-settings.php';
