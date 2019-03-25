function readBestSellers(vendas, funcionarios, lancamentos, lojas, tpl) {
    if (typeof vendas === "undefined" || !isNaN(vendas) || typeof tpl === "undefined") {

        //leitura dos dados
        let vendas = dbLocal.exeRead("vendas");
        let funcionarios = dbLocal.exeRead("funcionarios");
        let lojas = dbLocal.exeRead("lojas");
        let lancamentos = dbLocal.exeRead("lancamentos");
        let tpl = dbLocal.exeRead("__template", 1);

        Promise.all([vendas, funcionarios, lancamentos, lojas, tpl]).then(r => {
            let funcionario = {};
            $.each(r[1], function (i, l) {
                funcionario[l.id] = l;
            });
            let lancamento = {};
            $.each(r[2], function (i, l) {
                lancamento[l.id] = l;
            });
            let loja = {};
            $.each(r[3], function (i, l) {
                loja[l.id] = l;
            });

            readBestSellers(r[0], funcionario, lancamento, loja, r[4].chart_table);
        });
    } else {

        //preparação dos dados
        let dataLoja = {};
        $.each(vendas, function (i, v) {
            if (typeof dataLoja[funcionarios[v.funcionario].loja] === "undefined") {
                dataLoja[funcionarios[v.funcionario].loja] = {
                    titulo: lojas[funcionarios[v.funcionario].loja].razao_social,
                    table: {}
                };
            }

            if (typeof dataLoja[funcionarios[v.funcionario].loja].table[v.funcionario] === "undefined") {
                dataLoja[funcionarios[v.funcionario].loja].table[v.funcionario] = {
                    position: 0,
                    nome: funcionarios[v.funcionario].nome,
                    vendas: 0,
                    pontos: 0
                };
            }

            dataLoja[funcionarios[v.funcionario].loja].table[v.funcionario].pontos += parseInt(v.pontos);

            let vendas = 0;
            $.each(lancamentos[v.lancamento].produtos, function (i, p) {
                vendas += parseInt(p.quantidade);
            });
            dataLoja[funcionarios[v.funcionario].loja].table[v.funcionario].vendas += vendas;
        });

        let t = 0;
        $.each(dataLoja, function (i, data) {
            $("#relatorio_atendentes").chart("Loja " + data.titulo, data.table, "nome", ["pontos", "vendas"], {"pontos": "bar", "vendas": "line"});
        });
    }
}

readBestSellers();