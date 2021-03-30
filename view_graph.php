<?php
    include "header.php";
    include "nav.php";
    include "config.php";
    ?>
    <input type="hidden" name="currencyval" id="currencyval" value="<?php echo $_REQUEST['currency']; ?>">
    <select name="currency_list" id="currency_list" class="form-control">
    <option value="" disabled selected>Select Currency</option>
    <?php
    $get_currency_sql="select * from crypto_currency";
    $get_currency_res=mysqli_query($conn,$get_currency_sql);
    while($currency= mysqli_fetch_assoc($get_currency_res))
    {
        echo "<option value='".$currency['symbol']."'>".$currency['name']."</option>";
    }
    ?>
    </select>
    <div class="panel">
        <div class="container" id="container">
            <div style='padding: 3em;text-align: center;'>Loading Gaprh View...</div>
        </div>
    </div>
    <div class="col-md-8"></div>
    <div class="col-md-4" style="padding:2em;">
        <button type="button" class="btn btn-default pull-right" id="send_mail" style="display:none;">Send Mail</button>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <form method="post" accept-charset="utf-8" name="form1" id="mail_form"  onSubmit="return false" autocomplete="off" style="display:none;">
                <texarea name="hidden_data" id='hidden_data' type="hidden"></textarea>
                <div class="form-group">
                <input type="email" name="email" id="email"  placeholder="enter email" required>
					</div>
                <br>
                <button type="submit" class="btn btn-default pull-right" id="send_mail_btn" >Send Mail</button>
        </form>
    </div>
    <div class="col-md-4"></div>
    <div id="division" style="display:none;">
    
    </div>
    <?php
    include "footer.php";
    ?>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/data.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.2.61/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta1/html2canvas.svg.js"></script>
    <script>
    EXPORT_WIDTH = 1000;
    function send_mail(){
        var c=document.getElementById("canvas_id");
            var d=c.toDataURL("image/png");
//var w=window.open('about:blank','image from canvas');
//w.document.write("<img src='"+d+"' alt='from canvas'/>");
            //document.getElementById('hidden_data').value = dataURL;
                var fd = new FormData(document.forms["form1"]);
                fd.append('base_img', d);
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'send_mail.php', true);
                xhr.upload.onprogress = function(e) {
                    if (e.lengthComputable) {
                        //var percentComplete = (e.loaded / e.total) * 100;
                        //console.log(percentComplete + '% uploaded');
                        alert('Attachment created  Mail Sending....');
			 $('#mail_form')[0].reset();
                        $("#send_mail").show();
                        $("#mail_form").hide();
                    }
                    else
                    {
                        alert('Mail Sending Failed');
                    }
                };

                xhr.onload = function() {

                };
                xhr.send(fd);
    }
    function save_chart(chart) {
            var render_width = EXPORT_WIDTH;
            var render_height = render_width * chart.chartHeight / chart.chartWidth

            // Get the cart's SVG code
            var svg = chart.getSVG({
                exporting: {
                    sourceWidth: chart.chartWidth,
                    sourceHeight: chart.chartHeight
                }
            });

            // Create a canvas
            var canvas = document.createElement('canvas');
            canvas.setAttribute("id","canvas_id");
            canvas.height = render_height;
            canvas.width = render_width;
            $("#division").append(canvas);
            var dataURL=canvas.toDataURL("image/png");
            document.getElementById('hidden_data').value = dataURL;
            // Create an image and draw the SVG onto the canvas
            var image = new Image;
            image.onload = function() {
                canvas.getContext('2d').drawImage(this, 0, 0, render_width, render_height);
            };
            image.src = 'data:image/svg+xml;base64,' + window.btoa(unescape(encodeURIComponent(svg)));
            console.log(image);
        }
    function getData(currency_val)
    {
        /* Code for Currency Graph*/
        $.ajax({
            type: "POST",
            url: "get_graph_data.php", //WebMethod to be called
            data: {currency : currency_val},
            /*contentType: "application/json; charset=utf-8",
            dataType: "json",*/
            async: false,   //execute script synchronously
            success: function (response) {
                $("#send_mail").show();
                response=JSON.parse(response);
                $("#division").html(response);
                if(response.statusCode != '200')
                {
                    $("#container").empty().html("<div style='padding: 3em;text-align: center;'>Sorry! No data For this Currency from the API</div>");
                }
                else
                {
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
                        text: 'Currency History for '+response.data.iso
                    },

                    _navigator: {
                        enabled: false
                    },

                    series: [{
                        name: 'Value',
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
                }
                save_chart($('#container').highcharts());
                $("#send_mail").click(function(){
                   $("#send_mail").hide();
                   $("#mail_form").show();
                    /*html2canvas(document.getElementById('container')).then(function(canvas) {
                        document.body.appendChild(canvas);
                        var canvas_val = canvas;
				        var dataURL = canvas_val.toDataURL("image/png");
                    });*/
                });  
                $('#mail_form').validate({
		        rules: {
					email: {
				            required:true,
                              email:true
				    }
		        },
		        highlight: function (input) {
		            $(input).parents('.form-line').addClass('error');
		        },
		        unhighlight: function (input) {
		            $(input).parents('.form-line').removeClass('error');
		        },
		        errorPlacement: function (error, element) {
		            $(element).parents('.form-group').append(error);
		        },
		        submitHandler: function(form) {
                    send_mail();
				}
			    	});


















            
            },
            failure: function (response) {
                var r = jQuery.parseJSON(response.responseText);
                alert("Message: " + r.Message);
            }
        });
        /* Code for Graph Ends*/
    }
    $(document).ready(function(){
        $("#container").empty().html("<div style='padding: 3em;text-align: center;'>Loading Gaprh View...</div>");
        var txt_currency_val=$("#currencyval").val();
        if(txt_currency_val =='')
        {
            getData('BTC');
        }
        else
        {
            getData(txt_currency_val);
        }
        $("#currency_list").change(function(){
            $("#container").html("<div style='padding: 3em;text-align: center;'>Loading Gaprh View...</div>");
            getData($("#currency_list").val());
        })
        
    });
    </script>
