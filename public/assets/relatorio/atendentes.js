exeRead("lojas").then(l => {
   if(l.lenght) {
       $.each(l.data, function (i, loja) {
           let $loja = $("<div class='col'></div>").prependTo("#relatorio_atendentes");
           $loja.chart("report/funcionario/vendas/" + loja.id);
       });
   }
});