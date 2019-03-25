function readClassificacao(vendas, lojas, funcionarios) {
    if (typeof vendas === "undefined" || !isNaN(vendas)) {
        let vendas = dbLocal.exeRead("vendas");
        let funcionarios = dbLocal.exeRead("funcionarios");
        let lojas = dbLocal.exeRead("lojas");

        Promise.all([vendas, lojas, funcionarios]).then(r => {
            let loja = {};
            $.each(r[1], function (i, l) {
                loja[l.id] = l;
            });
            let funcionario = {};
            $.each(r[2], function (i, l) {
                funcionario[l.id] = l;
            });

            readClassificacao(r[0], loja, funcionario);
        });

    } else {
        let classificacaoLoja = {};
        $.each(vendas, function (i, v) {
            if (typeof classificacaoLoja[funcionarios[v.funcionario].loja] === "undefined")
                classificacaoLoja[funcionarios[v.funcionario].loja] = {
                    pontos: 0,
                    nome: lojas[funcionarios[v.funcionario].loja].razao_social
                };

            classificacaoLoja[funcionarios[v.funcionario].loja].pontos += v.pontos;
        });

        $("#relatorio_lojas").chart("Lojas que mais Vendem", classificacaoLoja, "nome", "pontos", "doughnut");
    }
}

readClassificacao();