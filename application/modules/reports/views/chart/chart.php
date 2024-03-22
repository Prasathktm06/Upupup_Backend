<?php
$this->load->view('chart/fusioncharts');
?><!-- Page content -->
<div id="page-content">
    <!-- Dashboard Header -->
    <!-- For an image header add the class 'content-header-media' and an image as in the following example -->
    <div class="content-header content-header-media">
        <div class="header-section">
            <div class="row">
                <!-- Main Title (hidden on small devices for the statistics to fit) -->
                <div class="col-md-4 col-lg-6 hidden-xs hidden-sm">
                    <h1>Welcome <strong><?=$_SESSION['customer_name']?></strong></h1>
                </div>

            </div>
        </div>
        <!-- For best results use an image with a resolution of 2560x248 pixels (You can also use a blurred image with ratio 10:1 - eg: 1000x100 pixels - it will adjust and look great!) -->
        <img src="<?=base_url()?>assets/img/placeholders/headers/dashboard_header.jpg" alt="header image" class="animation-pulseSlow">
    </div>
    <!-- END Dashboard Header -->

    <!-- Mini Top Stats Row -->
    <div class="row text-center">
       <script type="text/javascript" src="http://static.fusioncharts.com/code/latest/fusioncharts.js"></script>

        <?php

            // Form the SQL query that returns the top 10 most populous countries
            //$strQuery = "SELECT cat_name, master_count FROM  sa_category ";
           /* $sql ="SELECT cat_name, master_count FROM  sa_category";
            $result = $this->db->query($sql);*/
                
            // Execute the query, or else return the error message.
           // $result = $dbhandle->query($strQuery) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

            // If the query returns a valid response, prepare the JSON string
            if ($sales_man) {
                // The `$arrData` array holds the chart attributes and data
                $arrData = array(
                    "chart" => array(
                      "caption" => "Sale",
                      "paletteColors" => "#0075c2",
                      "bgColor" => "#ffffff",
                      "borderAlpha"=> "20",
                      "canvasBorderAlpha"=> "0",
                      "usePlotGradientColor"=> "0",
                      "plotBorderAlpha"=> "10",
                      "showXAxisLine"=> "1",
                      "xAxisLineColor" => "#999999",
                      "showValues" => "0",
                      "divlineColor" => "#999999",
                      "divLineIsDashed" => "1",
                      "showAlternateHGridColor" => "0"
                    )
                );

                $arrData["data"] = array();

                // Push the data into the array
                /*if ($result->num_rows() > 0) 
                    {
                        foreach ($result->result() as $row) 
                            {
                                array_push($arrData["data"], array(
                                        "label" => $row->cat_name,
                                        "value" => $row->master_count
                                    )
                                );
                            }
                    }*/

                foreach ($sales_man as $key => $value) {
                    array_push($arrData["data"], array(
                                        "label" => $value['name'],
                                        "value" => $value['order_list_sum']->total,
                                    )
                                );
                }
               //echo "<pre>";print_r($arrData);exit();
                /*JSON Encode the data to retrieve the string containing the JSON representation of the data in the array. */

                $jsonEncodedData = json_encode($arrData);

                /*Create an object for the column chart using the FusionCharts PHP class constructor. Syntax for the constructor is ` FusionCharts("type of chart", "unique chart id", width of the chart, height of the chart, "div id to render the chart", "data format", "data source")`. Because we are using JSON data to render the chart, the data format will be `json`. The variable `$jsonEncodeData` holds all the JSON data for the chart, and will be passed as the value for the data source parameter of the constructor.*/

                $columnChart = new FusionCharts("column2D", "myFirstChart" , 600, 300, "chart-1", "json", $jsonEncodedData);

                // Render the chart
                $columnChart->render();

                // Close the database connection
                //$dbhandle->close();
            }

        ?>

        <div id="chart-1"><!-- Fusion Charts will render here--></div>
    </div>
    <!-- END Mini Top Stats Row -->

</div>
<!-- END Page Content -->

<!-- Load and execute javascript code used only in this page -->
<script src="<?=base_url()?>assets/js/pages/index.js"></script>
<script>$(function(){ Index.init(); });</script>
   