import Global from "../global.js"


/**
 * Método responsável por realizar uma busca na api
 * e com as informações retornadas criar um grafico de
 * exibição na view.
 */
function graficoLucro()
{
    // Url e Token
    var url = Global.config.urlApi + "grafico/lucro";
    var token = Global.session.get("token").token;

    Global.enviaApi("GET", url, null, token)
        .then((data) => {

            var $chart = $('#chart-sales-dark');

            var salesChart = new Chart($chart, {
                type: 'line',
                options: {
                    scales: {
                        yAxes: [{
                            gridLines: {
                                lineWidth: 1,
                                color: Charts.colors.gray[900],
                                zeroLineColor: Charts.colors.gray[900]
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(item, data) {
                                var label = data.datasets[item.datasetIndex].label || '';
                                var yLabel = item.yLabel;
                                var content = '';

                                if (data.datasets.length > 1) {
                                    content += '<span class="popover-body-label mr-auto">' + label + '</span>';
                                }

                                content += 'R$' + yLabel;
                                return content;
                            }
                        }
                    }
                },
                data: {
                    labels: data.objeto.meses,
                    datasets: [{
                        label: 'Performance',
                        data: data.objeto.lucros
                    }]
                }
            });

            // Save to jQuery object

            $chart.data('chart', salesChart);

        })
} // graficoLucro()


function graficoContratos()
{
    // Url e Token
    var url = Global.config.urlApi + "grafico/contratos";
    var token = Global.session.get("token").token;

    Global.enviaApi("GET", url, null, token)
        .then((data) => {

            var $chart = $('#chart-bars');

            // Create chart
            var ordersChart = new Chart($chart, {
                type: 'bar',
                data: {
                    labels: data.objeto.meses,
                    datasets: [{
                        label: 'Contratos',
                        data: data.objeto.contratos
                    }]
                }
            });

            // Save to jQuery object
            $chart.data('chart', ordersChart);

        })

} // End >> fun::graficoContratos()


// Retorno para os demais arquivos
export default (() => {

    return {
        graficoLucro: graficoLucro(),
        graficoContratos: graficoContratos(),
    };

})();