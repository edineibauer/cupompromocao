<div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small" data-action="page"
     data-entity="" data-atributo="panel" data-lib="dashboard">
    <i class="material-icons left">timeline</i>
    <span class="left padding-tiny padding-left">Resumo</span>
</div>
<div class="menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small" onclick="openMenu('campanha')">
    <i class="material-icons left">outlined_flag</i>
    <span class="left padding-tiny padding-left">Campanhas</span>
    <i class="material-icons left transition-easy arrow-campanha">keyboard_arrow_down</i>
</div>
<div class="col hide padding-left menu-campanha theme-d1 padding-bottom" style="width: 450px;overflow: hidden">
    <div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small" data-action="table"
         data-entity="campanhas">
        <i class="material-icons left">list</i>
        <span class="left padding-tiny padding-left">Todas</span>
    </div>
    <div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small" data-action="table"
         data-entity="premios">
        <i class="material-icons left">card_giftcard</i>
        <span class="left padding-tiny padding-left">Premios</span>
    </div>
    <div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small" data-action="table"
         data-entity="campanhas_lojas">
        <i class="material-icons left">flag</i>
        <span class="left padding-tiny padding-left">Participantes</span>
    </div>
    <div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small" data-action="table"
         data-entity="cesta">
        <i class="material-icons left">shopping_basket</i>
        <span class="left padding-tiny padding-left">Cesta</span>
    </div>
    <div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small" data-action="table"
         data-entity="produtos">
        <i class="material-icons left">shopping_cart</i>
        <span class="left padding-tiny padding-left">Produtos</span>
    </div>
</div>

<div class="menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small" onclick="openMenu('lancamento')">
    <i class="material-icons left">send</i>
    <span class="left padding-tiny padding-left">Lancamentos</span>
    <i class="material-icons left transition-easy arrow-lancamento">keyboard_arrow_down</i>
</div>
<div class="col hide padding-left menu-lancamento theme-d1 padding-bottom" style="width: 450px;overflow: hidden">
    <div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small" data-action="table"
         data-entity="lancamentos">
        <i class="material-icons left">list</i>
        <span class="left padding-tiny padding-left">Todos</span>
    </div>
    <div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small" data-action="table"
         data-entity="pendencias">
        <i class="material-icons left">assignment_late</i>
        <span class="left padding-tiny padding-left">Pendentes</span>
    </div>
    <div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small" data-action="table"
         data-entity="cancelamentos">
        <i class="material-icons left">delete</i>
        <span class="left padding-tiny padding-left">Cancelados</span>
    </div>
    <div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small" data-action="table"
         data-entity="vendas">
        <i class="material-icons left">credit_card</i>
        <span class="left padding-tiny padding-left">Aprovados</span>
    </div>
</div>

<div class="menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small" onclick="openMenu('relatorio')">
    <i class="material-icons left">trending_up</i>
    <span class="left padding-tiny padding-left">Relatórios</span>
    <i class="material-icons left transition-easy arrow-relatorio">keyboard_arrow_down</i>
</div>
<div class="col hide padding-left menu-relatorio theme-d1 padding-bottom" style="width: 450px;overflow: hidden">
    <div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small"
         data-action="page" data-lib="cupompromocao" data-atributo="relatorio/campanhas">
        <i class="material-icons left">flag</i>
        <span class="left padding-tiny padding-left">Campanhas</span>
    </div>
    <div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small"
         data-action="page" data-lib="cupompromocao" data-atributo="relatorio/classificacao">
        <i class="material-icons left">format_list_numbered</i>
        <span class="left padding-tiny padding-left">Classificações</span>
    </div>
    <div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small"
         data-action="page" data-lib="cupompromocao" data-atributo="relatorio/lojas">
        <i class="material-icons left">store</i>
        <span class="left padding-tiny padding-left">Lojas/Farmácias</span>
    </div>
    <div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small"
         data-action="page" data-lib="cupompromocao" data-atributo="relatorio/produtos">
        <i class="material-icons left">shopping_cart</i>
        <span class="left padding-tiny padding-left">Produtos Vendidos</span>
    </div>
</div>

<div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small" data-action="table"
     data-entity="lojas">
    <i class="material-icons left">store</i>
    <span class="left padding-tiny padding-left">Lojas/Farmácias</span>
</div>
<div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small" data-action="table"
     data-entity="funcionarios">
    <i class="material-icons left">supervised_user_circle</i>
    <span class="left padding-tiny padding-left">Vendedores</span>
</div>

<?php

if ($_SESSION['userlogin']['setor'] === "admin") {
    ?>
    <div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small" data-action="table"
         data-entity="gestor">
        <i class="material-icons left">perm_identity</i>
        <span class="left padding-tiny padding-left">Gestores</span>
    </div>
    <?php

    if ($_SESSION['userlogin']['id'] === "1") {
        ?>
        <div class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small"
             data-action="table"
             data-entity="usuarios">
            <i class="material-icons left">person</i>
            <span class="left padding-tiny padding-left">Usuarios</span>
        </div>
        <a href="UIDev" class="menu-li menu-dashboard-lista theme-hover bar-item button z-depth-0 padding-small"
           data-action="link">
            <i class="material-icons left">settings_ethernet</i>
            <span class="left padding-tiny padding-left">DEV</span>
        </a>
        <?php
    }
}
?>
<script>
    function openMenu(menu) {
        let $menu = $(".menu-" + menu);
        if($menu.hasClass("hide")) {
            $(".arrow-" + menu).css("transform", "rotateZ(180deg)");
            let h = $menu.removeClass("hide").css("height");
            $menu.addClass("transition-easy").css("height", 0);
            $menu.css("height", h);
        } else {
            $(".arrow-" + menu).css("transform", "rotateZ(0)");
            $menu.css("height", 0);
            setTimeout(function () {
                $menu.addClass("hide").removeClass("transition-easy").css("height", "auto");
            },250);
        }
    }
</script>
