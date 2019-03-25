function readClassificacao(vendas, lancamentos, produtos, funcionarios, lojas) {
    if (typeof vendas === "undefined" || !isNaN(vendas)) {
        let vendas = dbLocal.exeRead("vendas");
        let lancamentos = dbLocal.exeRead("lancamentos");
        let produtos = dbLocal.exeRead("produtos");
        let funcionarios = dbLocal.exeRead("funcionarios");
        let lojas = dbLocal.exeRead("lojas");

        Promise.all([vendas, lancamentos, produtos, funcionarios, lojas]).then(r => {
            let lancamento = {};
            $.each(r[1], function (i, l) {
                lancamento[l.id] = l;
            });
            let produto = {};
            $.each(r[2], function (i, l) {
                produto[l.id] = l;
            });
            let funcionario = {};
            $.each(r[3], function (i, l) {
                funcionario[l.id] = l;
            });
            let loja = {};
            $.each(r[4], function (i, l) {
                loja[l.id] = l;
            });

            readClassificacao(r[0], lancamento, produto, funcionario, loja);
        });

    } else {
        let classificacaoProduto = {};
        let classificacaoLoja = {};
        $.each(vendas, function (i, v) {
            $.each(lancamentos[v.lancamento].produtos, function(e, p) {
                if (typeof classificacaoProduto[produtos[p.produto].nome] === "undefined")
                    classificacaoProduto[produtos[p.produto].nome] = {vendas: 0, nome: produtos[p.produto].nome};

                classificacaoProduto[produtos[p.produto].nome].vendas += parseInt(p.quantidade);


                if (typeof classificacaoLoja[funcionarios[v.funcionario].loja] === "undefined")
                    classificacaoLoja[funcionarios[v.funcionario].loja] = {produtos: {}, nome: lojas[funcionarios[v.funcionario].loja].razao_social};

                if (typeof classificacaoLoja[funcionarios[v.funcionario].loja].produtos[p.produto] === "undefined")
                    classificacaoLoja[funcionarios[v.funcionario].loja].produtos[p.produto] = {vendas: 0, nome: produtos[p.produto].nome};

                classificacaoLoja[funcionarios[v.funcionario].loja].produtos[p.produto].vendas += parseInt(p.quantidade);
            });
        });

        $("#relatorio_produtos").chart("Produtos Mais Vendidos", classificacaoProduto, "nome", "vendas", "pie");

        $.each(classificacaoLoja, function(idLoja, dados) {
            console.log(dados);
            $("#relatorio_produtos").chart("Loja " + dados.nome, dados.produtos, "nome", "vendas", "pie");
        });
    }
}

readClassificacao();