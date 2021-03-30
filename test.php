    <?php
    include "header.php";
    include "nav.php";
    ?>
    <div class="container" id="container">
    </div>
    <div id="division"></div>
    <?php
    include "footer.php";
    ?>
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/data.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
    <script>
        $.ajax({
            type: "POST",
            url: "get_graph_data.php", //WebMethod to be called
            data: {currency :'BTC'},
            /*contentType: "application/json; charset=utf-8",
            dataType: "json",*/
            async: false,   //execute script synchronously
            success: function (response) {
                response=JSON.parse(response);
                $("#division").html(response);
                Highcharts.stockChart('container', {
                    chart: {
                        height: 300
                    },

                    rangeSelector: {
                        allButtonsEnabled: true,
                        buttons: [{
                            type: 'month',
                            count: 3,
                            text: 'Day',
                            dataGrouping: {
                                forced: true,
                                units: [['day', [1]]]
                            }
                        }, {
                            type: 'year',
                            count: 1,
                            text: 'Week',
                            dataGrouping: {
                                forced: true,
                                units: [['week', [1]]]
                            }
                        }, {
                            type: 'all',
                            text: 'Month',
                            dataGrouping: {
                                forced: true,
                                units: [['month', [1]]]
                            }
                        },
                        {
                            type: 'all',
                            text: 'Year',
                            dataGrouping: {
                                forced: true,
                                units: [['year', [1]]]
                            }
                        },
                    ],
                        buttonTheme: {
                            width: 60
                        },
                        selected: 2
                    },

                    title: {
                        text: 'Crypto Currency'
                    },

                    subtitle: {
                        text: 'Currency History'
                    },

                    _navigator: {
                        enabled: false
                    },

                    series: [{
                        name: 'AAPL',
                        data: response.data.entries,
                        marker: {
                            enabled: null, // auto
                            radius: 3,
                            lineWidth: 1,
                            lineColor: '#FFFFFF'
                        },
                        tooltip: {
                            valueDecimals: 2
                        }
                    }]
                    });
            },
            failure: function (response) {
                var r = jQuery.parseJSON(response.responseText);
                alert("Message: " + r.Message);
            }
        });

    </script>