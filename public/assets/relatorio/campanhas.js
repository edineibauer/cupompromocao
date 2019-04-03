exeRead("campanhas").then(c => {
    if (c.lenght) {
        $.each(c.data, function (i, campanha) {
            let $campanha = $("<div class='col'></div>").prependTo("#relatorio_classificacao");
            $campanha.chart('report/campanha/premiacao/' + campanha.id);
        });
    }
});