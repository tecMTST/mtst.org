
Criar um tema no WordPress pode ser muito simples, se você conhece alguns conceitos de HTML, CSS e JavaScrip. Apesar disso, alguns iniciantes com a orientação correta também podem se aventurar. Basta ter um ambiente de desenvolvimento devidamente configurado e o CMS (Content Management System) instalado.

O WordPress é um dos sistemas de gerenciamento de conteúdo de código aberto mais usados em todo o mundo — cerca de 34,7% dos sites são ambientados nele. Tem ferramentas de construção de sites e permite adicionar várias funcionalidades, por meio de plugins e outros recursos que personalizam o layout, aumentam o desempenho e garantem uma experiência qualificada para o seu público.

Neste artigo, apresentamos os primeiros passos para criar um tema em WordPress para que você consiga ter um site com aspectos exclusivos. Veja o que vamos ensinar:

Quais os requisitos para criar um tema no WordPress?
Quais os principais passos para criar o tema na plataforma?
Quais ferramentas podem facilitar a criação de temas no WordPress?
Vamos lá?

Quais são os requisitos para criar um tema no WordPress?
Para criar o tema no WordPress, você deve ter um ambiente de desenvolvimento configurado com Apache, PHP, MySQL e WordPress instalados. O download do WordPress é feito na página oficial — você deve atualizar as credenciais do banco de dados.

Você também precisa conhecer a estrutura do tema. Um tema para WordPress é basicamente uma página normal, criada em HTML e, assim como qualquer outra, é formada pelos arquivos:

header.php: armazena o código do cabeçalho;
footer.php: armazena o código do rodapé;
sidebar.php: local onde são configurados os botões adicionados na lateral da página;
style.css: controla a apresentação visual do tema;
index.php: incorpora as configurações exibidas na página inicial;
single.php: contém códigos que mostram o artigo na sua própria página;
page.php: contém códigos que mostram o conteúdo de uma página única;
archive.php: exibe a listagem de artigos que estão no arquivo, as categorias criadas pelo usuário;
functions.php: local onde ficam algumas funções que adicionam mais funcionalidades aos temas, como logomarca, menus, cor, thumbnails, scripts e stylesheets;
404.php: código de erro que exibe que o arquivo requisitado não foi encontrado.
Além disso, você precisará integrar algumas configurações do Bootstrap para customizar o seu template. Neste artigo, ensinamos o primeiro passo a passo até essa customização. Veja, a seguir.

Quais são os principais passos para criar o tema na plataforma?
Abaixo, estão algumas etapas para você criar o tema para WordPress até que ele possa ser personalizado.

Crie uma pasta para armazenar os arquivos que serão adicionados
Se vamos construir um novo tema, precisamos saber onde os arquivos que o compõem ficarão em sua instalação local. Isso é bem fácil.

Sabemos que uma instalação do WordPress, normalmente, tem um diretório raiz, também chamado wordpress. Nesse diretório, estão os arquivos e pastas que mostraremos a seguir.

Arquivos
composer.json
index.php
license.txt
readme.html
wp-activate.php
wp-blog-header.php
wp-comments-post.php
wp-config.php
wp-config-sample.php
wp-cron.php
wp-links-opml.php
wp-load.php
wp-login.php
wp-mail.php
wp-settings.php
wp-signup.php
wp-trackback.php
xmlrpc.php
Pastas
wp-admin
wp-content
wp-includes
A pasta que buscamos é a wp-content, local onde ficam armazenados os temas e os plugins. Logo, dentro dela, há uma pasta chamada themes, que deve conter todos os temas do seu site WordPress, inclusive o que você criará, para que o CMS reconheça as novas configurações.

criar tema wordpress
Na pasta de themes, já estão armazenadas outras três pastas nativas, que contêm três temas padrão, fornecidos pelo WordPress. Além delas, você deve criar outra pasta, que poderá chamar como quiser.

No exemplo abaixo, a pasta se chama customtheme. A partir dessa unidade, o novo tema para WordPress será criado.

criar tema wordpress
É imprescindível que seu novo tema esteja dentro da pasta “themes”. Dessa forma, é possível ativa-lo e usá-lo online.

Crie os arquivos style.css e index.php
Todo tema para WordPress tem uma série de arquivos obrigatórios para funcionar corretamente. Na sua nova pasta dentro de Themes, crie dois arquivos:

style.css. — arquivo demonstração do WP usado para informar nome do autor, repositório e versão do tema;
index.php. — arquivo principal, por meio do qual o WP carrega os posts que serão exibidos na tela. Além disso, ele é utilizado pelo wp sempre que falta um arquivo base do WP.
criar tema wordpress
style.css
O style.css é um arquivo CSS declarativo e necessário para todos os temas do WordPress. Controla a apresentação (design visual e layout) das páginas do site, ou seja, nesse arquivo, você especificará informações sobre o tema: nome do tema, autor, página do autor e o número de versão (nesse caso, em que não foram feitas atualizações, e sim uma criação de tema do zero, você pode atribuir o número 1), por exemplo.

Essas informações precisam ser escritas de forma padronizada, para que o WP consiga identificá-las, assim como especificamos abaixo:

/*

Theme name: nome do tema (na listagem aparecerá esse nome);

Theme URI: site que pode ser criado para demonstrar as funcionalidades do tema, pode ainda, conter um formulário para que as pessoas consigam comprá-lo;

Author: escreva seu nome, para que as pessoas possam contactá-lo caso tenham interesse no seu tema;

Author URI: se você tiver um site próprio, pode adicioná-lo nessa linha do código;

Github Theme URI: é interessante adicionar o tema no GitHud para que nesse ambiente colaborativo os usuários possam adicionar novas funcionalidades, sugerir melhorias e tirar dúvidas, por exemplo;

<h1>Custom Theme!</h1>