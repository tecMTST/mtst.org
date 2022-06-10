    <header>
        <div class="topo">
            <div class="container">
                <div class="row">
                    <div class="col-md-2">
                        <div class="logo-mtst">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" width="106" height="106" alt="Logo do MTST">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="busca">
                            <form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
                                <div class="search-container">
                                    <input type="text" name="s" id="search" placeholder="DIGITE A SUA BUSCA AQUI" value=""/>
                                    <button aria-label="Buscar" class="lupa-busca" type="submit" value=""><img width="36" height="36" alt="Pesquisar" src="<?php echo get_template_directory_uri(); ?>/assets/images/lupa.png" width="33" height="36"/></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="redes-cont">
                            <ul class="redes-sociais">
                                <li><a href="https://www.instagram.com/mtstbrasil/?hl=pt" target="_blank" rel="noopener noreferrer"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/instagram.png" alt="Instagram"></a></li>
                                <li><a href="https://www.facebook.com/mtstbrasil/" target="_blank" rel="noopener noreferrer"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/facebook.png" alt="Facebook"></a></li>
                                <li><a href="https://twitter.com/mtst" target="_blank" rel="noopener noreferrer"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/twitter.png" alt="Twitter"></a></li>
                                <li><a href="https://www.youtube.com/channel/UC3OzrZMhnmEgVtxpJoDRkeg" target="_blank" rel="noopener noreferrer"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/youtube.png" alt="Youtube"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-1">

                    </div>
                </div>
            </div>
        </div>
        <div class="menu-principal container">
            <nav>
                <ul>
                    <li><a class="nav-link active" href="<?php home_url(); ?>">Home</a></li>
                    <li><a class="nav-link" href="#">Quem Somos</a></li>
                    <li><a class="nav-link" href="#">Notícias</a></li>
                    <li><a class="nav-link" href="#">Campanhas</a></li>
                    <li><a class="nav-link" href="#">Loja</a></li>
                    <li><a class="nav-link" href="#">Galeria</a></li>
                </ul>
            </nav>
        </div>
    </header>


