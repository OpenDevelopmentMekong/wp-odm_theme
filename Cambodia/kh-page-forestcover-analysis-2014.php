<?php the_content(); ?>

<script type="text/javascript">
  jQuery(document).ready(function($)
  {
    // Hectares
    $(".selected_img_ha").show(); 
    $('.graph_options').each(function(column) 
    {
      $(this).click(function(event)
      {
        if (this == event.target) 
        {
          var selected_id = $(this).attr("id"); 
          $(".selected_option_ha").attr("src","https://cambodia.opendevelopmentmekong.net/wp-content/uploads/sites/2/2016/01/option.png");
          $("#"+selected_id).attr("src","https://cambodia.opendevelopmentmekong.net/wp-content/uploads/sites/2/2016/01/selected.png"); 
          $(".graph_options").removeClass("selected_option_ha")
          $("#"+selected_id).addClass("selected_option_ha");
          
          var g_year = selected_id.substr(14, 25);
          var graph_year = "fc_"+g_year; 
          $(".fc_graph").fadeOut();
          $(".fc_graph").removeClass("selected_img_ha");
          $("."+graph_year).fadeIn();
          $("."+graph_year).addClass("selected_img_ha");
        }
      });
    });
    
    // Percentage 
    $(".selected_img_per").show();  
    $('.graph_options_per').each(function(column)
    {
      $(this).click(function(event)
      {
        if (this == event.target) 
        { 
          var selected_id = $(this).attr("id"); 
          $(".selected_option_per").attr("src","https://cambodia.opendevelopmentmekong.net/wp-content/uploads/sites/2/2016/01/option.png");
          $("#"+selected_id).attr("src","https://cambodia.opendevelopmentmekong.net/wp-content/uploads/sites/2/2016/01/selected.png"); 
          $(".graph_options_per").removeClass("selected_option_per")
          $("#"+selected_id).addClass("selected_option_per");
          
          var g_year = selected_id.substr(18, 28);
           
          var graph_year = "fc_per_"+g_year; 
          $(".fc_graph_per").fadeOut();
          $(".fc_graph_per").removeClass("selected_img_per");
          $("."+graph_year).fadeIn();
          $("."+graph_year).addClass("selected_img_per");
        } 
      });
    });
  });
</script>

<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
    <!-- 19973 -------------------------------------------------------->  
    var data_1973 = google.visualization.arrayToDataTable([
      ['Province',  'Total Forest', 'Dense Forest', 'Mixed Forest'],
      ['Koh Kong',1076876.59,973569.84,103306.74],
      ['Mondulkiri',1228871.94,827697.07,401174.86],
      ['Ratanakiri',1116499.13,783735.99,332763.14],
      ['Stung Treng',1133365.89,763265.58,370100.32],
      ['Pursat',950109.96,645005.55,305104.41],
      ['Kratie',1057880.07,557906.29,499973.78],
      ['Kampong Thom',806560.27,546873.56,259686.71],
      ['Preah Vihear',1310568.19,467835.12,842733.07],
      ['Battambang',747898.46,370146.04,377752.42],
      ['Siem Reap',680719.69,336312.34,344407.35],
      ['Oddar Meanchey',591263.06,270758.14,320504.92],
      ['Kampong Cham',622927.58,270155.39,352772.20],
      ['Kampot',246454.92,196518.59,49936.33],
      ['Preah Sihanouk',234369.06,194280.03,40089.04],
      ['Kampong Speu',375708.71,167339.07,208369.64],
      ['Pailin',95091.76,89857.15,5234.61],
      ['Banteay Meanchey',240698.38,57195.75,183502.64],
      ['Kampong Chhnang',232422.82,42364.96,190057.87],
      ['Svay Rieng',38528.55,20030.39,18498.17],
      ['Takeo',77268.35,15702.30,61566.05],
      ['Prey Veng',82719.77,5852.66,76867.11],
      ['Kep',5443.96,2448.73,2995.23],
      ['Kandal',129873.55,767.64,129105.91],
      ['Phnom Penh',13439.70,245.89,13193.81]
      ]);
    <!-- 1989---------------------------------------------------------->  
        var data_1989 = google.visualization.arrayToDataTable([
      ['Province',  'Total Forest', 'Dense Forest', 'Mixed Forest'],
      ['Koh Kong',1068066.76,953829.72,114237.05],
      ['Mondulkiri',1225129.50,806366.77,418762.73],
      ['Ratanakiri',1108012.06,719138.53,388873.53],
      ['Stung Treng',1101851.95,721737.03,380114.92],
      ['Pursat',928223.88,589168.14,339055.74],
      ['Kratie',993913.62,519618.41,474295.21],
      ['Kampong Thom',695250.14,526329.89,168920.26],
      ['Preah Vihear',1270767.97,447214.58,823553.40],
      ['Battambang',736974.99,340220.93,396754.07],
      ['Siem Reap',654829.26,274393.02,380436.24],
      ['Oddar Meanchey',541186.69,231180.41,310006.27],
      ['Kampong Cham',500465.47,212447.88,288017.59],
      ['Kampot',233869.09,191516.02,42353.08],
      ['Preah Sihanouk',203818.42,182855.06,20963.36],
      ['Kampong Speu',371427.96,165165.94,206262.02],
      ['Pailin',94650.47,82293.61,12356.86],
      ['Banteay Meanchey',194799.53,48742.16,146057.37],
      ['Kampong Chhnang',206844.26,36674.66,170169.60],
      ['Svay Rieng',28712.04,12325.09,16386.95],
      ['Takeo',50471.85,15369.19,35102.66],
      ['Prey Veng',30826.50,2073.95,28752.54],
      ['Kep',4242.23,1692.88,2549.35],
      ['Kandal',76257.88,767.64,75490.24],
      ['Phnom Penh',7209.65,240.67,6968.98]
        ]);
    <!-- 2000---------------------------------------------------------->  
        var data_2000 = google.visualization.arrayToDataTable([
      ['Province',  'Total Forest', 'Dense Forest', 'Mixed Forest'],
      ['Koh Kong',1022183.57,937074.74,85108.83],
      ['Mondulkiri',1228260.09,743688.63,484571.46],
      ['Ratanakiri',1096391.16,653885.03,442506.13],
      ['Stung Treng',1087709.60,590502.62,497206.98],
      ['Pursat',900272.81,579642.34,320630.48],
      ['Kratie',1011774.65,452833.05,558941.60],
      ['Kampong Thom',748532.08,496222.97,252309.12],
      ['Preah Vihear',1234156.41,339018.30,895138.11],
      ['Battambang',696023.72,282542.94,413480.78],
      ['Siem Reap',618326.69,221124.22,397202.46],
      ['Oddar Meanchey',486985.09,195951.84,291033.25],
      ['Kampong Cham',513318.43,150974.71,362343.72],
      ['Kampot',241167.39,170422.33,70745.07],
      ['Preah Sihanouk',191856.68,167491.00,24365.68],
      ['Kampong Speu',350457.03,126986.69,223470.35],
      ['Pailin',93011.68,69579.16,23432.52],
      ['Banteay Meanchey',177110.49,35447.91,141662.58],
      ['Kampong Chhnang',201769.31,29610.37,172158.94],
      ['Svay Rieng',29506.41,7386.50,22119.91],
      ['Takeo',38829.62,15140.76,23688.85],
      ['Prey Veng',38662.30,992.38,37669.92],
      ['Kep',4986.47,1627.80,3358.66],
      ['Kandal',83389.76,755.94,82633.82],
      ['Phnom Penh',8614.78,238.06,8376.72]

        ]);
    <!-- 2004---------------------------------------------------------->  
        var data_2004 = google.visualization.arrayToDataTable([
      ['Province',  'Total Forest', 'Dense Forest', 'Mixed Forest'],
      ['Koh Kong',1050485.82,913285.53,137200.29],
      ['Mondulkiri',1213981.29,724789.59,489191.71],
      ['Ratanakiri',1019881.64,635814.02,384067.61],
      ['Stung Treng',1085574.08,566443.94,519130.14],
      ['Pursat',868647.62,553297.75,315349.88],
      ['Kratie',978575.39,394540.16,584035.23],
      ['Kampong Thom',765474.36,483039.33,282435.02],
      ['Preah Vihear',1155736.02,322036.24,833699.77],
      ['Battambang',439538.62,113700.54,325838.08],
      ['Siem Reap',568094.79,167101.82,400992.97],
      ['Oddar Meanchey',447932.08,165129.57,282802.51],
      ['Kampong Cham',425389.70,95114.17,330275.53],
      ['Kampot',229309.42,135914.05,93395.37],
      ['Preah Sihanouk',210346.20,125366.26,84979.94],
      ['Kampong Speu',337539.81,90357.48,247182.33],
      ['Pailin',71697.48,33171.18,38526.30],
      ['Banteay Meanchey',60228.60,7197.23,53031.37],
      ['Kampong Chhnang',215793.85,21288.37,194505.48],
      ['Svay Rieng',35676.16,6275.50,29400.66],
      ['Takeo',51429.49,11538.82,39890.67],
      ['Prey Veng',69736.85,917.86,68818.99],
      ['Kep',4115.41,1111.27,3004.14],
      ['Kandal',131246.29,755.22,130491.07],
      ['Phnom Penh',11706.68,238,11469],

        ]);
    <!-- 2009---------------------------------------------------------->  
        var data_2009 = google.visualization.arrayToDataTable([
      ['Province',  'Total Forest', 'Dense Forest', 'Mixed Forest'],
      ['Koh Kong',975085,818831.27,156254.14],
      ['Mondulkiri',1074626,220836.48,853789.11],
      ['Ratanakiri',1014534,387122.69,627411.02],
      ['Stung Treng',1038935,447877.81,591057.40],
      ['Pursat',874770,454434.21,420335.91],
      ['Kratie',869399,277029.41,592369.29],
      ['Kampong Thom',709333,407128.87,302204.58],
      ['Preah Vihear',1210872,286889.30,923982.91],
      ['Battambang',633401,99760.87,533640.34],
      ['Siem Reap',595954,131543.57,464410.84],
      ['Oddar Meanchey',482376,150699.57,331676.44],
      ['Kampong Cham',377625,70758.57,306866.68],
      ['Kampot',200900,113772.54,87127.16],
      ['Preah Sihanouk',162148,92961.01,69186.83],
      ['Kampong Speu',263794,67262.11,196532.18],
      ['Pailin',66025,29498.76,36526.51],
      ['Banteay Meanchey',152467,3582.59,148884.47],
      ['Kampong Chhnang',121630,15907.05,105722.62],
      ['Svay Rieng',30773,4896.46,25876.30],
      ['Takeo',24258,11516.95,12741.18],
      ['Prey Veng',11158,917.77,10239.89],
      ['Kep',3248,835.95,2412.37],
      ['Kandal',32473,755.22,31718.25],
      ['Phnom Penh',2655,238,2417]

        ]);
    <!-- 2014---------------------------------------------------------->  
        var data_2014 = google.visualization.arrayToDataTable([
      ['Province',  'Total Forest', 'Dense Forest', 'Mixed Forest'],
      ['Koh Kong',866795.83,623958.96,242836.87],
      ['Mondulkiri',983886.34,271482.70,712403.64],
      ['Ratanakiri',743487.99,370245.63,373242.37],
      ['Stung Treng',953835.97,439225.87,514610.10],
      ['Pursat',733845.42,318779.23,415066.19],
      ['Kratie',612467.67,117104.64,495363.02],
      ['Kampong Thom',543934.84,237649.12,306285.72],
      ['Preah Vihear',910751.98,220677.03,690074.95],
      ['Battambang',425980.10,31526.73,394453.37],
      ['Siem Reap',398839.16,55431.78,343407.38],
      ['Oddar Meanchey',415020.83,57683.00,357337.83],
      ['Kampong Cham',124355.59,9924.83,114430.76],
      ['Kampot',176490.73,62275.09,114215.64],
      ['Preah Sihanouk',131525.44,46063.99,85461.45],
      ['Kampong Speu',206540.11,53432.76,153107.35],
      ['Pailin',57097.52,25963.66,31133.85],
      ['Banteay Meanchey',132706.40,2393.34,130313.06],
      ['Kampong Chhnang',160809.68,31320.62,129489.06],
      ['Svay Rieng',22173.88,4273.38,17900.50],
      ['Takeo',13959.25,1739.36,12219.89],
      ['Prey Veng',14251.49,1724.60,12526.90],
      ['Kep',3177.74,239.33,2938.41],
      ['Kandal',24009.55,4790.91,19218.64],
      ['Phnom Penh',3168.29,179.56,2988.73]
        ]);
    <!-- Persentage -------------------------------->
    <!-- 1973---------------------------------------------------------->  
        var per_data_1973 = google.visualization.arrayToDataTable([
      ['Province',  'Total Forest', 'Dense Forest', 'Mixed Forest'],
      ['Koh Kong',5.93,5.36,0.57],
      ['Mondulkiri',6.77,4.56,2.21],
      ['Ratanakiri',6.15,4.32,1.83],
      ['Stung Treng',6.24,4.20,2.04],
      ['Pursat',5.23,3.55,1.68],
      ['Kratie',5.83,3.07,2.75],
      ['Kampong Thom',4.44,3.01,1.43],
      ['Preah Vihear',7.22,2.58,4.64],
      ['Battambang',4.12,2.04,2.08],
      ['Siem Reap',3.75,1.85,1.90],
      ['Oddar Meanchey',3.26,1.49,1.76],
      ['Kampong Cham',3.43,1.49,1.94],
      ['Kampot',1.36,1.08,0.27],
      ['Preah Sihanouk',1.29,1.07,0.22],
      ['Kampong Speu',2.07,0.92,1.15],
      ['Pailin',0.52,0.49,0.03],
      ['Banteay Meanchey',1.33,0.31,1.01],
      ['Kampong Chhnang',1.28,0.23,1.05],
      ['Svay Rieng',0.21,0.11,0.10],
      ['Takeo',0.43,0.09,0.34],
      ['Prey Veng',0.46,0.03,0.42],
      ['Kep',0.03,0.01,0.02],
      ['Kandal',0.72,0.00,0.71],
      ['Phnom Penh',0.07,0.00,0.07]
        ]);
    <!-- 1989---------------------------------------------------------->  
        var per_data_1989 = google.visualization.arrayToDataTable([
      ['Province',  'Total Forest', 'Dense Forest', 'Mixed Forest'],
      ['Koh Kong',5.88,5.25,0.63],
      ['Mondulkiri',6.75,4.44,2.31],
      ['Ratanakiri',6.10,3.96,2.14],
      ['Stung Treng',6.07,3.97,2.09],
      ['Pursat',5.11,3.24,1.87],
      ['Kratie',5.47,2.86,2.61],
      ['Kampong Thom',3.83,2.90,0.93],
      ['Preah Vihear' ,7.00,2.46,4.53],
      ['Battambang',4.06,1.87,2.18],
      ['Siem Reap',3.61,1.51,2.09],
      ['Oddar Meanchey',2.98,1.27,1.71],
      ['Kampong Cham',2.76,1.17,1.59],
      ['Kampot',1.29,1.05,0.23],
      ['Preah Sihanouk',1.12,1.01,0.12],
      ['Kampong Speu',2.05,0.91,1.14],
      ['Pailin',0.52,0.45,0.07],
      ['Banteay Meanchey',1.07,0.27,0.80],
      ['Kampong Chhnang' ,1.14,0.20,0.94],
      ['Svay Rieng',0.16,0.07,0.09],
      ['Takeo',0.28,0.08,0.19],
      ['Prey Veng',0.17,0.01,0.16],
      ['Kep',0.02,0.01,0.01],
      ['Kandal',0.42,0.00,0.42],
      ['Phnom Penh',0.04,0.00,0.04]
        ]);
    <!-- 2000---------------------------------------------------------->  
        var per_data_2000 = google.visualization.arrayToDataTable([
      ['Province',  'Total Forest', 'Dense Forest', 'Mixed Forest'],
      ['Koh Kong',5.63,5.16,0.47],
      ['Mondulkiri',6.76,4.10,2.67],
      ['Ratanakiri',6.04,3.60,2.44],
      ['Stung Treng',5.99,3.25,2.74],
      ['Pursat',4.96,3.19,1.77],
      ['Kratie',5.57,2.49,3.08],
      ['Kampong Thom',4.12,2.73,1.39],
      ['Preah Vihear',6.80,1.87,4.93],
      ['Battambang',3.83,1.56,2.28],
      ['Siem Reap',3.40,1.22,2.19],
      ['Oddar Meanchey',2.68,1.08,1.60],
      ['Kampong Cham',2.83,0.83,2.00],
      ['Kampot',1.33,0.94,0.39],
      ['Preah Sihanouk',1.06,0.92,0.13],
      ['Kampong Speu',1.93,0.70,1.23],
      ['Pailin',0.51,0.38,0.13],
      ['Banteay Meanchey',0.98,0.20,0.78],
      ['Kampong Chhnang',1.11,0.16,0.95],
      ['Svay Rieng',0.16,0.04,0.12],
      ['Takeo',0.21,0.08,0.13],
      ['Prey Veng',0.21,0.01,0.21],
      ['Kep',0.03,0.01,0.02],
      ['Kandal',0.46,0.00,0.46],
      ['Phnom Penh',0.05,0.00,0.05],
        ]);
    <!-- 2004---------------------------------------------------------->  
        var per_data_2004 = google.visualization.arrayToDataTable([
      ['Province',  'Total Forest', 'Dense Forest', 'Mixed Forest'],
      ['Koh Kong',5.78,5.03,0.76],
      ['Mondulkiri',6.68,3.99,2.69],
      ['Ratanakiri',5.62,3.50,2.11],
      ['Stung Treng',5.98,3.12,2.86],
      ['Pursat',4.78,3.05,1.74],
      ['Kratie',5.39,2.17,3.22],
      ['Kampong Thom',4.21,2.66,1.56],
      ['Preah Vihear',6.36,1.77,4.59],
      ['Battambang',2.42,0.63,1.79],
      ['Siem Reap',3.13,0.92,2.21],
      ['Oddar Meanchey',2.47,0.91,1.56],
      ['Kampong Cham',2.34,0.52,1.82],
      ['Kampot',1.26,0.75,0.51],
      ['Preah Sihanouk',1.16,0.69,0.47],
      ['Kampong Speu',1.86,0.50,1.36],
      ['Pailin',0.39,0.18,0.21],
      ['Banteay Meanchey',0.33,0.04,0.29],
      ['Kampong Chhnang',1.19,0.12,1.07],
      ['Svay Rieng',0.20,0.03,0.16],
      ['Takeo',0.28,0.06,0.22],
      ['Prey Veng',0.38,0.01,0.38],
      ['Kep',0.02,0.01,0.02],
      ['Kandal',0.72,0.00,0.72],
      ['Phnom Penh',0.06,0.00,0.06],
        ]);
    <!-- 2009---------------------------------------------------------->  
        var per_data_2009 = google.visualization.arrayToDataTable([
      ['Province',  'Total Forest', 'Dense Forest', 'Mixed Forest'],
      ['Koh Kong',5.37,4.51,0.86],
      ['Mondulkiri',5.92,1.22,4.70],
      ['Ratanakiri',5.59,2.13,3.45],
      ['Stung Treng',5.72,2.47,3.25],
      ['Pursat',4.82,2.50,2.31],
      ['Kratie',4.79,1.53,3.26],
      ['Kampong Thom',3.91,2.24,1.66],
      ['Preah Vihear',6.67,1.58,5.09],
      ['Battambang',3.49,0.55,2.94],
      ['Siem Reap',3.28,0.72,2.56],
      ['Oddar Meanchey',2.66,0.83,1.83],
      ['Kampong Cham',2.08,0.39,1.69],
      ['Kampot',1.11,0.63,0.48],
      ['Preah Sihanouk',0.89,0.51,0.38],
      ['Kampong Speu',1.45,0.37,1.08],
      ['Pailin',0.36,0.16,0.20],
      ['Banteay Meanchey',0.84,0.02,0.82],
      ['Kampong Chhnang',0.67,0.09,0.58],
      ['Svay Rieng',0.17,0.03,0.14],
      ['Takeo',0.13,0.06,0.07],
      ['Prey Veng',0.06,0.01,0.06],
      ['Kep',0.02,0.00,0.01],
      ['Kandal',0.18,0.00,0.17],
      ['Phnom Penh',0.01,0.00,0.01],

        ]);
    <!-- 2014---------------------------------------------------------->  
        var per_data_2014 = google.visualization.arrayToDataTable([
      ['Province',  'Total Forest', 'Dense Forest', 'Mixed Forest'],
      ['Koh Kong',4.77,3.44,1.34],
      ['Mondulkiri',5.42,1.49,3.92],
      ['Ratanakiri',4.09,2.04,2.05],
      ['Stung Treng',5.25,2.42,2.83],
      ['Pursat',4.04,1.76,2.29],
      ['Kratie',3.37,0.64,2.73],
      ['Kampong Thom',2.99,1.31,1.69],
      ['Preah Vihear',5.01,1.21,3.80],
      ['Battambang',2.35,0.17,2.17],
      ['Siem Reap',2.20,0.31,1.89],
      ['Oddar Meanchey',2.28,0.32,1.97],
      ['Kampong Cham',0.68,0.05,0.63],
      ['Kampot',0.97,0.34,0.63],
      ['Preah Sihanouk',0.72,0.25,0.47],
      ['Kampong Speu',1.14,0.29,0.84],
      ['Pailin',0.31,0.14,0.17],
      ['Banteay Meanchey',0.73,0.01,0.72],
      ['Kampong Chhnang',0.89,0.17,0.71],
      ['Svay Rieng',0.12,0.02,0.10],
      ['Takeo',0.08,0.01,0.07],
      ['Prey Veng',0.08,0.01,0.07],
      ['Kep',0.02,0.00,0.02],
      ['Kandal',0.13,0.03,0.11],
      ['Phnom Penh',0.02,0.00,0.02],

        ]);
        var options = {
          <!--title: 'Forest Cover Distribution by Province (ha)',-->
      colors: ['#00bb11', '#007600', '#afdb6d'],
      <!--vAxis:{title: 'Hectare'},-->
      bar:{groupWidth: '80%'},
      fontSize:10,
      legend: {'position': 'top'},
      width: 760,
          height: 257,
      chartArea: {left:60,'width': '100%', 'height': '60%'},
      backgroundColor:{fill:'#e7e7e7',strokeWidth:0,stroke:'#ddd'},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_1973'));
        chart.draw(data_1973, options);
    
    var chart = new google.visualization.ColumnChart(document.getElementById('chart_1989'));
        chart.draw(data_1989, options);
    
    var chart = new google.visualization.ColumnChart(document.getElementById('chart_2000'));
        chart.draw(data_2000, options);
    
    var chart = new google.visualization.ColumnChart(document.getElementById('chart_2004'));
        chart.draw(data_2004, options);
    
    var chart = new google.visualization.ColumnChart(document.getElementById('chart_2009'));
        chart.draw(data_2009, options);
    
    var chart = new google.visualization.ColumnChart(document.getElementById('chart_2014'));
        chart.draw(data_2014, options);
    
    <!-- persentage % -------------------------------------------------------------------->
    var chart = new google.visualization.ColumnChart(document.getElementById('per_chart_1973'));
        chart.draw(per_data_1973, options);
    
    var chart = new google.visualization.ColumnChart(document.getElementById('per_chart_1989'));
        chart.draw(per_data_1989, options);
    
    var chart = new google.visualization.ColumnChart(document.getElementById('per_chart_2000'));
        chart.draw(per_data_2000, options);
    
    var chart = new google.visualization.ColumnChart(document.getElementById('per_chart_2004'));
        chart.draw(per_data_2004, options);
    
    var chart = new google.visualization.ColumnChart(document.getElementById('per_chart_2009'));
        chart.draw(per_data_2009, options);
    
    var chart = new google.visualization.ColumnChart(document.getElementById('per_chart_2014'));
        chart.draw(per_data_2014, options);
      }
    </script>