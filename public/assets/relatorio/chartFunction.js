

function baseDataChart(label, value, cores, chartType) {
    return {
        label: ' ' + ucFirst(label),
        data: value,
        backgroundColor: cores,
        borderColor: cores.map((c) => { return c.replace(/rgba\(\s+(\d{1,3}),\s+(\d{1,3}),\s+(\d{1,3}),\s+0.\d\s+\)/g, "rgba($1, $2, $3, 1)")}),
        borderWidth: 1,
        type: chartType[label] || "bar"
    };
}

function chartDataOrder(data, order) {
    //cria array com os valores
    let classificacao = [];
    $.each(data, function (i, d) {
        classificacao.push(d);
    });

    //ordena
    classificacao.sort(dynamicSort(order)).reverse();

    //adiciona posição
    $.each(classificacao, function (i, c) {
        classificacao[i].position = i + 1;
    });

    return classificacao;
}

$(function ($) {
    $.fn.chart = function (titulo, data, x, y, chartType, mensagemEmpty, cores, template) {
        let $this = this;
        let $container = $("<div class='col'></div>").appendTo($this);
        $container.html("<img src='" + HOME + "assetsPublic/img/save.gif' />");
        mensagemEmpty = mensagemEmpty || "Opss! Ainda não temos dados registrados";

        if(typeof y === "string")
            y = [y];

        if(typeof y !== "undefined" && y.constructor === Array && y.length) {
            if(typeof chartType !== "object") {
                let c = (typeof chartType === "string" ? chartType : "bar");
                chartType = {};
                $.each(y, function (i, e) {
                    chartType[e] = c;
                });
            }

            data = chartDataOrder(data, y[0]);
            template = template || "chart_table";
            cores = cores || [
                'rgba(75, 192, 192, 0.4)',
                'rgba(54, 162, 235, 0.4)',
                'rgba(255, 99, 132, 0.4)',
                'rgba(255, 206, 86, 0.4)',
                'rgba(153, 102, 255, 0.4)',
                'rgba(255, 159, 64, 0.4)'
            ];

            if (!isEmpty(titulo) && !isEmpty(data)) {
                dbLocal.exeRead("__template", 1).then(tpl => {
                    let chartData = {
                        data: [],
                        haveClassificacao: data.length > 0,
                        id: Date.now() + Math.floor(Math.random() * 1000),
                        titulo: titulo,
                        mensagem: mensagemEmpty,
                        x: x,
                        y: y
                    };

                    let chartValues = {labels: [], datasets: []};
                    let dataset = {};
                    $.each(data, function (i, d) {
                        let dados = {position: d.position, x: d[x], y: []};
                        chartValues.labels.push(d[x]);

                        $.each(y, function (o, pp) {
                            dados.y.push(d[pp]);

                            if (typeof dataset[pp] === "undefined")
                                dataset[pp] = [];

                            dataset[pp].push(d[pp]);
                        });
                        chartData.data.push(dados);
                    });

                    $.each(dataset, function (label, values) {
                        chartValues.datasets.push(baseDataChart(label, values, cores, chartType));
                    });

                    $container.html(Mustache.render(tpl[template], chartData));

                    if (chartData.haveClassificacao) {
                        let dados = {
                            type: chartType[y[0]],
                            data: chartValues,
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

                        new Chart(document.getElementById("chart_" + chartData.id).getContext('2d'), dados);
                    }
                });
            }
        } else {
            toast("campo Y ausente na chamada do Chart.js");
        }

        return $this;
    }
}, jQuery);