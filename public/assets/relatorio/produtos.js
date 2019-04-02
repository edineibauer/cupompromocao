$("#relatorio_produtos_geral").chart('report/produto/geral');

exeRead("lojas").then(l => {
    if (l.lenght) {
        $.each(l.data, function (i, loja) {
            let $loja = $("<div class='col'></div>").prependTo("#relatorio_produtos_loja");
            $loja.chart('report/produto/loja/' + loja.id);
        });
    }
});