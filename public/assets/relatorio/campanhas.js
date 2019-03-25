function readClassificacao(campanha, vendas, funcionarios, tpl) {
    if (typeof campanha === "undefined" || !isNaN(campanha)) {
        dbLocal.exeRead("campanhas").then(campanhas => {
            let vendas = dbLocal.exeRead("vendas");
            let funcionarios = dbLocal.exeRead("funcionarios");
            let tpl = dbLocal.exeRead("__template", 1);

            Promise.all([vendas, funcionarios, tpl]).then(r => {

                let fun = {};
                $.each(r[2], function (i, f) {
                    fun[f.id] = f;
                });

                $.each(campanhas, function (i, e) {
                    readClassificacao(e, r[1], fun, r[3]);
                });
            });
        });
    } else {
        let $container = $("<div class='col padding-medium card' id='chart-container-" + campanha.id + "'></div>").appendTo("#relatorio_classificacao");
        $container.html("<img src='" + HOME + "assetsPublic/img/save.gif' />");

        let readAll = [];
        if (typeof lancamentos === "undefined") {
            readAll.push(dbLocal.exeRead("vendas"));
            readAll.push(dbLocal.exeRead("funcionarios"));
            readAll.push(dbLocal.exeRead("__template", 1));
        }

        Promise.all(readAll).then(r => {
            if (r.length) {
                vendas = r[0] || [];
                tpl = r[2] || [];

                funcionarios = {};
                $.each(r[1], function (i, f) {
                    funcionarios[f.id] = f;
                });
            }

            let d = campanha.termino_da_vigencia.split("-");
            campanha.termino_da_vigencia = d[2] + '/' + d[1] + "/" + d[0];

            d = campanha.inicio_da_vigencia.split("-");
            campanha.inicio_da_vigencia = d[2] + '/' + d[1] + "/" + d[0];

            let classificacaoCampanha = {};
            $.each(vendas, function (i, v) {
                if (v.campanha == campanha.id) {
                    if (typeof classificacaoCampanha[v.funcionario] === "undefined")
                        classificacaoCampanha[v.funcionario] = {pontos: 0, nome: funcionarios[v.funcionario].nome};

                    classificacaoCampanha[v.funcionario].pontos += v.pontos;
                }
            });


            //Classificação

            campanha.classificacao = [];
            $.each(classificacaoCampanha, function (i, d) {
                campanha.classificacao.push(d);
            });
            campanha.classificacao.sort(dynamicSort("pontos")).reverse();
            $.each(campanha.classificacao, function (i, c) {
                campanha.classificacao[i].position = i + 1;
            });
            campanha.haveClassificacao = campanha.classificacao.length > 0;

            $container.html(Mustache.render(tpl.campanhas, campanha));

            if (campanha.haveClassificacao) {
                let dados = {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: ' Pontos',
                            data: [],
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(75, 192, 192, 0.1)',
                                'rgba(75, 192, 192, 0.05)'
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(75, 192, 192, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                };

                $.each(campanha.classificacao, function (i, d) {
                    dados.data.labels.push(d.nome);
                    dados.data.datasets[0].data.push(d.pontos);
                });

                new Chart(document.getElementById('chart-' + campanha.id).getContext('2d'), dados);
            }
        })
    }
}

readClassificacao();