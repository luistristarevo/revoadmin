<?php  include_once __DIR__.'/admin_components/admin_header.php'; ?>
<div class="container dashboard">
    <div class="row">
        <div class="col-sm-6">
            <div class="panel-shadow">
                <div id="graph" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
            </div>
        </div>
        <div class="col-sm-6 icons">
            <div class="panel-shadow icons-cont">
                <h4 class="no-margin">Manage</h4>
                <div class="row fadein">
                    <div class="col-sm-6">
                        <div class="col-xs-4">
                            <a href="<?php echo route('plist',['token'=>$token]);?>">
                            <div class="item">
                                <span class="fa fa-circle"></span>
                                <label>Verticals</label>
                            </div>
                            </a>
                        </div>
                        <div class="col-xs-4">
                            <a href="<?php echo route('glist',['token'=>$token]);?>">
                            <div class="item">
                                <span class="fa fa-circle-o"></span>
                                <label>Groups</label>
                            </div>
                            </a>
                        </div>
                        <div class="col-xs-4">
                            <a href="<?php echo route('mlist',['token'=>$token]);?>">
                                <div class="item">
                                    <span class="fa fa-dot-circle-o"></span>
                                    <label>Merchants</label>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="col-xs-4">
                            <a href="">
                                <div class="item">
                                    <span class="fa fa-money"></span>
                                    <label>Verticals</label>
                                </div>
                            </a>
                        </div>
                        <div class="col-xs-4">
                            <a href="">
                                <div class="item">
                                    <span class="fa fa-credit-card"></span>
                                    <label>Groups</label>
                                </div>
                            </a>
                        </div>
                        <div class="col-xs-4">
                            <a href="">
                                <div class="item">
                                    <span class="fa fa-user-secret"></span>
                                    <label>Merchants</label>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row fadein">
                    <div class="col-sm-6">
                        <div class="col-xs-4">
                            <a href="">
                                <div class="item">
                                    <span class="fa fa-circle"></span>
                                    <label>Verticals</label>
                                </div>
                            </a>
                        </div>
                        <div class="col-xs-4">
                            <a href="">
                                <div class="item">
                                    <span class="fa fa-circle-o"></span>
                                    <label>Groups</label>
                                </div>
                            </a>
                        </div>
                        <div class="col-xs-4">
                            <a href="">
                                <div class="item">
                                    <span class="fa fa-dot-circle-o"></span>
                                    <label>Merchants</label>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="col-xs-4">
                            <a href="">
                                <div class="item">
                                    <span class="fa fa-money"></span>
                                    <label>Verticals</label>
                                </div>
                            </a>
                        </div>
                        <div class="col-xs-4">
                            <a href="">
                                <div class="item">
                                    <span class="fa fa-credit-card"></span>
                                    <label>Groups</label>
                                </div>
                            </a>
                        </div>
                        <div class="col-xs-4">
                            <a href="">
                                <div class="item">
                                    <span class="fa fa-user-secret"></span>
                                    <label>Merchants</label>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row fadein">
                    <div class="col-sm-6">
                        <div class="col-xs-4">
                            <a href="">
                                <div class="item">
                                    <span class="fa fa-circle"></span>
                                    <label>Verticals</label>
                                </div>
                            </a>
                        </div>
                        <div class="col-xs-4">
                            <a href="">
                                <div class="item">
                                    <span class="fa fa-circle-o"></span>
                                    <label>Groups</label>
                                </div>
                            </a>
                        </div>
                        <div class="col-xs-4">
                            <a href="">
                                <div class="item">
                                    <span class="fa fa-dot-circle-o"></span>
                                    <label>Merchants</label>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="col-xs-4">
                            <a href="">
                                <div class="item">
                                    <span class="fa fa-money"></span>
                                    <label>Verticals</label>
                                </div>
                            </a>
                        </div>
                        <div class="col-xs-4">
                            <a href="">
                                <div class="item">
                                    <span class="fa fa-credit-card"></span>
                                    <label>Groups</label>
                                </div>
                            </a>
                        </div>
                        <div class="col-xs-4">
                            <a href="">
                                <div class="item">
                                    <span class="fa fa-user-secret"></span>
                                    <label>Merchants</label>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row statistics">
        <div class="col-sm-3">
            <div class="panel-shadow">
               <div class="row">
                   <div class="col-xs-5">
                        <span class="chart chart1" data-percent="86">
                        <span class="percent"></span>
                        </span>
                   </div>
                   <div class="col-xs-7">
                       <h4 class="no-margin">Statistics</h4>
                       <p>Statistic description</p>
                       <a href="">Click here</a>
                   </div>
               </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel-shadow">
                <div class="row">
                    <div class="col-xs-5">
                        <span class="chart chart2" data-percent="86">
                        <span class="percent"></span>
                        </span>
                    </div>
                    <div class="col-xs-7">
                        <h4 class="no-margin">Statistics</h4>
                        <p>Statistic description</p>
                        <a href="">Click here</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel-shadow">
                <div class="row">
                    <div class="col-xs-5">
                        <span class="chart chart3" data-percent="86">
                        <span class="percent"></span>
                        </span>
                    </div>
                    <div class="col-xs-7">
                        <h4 class="no-margin">Statistics</h4>
                        <p>Statistic description</p>
                        <a href="">Click here</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel-shadow">
                <div class="row">
                    <div class="col-xs-5">
                        <span class="chart chart4" data-percent="86">
                        <span class="percent"></span>
                        </span>
                    </div>
                    <div class="col-xs-7">
                        <h4 class="no-margin">Statistics</h4>
                        <p>Statistic description</p>
                        <a href="">Click here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <div class="footer-body">
            <div class="row">
                <div class="col-sm-3"><h3 class="no-margin">Quick Links</h3></div>
                <div class="col-sm-3">
                    <a href="">Ticket History</a>
                    <a href="">User Locked</a>
                    <a href="">Open Ticket</a>
                    <a href="">System Health</a>
                </div>
                <div class="col-sm-3">
                    <a href="">Ticket History</a>
                    <a href="">User Locked</a>
                    <a href="">Open Ticket</a>
                    <a href="">System Health</a>
                </div>
                <div class="col-sm-3">
                    <a href="">Ticket History</a>
                    <a href="">User Locked</a>
                    <a href="">Open Ticket</a>
                    <a href="">System Health</a>
                </div>
            </div>
        </div>
    </div>
</footer>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="<?php echo asset('../js/jquery.easypiechart.min.js'); ?>"></script>
<script>
    $(function(){
        $('#graph').highcharts({
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Snow depth at Vikjafjellet, Norway'
            },
            subtitle: {
                text: 'Irregular time data in Highcharts JS'
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: { // don't display the dummy year
                    month: '%e. %b',
                    year: '%b'
                },
                title: {
                    text: 'Date'
                }
            },
            yAxis: {
                title: {
                    text: 'Snow depth (m)'
                },
                min: 0
            },
            tooltip: {
                headerFormat: '<b>{series.name}</b><br>',
                pointFormat: '{point.x:%e. %b}: {point.y:.2f} m'
            },

            plotOptions: {
                spline: {
                    marker: {
                        enabled: true
                    }
                }
            },

            series: [{
                name: 'Winter 2012-2013',
                // Define the data points. All series have a dummy year
                // of 1970/71 in order to be compared on the same x axis. Note
                // that in JavaScript, months start at 0 for January, 1 for February etc.
                data: [
                    [Date.UTC(1970, 9, 21), 0],
                    [Date.UTC(1970, 10, 4), 0.28],
                    [Date.UTC(1970, 10, 9), 0.25],
                    [Date.UTC(1970, 10, 27), 0.2],
                    [Date.UTC(1970, 11, 2), 0.28],
                    [Date.UTC(1970, 11, 26), 0.28],
                    [Date.UTC(1970, 11, 29), 0.47],
                    [Date.UTC(1971, 0, 11), 0.79],
                    [Date.UTC(1971, 0, 26), 0.72],
                    [Date.UTC(1971, 1, 3), 1.02],
                    [Date.UTC(1971, 1, 11), 1.12],
                    [Date.UTC(1971, 1, 25), 1.2],
                    [Date.UTC(1971, 2, 11), 1.18],
                    [Date.UTC(1971, 3, 11), 1.19],
                    [Date.UTC(1971, 4, 1), 1.85],
                    [Date.UTC(1971, 4, 5), 2.22],
                    [Date.UTC(1971, 4, 19), 1.15],
                    [Date.UTC(1971, 5, 3), 0]
                ]
            }, {
                name: 'Winter 2013-2014',
                data: [
                    [Date.UTC(1970, 9, 29), 0],
                    [Date.UTC(1970, 10, 9), 0.4],
                    [Date.UTC(1970, 11, 1), 0.25],
                    [Date.UTC(1971, 0, 1), 1.66],
                    [Date.UTC(1971, 0, 10), 1.8],
                    [Date.UTC(1971, 1, 19), 1.76],
                    [Date.UTC(1971, 2, 25), 2.62],
                    [Date.UTC(1971, 3, 19), 2.41],
                    [Date.UTC(1971, 3, 30), 2.05],
                    [Date.UTC(1971, 4, 14), 1.7],
                    [Date.UTC(1971, 4, 24), 1.1],
                    [Date.UTC(1971, 5, 10), 0]
                ]
            }, {
                name: 'Winter 2014-2015',
                data: [
                    [Date.UTC(1970, 10, 25), 0],
                    [Date.UTC(1970, 11, 6), 0.25],
                    [Date.UTC(1970, 11, 20), 1.41],
                    [Date.UTC(1970, 11, 25), 1.64],
                    [Date.UTC(1971, 0, 4), 1.6],
                    [Date.UTC(1971, 0, 17), 2.55],
                    [Date.UTC(1971, 0, 24), 2.62],
                    [Date.UTC(1971, 1, 4), 2.5],
                    [Date.UTC(1971, 1, 14), 2.42],
                    [Date.UTC(1971, 2, 6), 2.74],
                    [Date.UTC(1971, 2, 14), 2.62],
                    [Date.UTC(1971, 2, 24), 2.6],
                    [Date.UTC(1971, 3, 2), 2.81],
                    [Date.UTC(1971, 3, 12), 2.63],
                    [Date.UTC(1971, 3, 28), 2.77],
                    [Date.UTC(1971, 4, 5), 2.68],
                    [Date.UTC(1971, 4, 10), 2.56],
                    [Date.UTC(1971, 4, 15), 2.39],
                    [Date.UTC(1971, 4, 20), 2.3],
                    [Date.UTC(1971, 5, 5), 2],
                    [Date.UTC(1971, 5, 10), 1.85],
                    [Date.UTC(1971, 5, 15), 1.49],
                    [Date.UTC(1971, 5, 23), 1.08]
                ]
            }]
        });

        $('.chart1').easyPieChart({
            easing: 'easeOutBounce',
            onStep: function(from, to, percent) {
                $(this.el).find('.percent').text(Math.round(percent));
            },
            barColor: '#337AB7',
            lineWidth: 6
        });

        $('.chart2').easyPieChart({
            easing: 'easeOutBounce',
            onStep: function(from, to, percent) {
                $(this.el).find('.percent').text(Math.round(percent));
            },
            barColor: '#3D89A1',
            lineWidth: 6
        });

        $('.chart3').easyPieChart({
            easing: 'easeOutBounce',
            onStep: function(from, to, percent) {
                $(this.el).find('.percent').text(Math.round(percent));
            },
            barColor: '#47998A',
            lineWidth: 6
        });

        $('.chart4').easyPieChart({
            easing: 'easeOutBounce',
            onStep: function(from, to, percent) {
                $(this.el).find('.percent').text(Math.round(percent));
            },
            barColor: '#5CB85D',
            lineWidth: 6
        });

        /*var chart = window.chart = $('.chart').data('easyPieChart');
        $('.js_update').on('click', function() {
            chart.update(Math.random()*200-100);
        });*/
    });
</script>
<?php  include_once __DIR__.'/admin_components/admin_footer.php'; ?>
