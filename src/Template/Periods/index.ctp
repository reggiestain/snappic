<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Helper;
use Cake\Routing\Router;
?>
<!-- content -->
<div class="col-md-12">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header bootstrap-admin-content-title">
            </div>
        </div>
    </div>
    <div class="alert-msg">
    <?php 
    echo $this->Flash->render();
    echo $this->Flash->render('auth');
    ?>
    </div>    
    <div class="row">        
        <div class="col-lg-6">
            <div class="alert-message"></div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="text-muted bootstrap-admin-box-title"><strong>Add Period</strong> </div>
                </div>
                <?php echo $this->Form->create($period,['url' => ['action' => 'add'],'id'=>'add']);?>
                <div class="panel-body">
                    <div class="error-msg"></div>
                    <div class="form-group">
                        <label>Start Time</label>
                        <div class="input-group date">                                             
                      <?php echo $this->Form->input('start_time',['type' => 'text','label' => false,'class'=>'form-control','id'=>'start-time','required'=>false, 'error' => true]);?>
                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span> 
                        </div>
                    </div>

                    <div class="form-group">
                        <label>End Time</label>    
                        <div class="input-group">                       
                       <?php echo $this->Form->input('end_time',['type' => 'text','label' => false,'class'=>'form-control','id'=>'end-time','required'=>false,'error' => true]);?>
                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                        </div>  
                    </div>    
                </div>
                <div class="panel-footer">
                    <button type="submit" id="save" class="btn btn-success">Submit</button>
                </div>
                <?php echo $this->Form->end();?>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="alert-message"></div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="text-muted bootstrap-admin-box-title"><strong>Period Graph</strong> </div>
                </div>
                <div class="panel-body">
                    <div id="container" style="width:100%; height:210px;">

                    </div>
                </div
                <div class="panel-footer">

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        //Datetimepicker
        $('#start-time').datetimepicker({format: 'HH:mm:ss'});
        $('#end-time').datetimepicker({format: 'HH:mm:ss'});
        
        //Load highchart
        $.ajax({
            url: "<?php echo Router::url('/periods/data');?>",
            type: "GET",
            async: true,
            dataType: 'json',
            cache: false,
            beforeSend: function () {
                    $("#container").html("<button class='btn btn-default btn-lg'><i class='fa fa-spinner fa-spin'></i> Loading</button>");
                },
            success: function (results) {
                Highcharts.setOptions({
                    global: {
                         timezone: 'Africa/Harare'
                    }
                });
                var myChart = Highcharts.chart('container', {
                    chart: {
                        renderTo: 'Some NAME',
                        type: 'columnrange',
                        inverted: true
                    },
                    title: {
                        text: results.hrs
                    },
                    
                    yAxis: {
                        type: 'datetime',
                        title: {
                            text: 'time'
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    tooltip: {
                        shared: true,
                        valueSuffix: '',
                        formatter: function () {
                            return '</b> From:<b>' + Highcharts.dateFormat('%H:%M', this.points[0].point.low) + '</b> To:<b>' + Highcharts.dateFormat('%H:%M', this.points[0].point.high);
                        }
                    },
                    series: [{
                            name: 'Testtime',
                            data: results.data
                        }]
                });

            },
            error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                    //location.reload();
                }
        });
        
        //Submit form data
        $(document).on('submit', '#add', function (event) {
            event.preventDefault();
            var formData = $(this).serialize();
            var url = $(this).attr("action");
            $.ajax({
                url: url,
                type: "POST",
                asyn: false,
                data: formData,
                beforeSend: function () {
                    $("#save").html("Saving.....");
                },
                success: function (data, textStatus, jqXHR)
                {
                    if (data === '200') {
                        $(".alert-msg").html("<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Success! </strong>Person Info was successfully created.</div>");
                        $("#save").html("Submit");
                        location.reload();
                    } else {
                        $(".error-msg").html(data);
                        $("#save").html("Submit");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                    location.reload();
                }
            });
        });
    });
</script>    

