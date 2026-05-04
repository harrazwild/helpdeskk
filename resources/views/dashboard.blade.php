@extends('layout.mainlayout')

@section('content')
<style>
a:link 
{
    text-decoration: none;
}

/* Dashboard Hover Animations */
.short-states {
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
    cursor: pointer !important;
    position: relative !important;
    overflow: hidden !important;
}

.short-states::before {
    content: '' !important;
    position: absolute !important;
    top: 0 !important;
    left: -100% !important;
    width: 100% !important;
    height: 100% !important;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent) !important;
    transition: left 0.6s ease !important;
}

.short-states:hover::before {
    left: 100% !important;
}

.short-states:hover {
    transform: translateY(-8px) scale(1.02) !important;
    box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
}

.short-states .state-icon {
    transition: all 0.3s ease !important;
}

.short-states:hover .state-icon {
    transform: rotate(15deg) scale(1.1) !important;
}

.short-states .panel-body h1 {
    transition: all 0.3s ease !important;
}

.short-states:hover .panel-body h1 {
    transform: scale(1.1) !important;
}

.bg-default:hover {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
    border-color: #6c757d !important;
}

.bg-danger:hover {
    background: linear-gradient(135deg, #ff6b6b, #ff5252) !important;
    border-color: #ff5252 !important;
}

.bg-warning:hover {
    background: linear-gradient(135deg, #ffc107, #ffb300) !important;
    border-color: #ffb300 !important;
}

.bg-info:hover {
    background: linear-gradient(135deg, #17a2b8, #138496) !important;
    border-color: #138496 !important;
}

.bg-primary:hover {
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
    border-color: #0056b3 !important;
}

.bg-success:hover {
    background: linear-gradient(135deg, #28a745, #1e7e34) !important;
    border-color: #1e7e34 !important;
}

/* Chart Container Animations */
.panel {
    transition: all 0.3s ease !important;
}

.panel:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

/* Form Controls */
.form-control {
    transition: all 0.3s ease !important;
}

.form-control:focus {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(0,123,255,0.15) !important;
}

/* Panel Heading Animations */
.panel-heading {
    transition: all 0.3s ease !important;
}

.panel:hover .panel-heading {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
}

/* Icon Animations */
.fa {
    transition: all 0.3s ease !important;
}

.panel:hover .fa {
    transform: scale(1.1) !important;
}

/* Smooth transitions for all interactive elements */
* {
    transition-timing-function: cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
    
    var year = $('#tahun').val();
    var url = '{{ route("getDataWidget") }}';

    $.ajax({
        url:url,
        method:'POST',
        data:{
                "_token": '{{ csrf_token() }}',
                "year": year,
            },
        success:function(response){
            console.log(response);
            $('#baru').html(response[0].new);    
            $('#dt').html(response[0].dt);    
            $('#selesai').html(response[0].done);    
            $('#verify').html(response[0].verify);    
            $('#close').html(response[0].close);    
            $('#jumlah').html(response[0].total);

            var dom = document.getElementById("basic-Pie");
            var bpChart = echarts.init(dom);

            var app = {};
            option = null;
            option = {
                color: ['#62549a','#4aa9e9', '#ff6c60','#eac459', '#25c3b2' ],
                tooltip : {
                    trigger: 'item',
                    formatter: '{b} : {c} ({d}%)'
                },
                legend: {
                    orient : 'vertical',
                    x : 'left',
                    data:['PEJABAT KETUA AUDIT NEGARA','SEKTOR PENGURUSAN','SEKTOR AUDIT KEWANGAN','SEKTOR AUDIT PRESTASI','SEKTOR AUDIT TADBIR URUS']
                },
                calculable : true,
                series : [
                    {
                        name:'Source',
                        type:'pie',
                        radius : '55%',
                        center: ['50%', '60%'],
                        itemStyle : {
                            normal : {
                                label : {
                                    show: true, 
                                    position: 'inner',
                                    formatter : '{c}',
                                }
                            }
                        },
                        labelLine: {
                              show: false
                            },
                        data:[
                            {value:response[3].pkan, name:'PEJABAT KETUA AUDIT NEGARA'},
                            {value:response[3].sp, name:'SEKTOR PENGURUSAN'},
                            {value:response[3].sak, name:'SEKTOR AUDIT KEWANGAN'},
                            {value:response[3].sap, name:'SEKTOR AUDIT PRESTASI'},
                            {value:response[3].satu, name:'SEKTOR AUDIT TADBIR URUS'}
                        ],
                        labelLine: {
                              show: false
                            },
                            label:{
                                show: false
                            }
                    }
                ]
            };

            if (option && typeof option === "object") {
                bpChart.setOption(option, false);
            }

            var arr = [response[1].jan, response[1].feb, response[1].mac, response[1].apr, response[1].mei, response[1].jun, response[1].jul, response[1].ogos, response[1].sept, response[1].okt, response[1].nov, response[1].dis];
            arr = arr.map(Number); // convert string to integer

            var arrdone = [response[2].jan, response[2].feb, response[2].mac, response[2].apr, response[2].mei, response[2].jun, response[2].jul, response[2].ogos, response[2].sept, response[2].okt, response[2].nov, response[2].dis];
            arrdone = arrdone.map(Number); // convert string to integer

            Highcharts.chart('basic-line', {
                title: {
                    text: 'Taburan Aduan',
                    x: -20 //center
                },
                xAxis: {
                    title: {
                        text: 'Bulan'
                    },
                    categories: ['Jan', 'Feb', 'Mac', 'Apr', 'Mei', 'Jun',
                        'Jul', 'Ogos', 'Sept', 'Okt', 'Nov', 'Dis']
                },
                yAxis: {
                    title: {
                        text: 'Bilangan'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                },
                colors: [
                '#ff6c60', 
                '#90ed7d'
                ],
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                    },
                    column: {
                        colorByPoint: true
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },
                series: [{
                    name: 'Aduan Diterima',
                    data: [arr[0], arr[1], arr[2], arr[3], arr[4], arr[5], arr[6], arr[7], arr[8], arr[9], arr[10], arr[11]]
                }, {
                    name: 'Aduan Selesai',
                    data: [arrdone[0], arrdone[1], arrdone[2], arrdone[3], arrdone[4], arrdone[5], arrdone[6], arrdone[7], arrdone[8], arrdone[9], arrdone[10], arrdone[11]]
                }]
            });

            var mt = [response[4].jan, response[4].feb, response[4].mac, response[4].apr, response[4].mei, response[4].jun, response[4].jul, response[4].ogos, response[4].sept, response[4].okt, response[4].nov, response[4].dis];
            mt = mt.map(Number); // convert string to integer
            var dom = document.getElementById("rainfall");
            var rainChart = echarts.init(dom);

            var app = {};
            option = null;
            option = {
                color: ['#4aa9e9'],
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    data:['Sale']
                },
                calculable : true,
                xAxis : [
                    {
                        type : 'category',
                        data : ['Jan','Feb','Mac','Apr','Mei','Jun','Jul','Ogos','Sep','Okt','Nov','Dis']
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        name:'Jumlah Permohonan',
                        type:'bar',
                        data:[mt[0], mt[1], mt[2], mt[3], mt[4], mt[5], mt[6], mt[7], mt[8], mt[9], mt[10], mt[11]]
                    }
                ]
            };

            if (option && typeof option === "object") {
                rainChart.setOption(option, false);
            }
            /**
             * Resize chart on window resize
             * @return {void}
             */
            window.onresize = function() {
                rainChart.resize();
            };
        }
    });

    $('#tahun').on('change', function(e){
        
        e.preventDefault();

        var year = $(this).val();
        var url = '{{ route("getDataWidget") }}';

        $.ajax({
            url:url,
            method:'POST',
            data:{
                    "_token": '{{ csrf_token() }}',
                    "year": year,
                },
            success:function(response){
              console.log(response);  
                $('#baru').html(response[0].new);    
                $('#dt').html(response[0].dt);    
                $('#selesai').html(response[0].done);    
                $('#verify').html(response[0].verify);    
                $('#close').html(response[0].close);    
                $('#jumlah').html(response[0].total);

                var dom = document.getElementById("basic-Pie");
                var bpChart = echarts.init(dom);

                var app = {};
                option = null;
                option = {
                    color: ['#62549a','#4aa9e9', '#ff6c60','#eac459', '#25c3b2' ],
                    tooltip : {
                        trigger: 'item',
                        formatter: '{b} : {c} ({d}%)'
                    },
                    legend: {
                        orient : 'vertical',
                        x : 'left',
                        data:['PEJABAT KETUA AUDIT NEGARA','SEKTOR PENGURUSAN','SEKTOR AUDIT KEWANGAN','SEKTOR AUDIT PRESTASI','SEKTOR AUDIT TADBIR URUS']
                    },
                    calculable : true,
                    series : [
                        {
                            name:'Source',
                            type:'pie',
                            radius : '55%',
                            center: ['50%', '60%'],
                            itemStyle : {
                                normal : {
                                    label : {
                                        show: true, 
                                        position: 'inner',
                                        formatter : '{c}',
                                    }
                                }
                            },
                            labelLine: {
                                show: false
                                },
                            data:[
                                {value:response[3].pkan, name:'PEJABAT KETUA AUDIT NEGARA'},
                                {value:response[3].sp, name:'SEKTOR PENGURUSAN'},
                                {value:response[3].sak, name:'SEKTOR AUDIT KEWANGAN'},
                                {value:response[3].sap, name:'SEKTOR AUDIT PRESTASI'},
                                {value:response[3].satu, name:'SEKTOR AUDIT TADBIR URUS'}
                            ],
                            labelLine: {
                                show: false
                                },
                                label:{
                                    show: false
                                }
                        }
                    ]
                };

                if (option && typeof option === "object") {
                    bpChart.setOption(option, false);
                }

                var arr = [response[1].jan, response[1].feb, response[1].mac, response[1].apr, response[1].mei, response[1].jun, response[1].jul, response[1].ogos, response[1].sept, response[1].okt, response[1].nov, response[1].dis];
                arr = arr.map(Number); // convert string to integer

                var arrdone = [response[2].jan, response[2].feb, response[2].mac, response[2].apr, response[2].mei, response[2].jun, response[2].jul, response[2].ogos, response[2].sept, response[2].okt, response[2].nov, response[2].dis];
                arrdone = arrdone.map(Number); // convert string to integer

                Highcharts.chart('basic-line', {
                    title: {
                        text: 'Taburan Aduan',
                        x: -20 //center
                    },
                    xAxis: {
                        title: {
                            text: 'Bulan'
                        },
                        categories: ['Jan', 'Feb', 'Mac', 'Apr', 'Mei', 'Jun',
                            'Jul', 'Ogos', 'Sept', 'Okt', 'Nov', 'Dis']
                    },
                    yAxis: {
                        title: {
                            text: 'Bilangan'
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    colors: [
                    '#ff6c60', 
                    '#90ed7d'
                    ],
                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true
                            },
                        },
                        column: {
                            colorByPoint: true
                        }
                    },
                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true
                            },
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        borderWidth: 0
                    },
                    series: [{
                        name: 'Aduan Diterima',
                        data: [arr[0], arr[1], arr[2], arr[3], arr[4], arr[5], arr[6], arr[7], arr[8], arr[9], arr[10], arr[11]]
                    }, {
                        name: 'Aduan Selesai',
                        data: [arrdone[0], arrdone[1], arrdone[2], arrdone[3], arrdone[4], arrdone[5], arrdone[6], arrdone[7], arrdone[8], arrdone[9], arrdone[10], arrdone[11]]
                    }]
                });

                var mt = [response[4].jan, response[4].feb, response[4].mac, response[4].apr, response[4].mei, response[4].jun, response[4].jul, response[4].ogos, response[4].sept, response[4].okt, response[4].nov, response[4].dis];
                mt = mt.map(Number); // convert string to integer
                var dom = document.getElementById("rainfall");
                var rainChart = echarts.init(dom);

                var app = {};
                option = null;
                option = {
                    color: ['#4aa9e9'],
                    tooltip : {
                        trigger: 'axis'
                    },
                    legend: {
                        data:['Sale']
                    },
                    calculable : true,
                    xAxis : [
                        {
                            type : 'category',
                            data : ['Jan','Feb','Mac','Apr','Mei','Jun','Jul','Ogos','Sep','Okt','Nov','Dis']
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            name:'Jumlah Permohonan',
                            type:'bar',
                            data:[mt[0], mt[1], mt[2], mt[3], mt[4], mt[5], mt[6], mt[7], mt[8], mt[9], mt[10], mt[11]]
                        }
                    ]
                };

                if (option && typeof option === "object") {
                    rainChart.setOption(option, false);
                }
                /**
                 * Resize chart on window resize
                 * @return {void}
                 */
                window.onresize = function() {
                    rainChart.resize();
                };

            }
        });
    });


});

</script>
<div class="ui-content-body">  
    <div class="ui-container">

        <div class="row">
            <div class="col-md-2 pull-right">

                <form method="post">
                    @csrf
                    <div class="form-group">
                        <select name="tahun" id="tahun" class="form-control">
                        @php
                        $start_years = 2021;
                        $curr_years = date('Y');
                        
                        $years = range($curr_years, $start_years);
                        @endphp
                        @foreach($years as $year)
                        <option value="{{ $year }}" >{{ $year }}</option>;   
                        @endforeach
                        </select>
                    </div>
                </form>

            </div>
        </div>
 
        <!--states start-->
        <div class="row">
            <div class="col-md-2">
                <a class="underline" href="{{ route('complaintlist', ['status'=>1]) }}">
                <div class="panel short-states bg-default">
                    <div class="pull-right state-icon">
                        <i class="fa fa-inbox"></i>
                    </div>
                    <div class="panel-body">
                        <h1 id="baru"></h1>
                        <strong class="text-uppercase">Aduan Baru</strong>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-md-2">
                <a class="underline" href="{{ route('complaintlist', ['status'=>2]) }}">
                <div class="panel short-states bg-danger">
                    <div class="pull-right state-icon">
                        <i class="fa fa-edit"></i>
                    </div>
                    <div class="panel-body">
                        <h1 class="light-txt" id="dt"></h1>
                        <div class="pull-right"><i class="fa fa-info-circle" title="Dalam Tindakan Pegawai Teknikal&#10;Tindakan Pegawai&#10;Tindakan Pembekal"></i></div>
                        <strong class="text-uppercase">Dalam Tindakan</strong>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-md-2">
                <a class="underline" href="{{ route('complaintlist', ['status'=>4]) }}">
                <div class="panel short-states bg-warning">
                    <div class="pull-right state-icon">
                        <i class="fa fa-check-square-o"></i>
                    </div>
                    <div class="panel-body">
                        <h1 class="light-txt" id="selesai"></h1>
                        <div class="pull-right"><i class="fa fa-info-circle" title="Selesai Diperingkat Pegawai Teknikal&#10;Selesai Diperingkat Pegawai"></i></div>
                        <strong class="text-uppercase">Tindakan Selesai</strong>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-md-2">
                <a class="underline" href="{{ route('complaintlist', ['status'=>6]) }}">
                <div class="panel short-states bg-info">
                    <div class="pull-right state-icon">
                        <i class="fa fa-thumbs-o-up"></i>
                    </div>
                    <div class="panel-body">
                        <h1 class="light-txt" id="verify"></h1>
                        <div class="pull-right"><i class="fa fa-info-circle" title="Disahkan Selesai&#10;Tidak Disahkan Selesai"></i></div>
                        <strong class="text-uppercase">Pengesahan Pengadu</strong>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-md-2">
                <a class="underline" href="{{ route('complaintlist', ['status'=>8]) }}">
                <div class="panel short-states bg-primary">
                    <div class="pull-right state-icon">
                        <i class="fa fa-times-circle-o"></i>
                    </div>
                    <div class="panel-body">
                        <h1 class="light-txt" id="close"></h1>
                        <strong class="text-uppercase">Aduan Ditutup</strong>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-md-2">
                <div class="panel short-states bg-success">
                    <div class="pull-right state-icon">
                        <i class="fa fa-th-large"></i>
                    </div>
                    <div class="panel-body">
                        <h1 class="light-txt" id="jumlah"></h1>
                        <strong class="text-uppercase">Jumlah Aduan</strong>
                    </div>
                </div>
            </div>
        </div>
        <!--states end-->


        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <header class="panel-heading">
                        Taburan Aduan Mengikut Bulan
                    </header>
                    <div class="panel-body">
                        <div id="basic-line"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!--daily visit start-->
            <div class="col-md-6">
                <div class="panel">
                    <header class="panel-heading">
                        Pecahan Aduan Mengikut Sektor
                    </header>
                    <div class="panel-body">
                        <div id="basic-Pie" style="height: 400px"></div>
                    </div>
                </div>
            </div>
            <!--daily visit end-->
            <!--Visitor Graph start-->
            <div class="col-md-6">
                <div class="panel">
                    <header class="panel-heading">
                        Taburan Bantuan Bilik Mesyuarat Mengikut Bulan
                    </header>
                    <div class="panel-body">
                        <div id="rainfall" style="height: 390px"></div>
                    </div>
                </div>
            </div>
            <!--Visitor Graph end-->
        </div>

    </div>
</div>
@endsection
