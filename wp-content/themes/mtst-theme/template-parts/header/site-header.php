    <header>
        <div class="topo topo-desktop">
            <div class="container">
                <div class="row">
                    <div class="col-md-2">
                        <div class="logo-mtst">
                            <a href="<?php echo site_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-novo.png" width="106" height="106" alt="Logo do MTST"></a>
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
                    <div class="col-md-4">
                        <div class="redes-cont">
                            <ul class="redes-sociais">
                                <li><a href="https://www.instagram.com/mtstbrasil/?hl=pt"  rel="noopener noreferrer"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/instagram.png" alt="Instagram"></a></li>
                                <li><a href="https://www.facebook.com/mtstbrasil/"  rel="noopener noreferrer"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/facebook.png" alt="Facebook"></a></li>
                                <li><a href="https://twitter.com/mtst"  rel="noopener noreferrer"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/twitter.png" alt="Twitter"></a></li>
                                <li><a href="https://www.youtube.com/channel/UC3OzrZMhnmEgVtxpJoDRkeg"  rel="noopener noreferrer"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/youtube.png" alt="Youtube"></a></li>
                                <li><a href="https://www.tiktok.com/@mtstbr"  rel="noopener noreferrer"><img class="tiktok-icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/ic_baseline-tiktok.png" alt="Tik Tok"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="menu-principal container">
            <nav role="navigation">
                <ul class="menu" id="menu">
                    <li><a class="nav-link menu-home active" href="<?php echo home_url(); ?>">Home</a></li>
                    <li><a class="nav-link menu-quem-somos" href="#">Quem Somos</a>
                        <ul class="submenu">
                            <li><a href="<?php echo home_url(); ?>/quem-somos/o-mtst">O MTST</a></li>
                            <li><a href="<?php echo home_url(); ?>/quem-somos/fake-news">Fato ou fake</a></li>
                        </ul>
                    </li>
                    <li><a class="nav-link menu-noticias" href="#">Notícias</a>
                        <ul class="submenu">
                            <li><a href="<?php echo home_url(); ?>/noticias/ultimas-noticias">Últimas Notícias</a></li>
                        </ul>
                    </li>
                    <li><a class="nav-link menu-campanha" href="#">Contribua</a>
                        <ul class="submenu">
                            <li><a href="<?php echo home_url(); ?>/contribuicao/">MTST</a></li>
                            <li><a href="https://apoia.se/cozinhasolidaria" >Cozinhas Solidárias</a></li>
                            <li><a href="https://www.catarse.me/colabore_mtst" >Educação Popular</a></li>
                        </ul>
                    </li>
                    <li><a class="nav-link menu-campanha" href="#">Nossos Projetos</a>
                        <ul class="submenu">
                            <li><a href="<?php echo home_url(); ?>/cozinhas-solidarias/">Cozinhas Solidárias</a></li>
                            <li><a href="<?php echo home_url(); ?>/centro-popular-de-pesquisa/" >Centro Popular de Pesquisa</a></li>
                            <li><a href="<?php echo home_url(); ?>/contrate-quem-luta/" >Contrate Quem Luta</a></li>
                        </ul>
                    </li>
                    <li><a class="nav-link menu-loja" href="https://wa.me/5511914631714" >Loja</a></li>
                    <li><a class="nav-link menu-galeria" href="#">Galeria</a>
                        <ul class="submenu">
                            <li><a href="https://open.spotify.com/show/5X2nnU5w9uptyMiApbPCWj" >Podcasts</a></li>
                            <li><a href="https://www.youtube.com/c/MTSTBrasil" >Vídeos</a></li>
                            <li><a href="https://www.instagram.com/mtstbrasil/" >Fotos</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="topo topo-mobile">
            <div class="container">
                <div class="row">
                    <div class="col-3 col-logo">
                        <div class="logo-mtst">
                            <a href="<?php echo site_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-novo.png" width="106" height="106" alt="Logo do MTST"></a>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="busca">
                        <button class="lupa-busca" id="lupa-busca" onclick="exibirBusca()"><img width="36" height="36" alt="Pesquisar" src="<?php echo get_template_directory_uri(); ?>/assets/images/lupa.png" width="33" height="36"/></button>
                            <form role="search" method="get" class="search-form" id="search-form" action="<?php echo home_url( '/' ); ?>">
                                <div class="search-container" id="search-container">
                                    <input type="text" name="s" id="search" placeholder="DIGITE A SUA BUSCA AQUI" value=""/>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-2 col-hamb">
                        <!-- Hamburger icon -->
                        <div class="icon-mobile">
                            <input class="side-menu" type="checkbox" id="side-menu" name="c1" onclick="showMe('menu-mobile')"/>
                            <label class="hamb" for="side-menu"><span class="hamb-line"></span></label>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- Menu -->
        <div class="menu-principal menu-mobile container" id="menu-mobile">
                            <nav>
                                <ul calss="menu" id="menu-mobile">
                                    <li><a class="nav-link menu-home item-mob active" href="<?php echo home_url(); ?>">Home</a></li>
                                    <li><a href="#" class="nav-link menu-quem-somos item-mob" onclick="subQuemSomos()">Quem Somos</a>
                                        <ul class="submenu" id="sub-quem-somos" style="display:none;">
                                            <li><a a class="nav-link" href="<?php echo home_url(); ?>/quem-somos/o-mtst">O MTST</a></li>
                                            <li><a class="nav-link" href="<?php echo home_url(); ?>/quem-somos/fake-news">Fato ou fake</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#" class="nav-link menu-noticias item-mob" onclick="subNoticias()">Notícias</a>
                                        <ul class="submenu" id="sub-noticias" style="display:none">
                                            <li><a class="nav-link" href="<?php echo home_url(); ?>/noticias/ultimas-noticias">Últimas Notícias</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#" id="menu-campanha" class="nav-link menu-campanha item-mob" onclick="subCampanha()">Contribua</a>
                                        <ul class="submenu" id="sub-campanha" style="display:none">
                                            <li><a class="nav-link" href="<?php echo home_url(); ?>/contribuicao/">MTST</a></li>
                                            <li><a class="nav-link" href="https://apoia.se/cozinhasolidaria" >Cozinhas Solidárias</a></li>
                                            <li><a class="nav-link" href="https://www.catarse.me/colabore_mtst" >Educação Popular</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#" id="menu-projetos" class="nav-link menu-projetos item-mob" onclick="subProjetos()">Nossos Projetos</a>
                                        <ul class="submenu" id="sub-projetos" style="display:none">
                                            <li><a class="nav-link" href="<?php echo home_url(); ?>/cozinhas-solidarias/">Cozinhas Solidárias</a></li>
                                            <li><a class="nav-link" href="<?php echo home_url(); ?>/centro-popular-de-pesquisa/">Centro Popular de Pesquisa</a></li>
                                            <li><a class="nav-link" href="<?php echo home_url(); ?>/contrate-quem-luta/">Contrate Quem Luta</a></li>
                                        </ul>
                                    </li>
                                    <li><a class="nav-link menu-loja item-mob" href="https://wa.me/5511914631714" >Loja</a></li>
                                    <li><a href="#" class="nav-link menu-galeria item-mob" onclick="subGaleria()">Galeria</a>
                                        <ul class="submenu" id="sub-galeria" style="display:none;">
                                            <li><a class="nav-link" href="https://open.spotify.com/show/5X2nnU5w9uptyMiApbPCWj" >Podcasts</a></li>
                                            <li><a class="nav-link" href="https://www.youtube.com/c/MTSTBrasil" >Vídeos</a></li>
                                            <li><a class="nav-link" href="https://www.instagram.com/mtstbrasil/" >Fotos</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <script>
                            function exibirBusca() {
                                const lupa = document.getElementById('lupa-busca');
                                lupa.style.display = 'none';
                                const searchform = document.getElementById('search-container');
                                searchform.style.display = 'block';
                            }
                            function showMe (box) {        
                                var chboxs = document.getElementsByName("c1");
                                var vis = "none";
                                for(var i=0;i<chboxs.length;i++) { 
                                    if(chboxs[i].checked){
                                    vis = "block";
                                        break;
                                    }
                                }
                                document.getElementById(box).style.display = vis;
                            }
                            function subQuemSomos() {
                            var x = document.getElementById("sub-quem-somos");
                                if (x.style.display === "none") {
                                    x.style.display = "block";
                                } else {
                                    x.style.display = "none";
                                }
                            }
                            function subNoticias() {
                            var x = document.getElementById("sub-noticias");
                            var y = document.getElementById("menu-campanha");
                                if (x.style.display === "none") {
                                    x.style.display = "block";
                                    //y.style.marginTop = "92px";
                                } else {
                                    x.style.display = "none";
                                    y.style.marginTop = "0";
                                }
                            }
                            function subCampanha() {
                            var x = document.getElementById("sub-campanha");
                                if (x.style.display === "none") {
                                    x.style.display = "block";
                                } else {
                                    x.style.display = "none";
                                }
                            }
                            function subProjetos() {
                            var x = document.getElementById("sub-projetos");
                                if (x.style.display === "none") {
                                    x.style.display = "block";
                                } else {
                                    x.style.display = "none";
                                }
                            }
                            function subGaleria() {
                            var x = document.getElementById("sub-galeria");
                                if (x.style.display === "none") {
                                    x.style.display = "block";
                                } else {
                                    x.style.display = "none";
                                }
                            }

                        </script>
                </div>
    </header>


