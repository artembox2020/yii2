<?php 
   use frontend\models\Base;
   ?>
       
<div class="container">

   <div class="row">
      <div class="col-md-12 layout-content-container">
         <style>
.table {
        display: table;
    }
    .table-row {
        display: table-row;
    }
    .table .table-element div{
        font-family: Arial;
        text-align: center;
        vertical-align: middle;
    }
    .table-element{
        display: table-cell;
        vertical-align: top;
        width: 160px;
		float:left;
    }

    .table-element-top-row, .table-element-bottom-row{
        display: table-row;
        table-layout: fixed;
    }

    .table-element-top-row .cell, .table-element-bottom-row .cell{
        display: table-cell;
        border: 1px solid white;
        min-width: 80px;
        height: 50px;
        padding: 1px;
        word-break: break-word;
        vertical-align: middle;
    }

    /* Цвета и доп.стили */
    .table-element-title{
        background-color: #5b9bd6;
        color: white;
        font-size: x-large;
        padding: 5px;
        border: 1px solid white;
        border-bottom: 4px solid white;
    }
    .table-element-top-row .cell{
        background-color: #d1deef;
    }
    .table-element-bottom-row .cell{
        background-color: #e9eff7;
    }
    .right-cell{
        font-size: 11px;
    }
    .table-element-bottom-row .left-cell{
        font-size: 14px;
    }
         </style>
<div class="icon-menu">
            <img src="/frontend/web/static/img/menu-ham-icon.png">
            Menu
        </div>
         <div class="w3-bar w3-black">
		 
            <?php if(Yii::$app->user->can('is_fin')) {?>
            <button class="w3-bar-item btn btn-default w3-button" onclick="openCity('1')">Финансовая и фирменная информация автомата</button>
            <?php }?>
            <?php if(Yii::$app->user->can('is_tech')) {?>
            <button class="w3-bar-item btn btn-default w3-button" onclick="openCity('2')">Техническая информация автомата и действия</button>
            <?php }?>
         </div>
         <div class="container">
            <div class="row">
               <br>
               <?php if(Yii::$app->user->can('is_fin')) {?>
               <div id="1" class="city">
			   <div class="menu">
       
        <!-- Иконка меню -->
        <div class="icon-close">
            <img src="/frontend/web/static/img/but006.png">
        </div>
 
        <!-- Меню -->
			<form action="" method="get" id="select" style="font-size: 12px;">
									<ul id="navbar">
									<li><input name="column" type="checkbox" value="1" checked="checked" /> IMEI</li>
									<li><input name="column" type="checkbox" value="2" checked="checked" /> ГОРОД</li>
									<li> <input name="column" type="checkbox" value="3" checked="checked" /> АДРЕС</li>
									<li> <input name="column" type="checkbox" value="4" checked="checked" /> ОРГАНИЗАЦИЯ</li>
									<li> <input name="column" type="checkbox" value="5" checked="checked" /> СЧЕТ</li>
									<li> <input name="column" type="checkbox" value="6" /> НЕСГОРАЕМЫЙ ОСТАТОК</li>
									<li> <input name="column" type="checkbox" value="7" /> ДАТА ПОСЛЕДНЕЙ ИНКИСАЦИИ</li>
									<li> <input name="column" type="checkbox" value="8" /> КОЛ-ВО КУПЮР, шт</li>
									<li> <input name="column" type="checkbox" value="9" /> СЧЕТЧИК ОБНУЛЕНИЯ, грн</li>
									<li> <input name="column" type="checkbox" value="10" /> ТЕХНИЧЕСКИЙ ВБРОС, грн</li>
									<li> <input name="column" type="checkbox" value="11" /> ДЕНЕГ В КУПЮРОПРИЕМНИКЕ</li>
									<li> <input name="column" type="checkbox" value="12" /> КОЛ-ВО КУПЮР В КУПЮРОПРИЕМНИКЕ</li>
									</ul>
								</form>
    </div>

                  <div class="col-md-9">
                     <p>
                     <div class="table" style="background-color: #fff; font-size: 13px;">
                        <table id="table" class="table" >
                           <thead style="font-size: 10px;">
                              <tr>
                                 <th>IMEI</th>
                                 <th>ГОРОД</th>
                                 <th>АДРЕС</th>
                                 <th>ОРГАНИЗАЦИЯ</th>
                                 <th>СЧЕТ</th>
                                 <th>НЕСГОРАЕМЫЙ ОСТАТОК</th>
                                 <th>ДАТА ПОСЛЕДНЕЙ ИНКИСАЦИИ</th>
                                 <th>КОЛ-ВО КУПЮР, шт</th>
                                 <th>СЧЕТЧИК ОБНУЛЕНИЯ, грн</th>
                                 <th>ТЕХНИЧЕСКИЙ ВБРОС, грн</th>
                                 <th>ДЕНЕГ В КУПЮРОПРИЕМНИКЕ</th>
                                 <th>КОЛ-ВО КУПЮР В КУПЮРОПРИЕМНИКЕ</th>
                              </tr>
                           </thead>
                           <?php 
                              $ni = 1;
                              foreach ($devices as $dev){ 
                              $last = $dev->getBase()->orderBy(['date' => SORT_DESC])->limit(1)->one();
                              ?> 
                           <tbody id="nw-res-log">
                              <tr>
                                 <td style="text-align: center;vertical-align: middle;"><?=$dev['id_dev']?></td>
                                 <td style="text-align: center;vertical-align: middle;"><?=$dev['city']?></td>
                                 <td style="text-align: center;vertical-align: middle;"><?=$dev['adress']?></td>
                                 <td style="text-align: center;vertical-align: middle;"><?=$dev['organization']?></td>
                                 <td style="text-align: center;vertical-align: middle;"><?php if (isset($last['billModem'])){echo $last['billModem'].' грн';} else {echo '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';}; ?></td>
                                 <td style="text-align: center;vertical-align: middle;"><?php if (isset($last['ost'])){echo $last['ost'].' грн';} else {echo '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';}; ?></td>
                                 <td style="text-align: center;vertical-align: middle;"><i class="fa fa-ban" aria-hidden="true"></i></td>
                                 <td style="text-align: center;vertical-align: middle;"><i class="fa fa-ban" aria-hidden="true"></i></td>
                                 <td style="text-align: center;vertical-align: middle;"><i class="fa fa-ban" aria-hidden="true"></i></td>
                                 <td style="text-align: center;vertical-align: middle;"><i class="fa fa-ban" aria-hidden="true"></i></td>
                                 <td style="text-align: center;vertical-align: middle;"><?php if (isset($last['sumBills'])){echo $last['sumBills'];} else {echo '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';}; ?></td>
                                 <td style="text-align: center;vertical-align: middle;"><?php if (isset($dev['numBills'])){echo $last['numBills'];} else {echo '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';}; ?></td>
                              </tr>
                           </tbody>
                           <?php $ni++; } ?>
                        </table>
                     </div>
                     </p>
                  </div>
               </div>
               <?php }?>
               <?php if(Yii::$app->user->can('is_tech')) {?>
               <div id="2" class="city" style="display:none;">
			   <div class="menu">
       
        <!-- Иконка меню -->
        <div class="icon-close">
            <img src="/frontend/web/static/img/but006.png">
        </div>
 
        <!-- Меню -->
         <form action="" method="get" id="select2" style="font-size: 12px;">
                        <ul id="navbar">
                           <li><input name="column" type="checkbox" value="1" checked="checked" /> IMEI</li>
                           <li><input name="column" type="checkbox" value="2" /> АДРЕС</li>
                           <li> <input name="column" type="checkbox" value="3" /> ВЕРСИЯ ПО</li>
                           <li> <input name="column" type="checkbox" value="4" /> НОМЕР SIM МОДЕМА</li>
                           <li> <input name="column" type="checkbox" value="5" /> АВАРИЙНЫЙ НОМЕР</li>
                           <li> <input name="column" type="checkbox" value="6" /> ЗАДЕРЖКА</li>
                           <li> <input name="column" type="checkbox" value="7" /> СВЯЗЬ</li>
                           <li> <input name="column" type="checkbox" value="8" /> ЗАПОЛНЕННОСТЬ КУПЮРНИКА</li>
                           <li> <input name="column" type="checkbox" value="9" /> СЧЕТЧИК ИНКАСАКЦИЙ</li>
                           <li> <input name="column" type="checkbox" value="10" /> КОЛ-ВО ДНЕЙ ПРОСТОЯ</li>
                           <li> <input name="column" type="checkbox" value="11" checked="checked" /> УСТРОЙСТВО  |  СЧЕТ | УРОВЕНЬ | СОСТОЯНИЕ</li>
                           <?php if(Yii::$app->user->can('add_com')) {?>
                           <li> <input name="column" type="checkbox" value="12" /> ДЕЙСТВИЕ</li>
                           <?php }?>
                        </ul>
                     </form>
				</div>

                  <div class="col-md-12">
                     <p>
                     <div class="table" style="background-color: #fff; font-size: 13px;">
                        <table id="table2" class="table" >
                           <thead style="font-size: 10px;">
                              <tr>
                                 <th>IMEI</th>
                                 <th>АДРЕС</th>
                                 <th>ВЕРСИЯ ПО</th>
                                 <th>НОМЕР SIM МОДЕМА</th>
                                 <th>АВАРИЙНЫЙ НОМЕР</th>
                                 <th>ЗАДЕРЖКА</th>
                                 <th>СВЯЗЬ</th>
                                 <th>ЗАПОЛНЕННОСТЬ КУПЮРНИКА</th>
                                 <th>СЧЕТЧИК ИНКАСАКЦИЙ</th>
                                 <th>КОЛ-ВО ДНЕЙ ПРОСТОЯ</th>
                                 <th style="font-size: 10px;">УСТРОЙСТВО  |  СЧЕТ | УРОВЕНЬ | СОСТОЯНИЕ</th>
                                 <th>ДЕЙСТВИЕ</th>
                              </tr>
                           </thead>
                           <?php 
                              $ni = 1;
                              foreach ($devices as $dev){ 
                              $last = $dev->getBase()->orderBy(['date' => SORT_DESC])->limit(1)->one();
                              $kps = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';
                              if (isset($last['numBills']) AND is_numeric($dev['kps']) AND $dev['kps'] > 0 AND $last['numBills'] > 0 AND is_numeric($last['numBills'])) $kps = round(($last['numBills']/$dev['kps'])* 100).'%';
                              
                              ?> 
                           <tbody id="nw-res-log">
                              <tr>
                                 <td style="text-align: center;vertical-align: middle;"><?=$dev['id_dev']?></td>
                                 <td style="text-align: center;vertical-align: middle;"><?=$dev['adress']?></td>
                                 <td style="text-align: center;vertical-align: middle;"><?php if (isset($last['fvVer']) AND $last['fvVer'] != ''){echo $last['fvVer'];} else {echo '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';}; ?></td>
                                 <td style="text-align: center;vertical-align: middle;"><?php if (isset($last['mTel']) AND $last['mTel'] != ''){echo $last['mTel'];} else {echo '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';}; ?></td>
                                 <td style="text-align: center;vertical-align: middle;"><?php if (isset($last['sTel']) AND $last['sTel'] != ''){echo $last['sTel'];} else {echo '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';}; ?></td>
                                 <td style="text-align: center;vertical-align: middle;"><?php if (isset($last['timeout']) AND $last['timeout'] != ''){echo $last['timeout'].' сек';} else {echo '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';}; ?></td>
                                 <td style="text-align: center;vertical-align: middle;"><?php if (isset($last['gsmSignal'])){echo $last['gsmSignal'].' dBm';} else {echo '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';}; ?></td>
                                 <td style="text-align: center;vertical-align: middle;"><?php echo $kps ?></td>
                                 <td style="text-align: center;vertical-align: middle;"><i class="fa fa-ban" aria-hidden="true"></i></td>
                                 <td style="text-align: center;vertical-align: middle;"><i class="fa fa-ban" aria-hidden="true"></i></td>
                                 <td style="text-align: center;vertical-align: middle;">
                                    <div class="table">
                                       <?php
                                          $eedate = $last['edate'];
                                          
                                          if(isset($eedate)){
                                          	$types = Base::getTypes($dev['id_dev'], $last['edate']);
                                          	
                                          foreach ($types as $type){
                                          	
                                            ?>
                                       <div class="table-element">
                                          <div class="table-element-title">
                                             СМ <?php echo $type['numDev']; ?>
                                          </div>
                                          <div class="table-element-top-row">
                                             <div class="cell left-cell">
                                                <?php 
                                                   if(!$type['billCash'] != ''){
                                                   	echo $type['billCash']; 
                                                   } else {
                                                   	echo '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';
                                                   }
                                                              ?>
                                             </div>
                                             <div class="cell right-cell">
                                                <?php echo Base::nameStatus($type['type'], $type['statusDev']); ?>
                                             </div>
                                          </div>
                                          <div class="table-element-bottom-row">
                                             <div class="cell left-cell">
                                                <?php 
                                                   if($type['type'] == 'GD'){
                                                       echo '<div><small> Гель </small></div>';  
                                                       if($type['colGel'] != ''){
                                                           echo $type['colGel']; 
                                                       } else {
                                                           echo '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
                                                       }                                     
                                                   } else {
                                                       echo '<div><small> ZigBee </small></div>';
                                                       if($type['devSignal'] != ''){
                                                               echo $type['devSignal'].' dBm'; 
                                                           } else {
                                                               echo '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';
                                                       }                                        
                                                   }
                                                   ?>
                                             </div>
                                             <div class="cell right-cell">
                                                <?php echo '<font size=1>'.date("Y-m-d", strtotime($type['edate'])).'</font><br>';
                                                   echo '<font size=1>'.date("H:i:s", strtotime($type['edate'])).'</font>';?>
                                             </div>
                                          </div>
                                       </div>
                                       <?php } } ?>
                                    </div>
                                    <?php if(Yii::$app->user->can('add_com')) {?>
                                 <td><a href="#" onclick="openbox('box'); return false">Действия</a></td>
                                 <div class="hiden_box" id="box" style="display: none;">
                                    <div class="block">
                                       <div class="btn  btn-success btn-sm my-btn" data-id="<?=$dev['id_dev']?>" data-comand="1">Перезагрузка ЦП</div>
                                       <div class="btn  btn-success btn-sm my-btn" data-id="<?=$dev['id_dev']?>" data-comand="2">Перезагрузка<br> Купюроприемника</div>
                                       <div class="btn  btn-success btn-sm my-btn" data-id="<?=$dev['id_dev']?>" data-comand="3">Перезагрузка ZigBee</div>
                                       <div class="btn  btn-success btn-sm my-btn" data-id="<?=$dev['id_dev']?>" data-comand="4">Перезагрузка модема</div>
                                       <div class="btn  btn-success btn-sm my-btn" data-id="<?=$dev['id_dev']?>" data-comand="5">Форматирование Логдиска</div>
                                       <div class="btn  btn-success btn-sm my-btn" data-id="<?=$dev['id_dev']?>" data-comand="6">Установка времени<br> с сервера</div>
                                       <div class="btn  btn-success btn-sm my-btn" data-id="<?=$dev['id_dev']?>" data-comand="7">Отослать прайс</div>
                                    </div>
                                 </div>
                                 <?php }?>
                              </tr>
                           </tbody>
                           <?php $ni++; } ?>
                        </table>
                     </div>
                     </p> 
                  </div>
               </div>
               <?php }?>
            </div>
         </div>
      </div>
      <style>
         .hiden_box {
         position: relative;
         display: inline-block;
         }
         .hiden_box .block {
         width: 220px;
         height: 350px;
         background-color: #dcd;
         color: #dcd;
         text-align: center;
         padding: 5px 0;
         border-radius: 6px;
         position: absolute;
         z-index: 1;
         top: -5px;
         left: 40%; 
         }
      </style>
   </div>
</div>
         <div class="panel panel-body">
            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> - ДАННЫЕ В СИСТЕМУ ЕЩЕ НЕ ПОСТУПИЛИ <br>
            <i class="fa fa-ban" aria-hidden="true"></i> - ДАННЫЕ В СТРУКТУРЕ ПАКЕТА ОТСУТСТВУЮТ - ОБСУЖДАЕМ, ДОБАВЛЯЕМ ПЕРЕМЕННЫЕ
            <div id="refr"></div>
         </div>
</div>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script>
   function openbox(id){
       display = document.getElementById(id).style.display;
   
       if(display=='none'){
          document.getElementById(id).style.display='block';
       }else{
          document.getElementById(id).style.display='none';
       }
   }
   
   
   function openCity(cityName) {
       var i;
       var x = document.getElementsByClassName("city");
       for (i = 0; i < x.length; i++) {
           x[i].style.display = "none"; 
       }
       document.getElementById(cityName).style.display = "block"; 
   }
      var time = 60;
       
   //    function refr(){
   //        $('#refr').html('Обновление через: '+time+' секунд.');
   //        time = time -1;
   //        if(time < 1){
   //            location.reload();
   //        }
   //    }
   //    setInterval(refr, 1000);
   //
        $('.my-btn').on('click', function(){
           var imei = $(this).attr('data-id');
           var comand = $(this).attr('data-comand');
           $.ajax({
               type: 'GET',
               url: '/site/addcom?imei=' + imei + '&comand=' + comand,
               cache: false,
               success: function (datar) {
                   alert ('Данные будут отправлены при следующем сеансе связи.');
               }
           });
       });
   
</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
   $(document).ready(function(){
   var checkBoxes = $("#select input[type='checkbox']");
   for (var i = 0; i < checkBoxes.length; i++){
      if(!checkBoxes[i].checked){
          $('#table td:nth-child('+$(checkBoxes[i]).val()+')').toggle("slow");
   $('#table th:nth-child('+$(checkBoxes[i]).val()+')').toggle("slow");
      }
   }
   $("#select input[type='checkbox']").click(function(){                        
      $('#table td:nth-child('+$(this).val()+')').toggle("slow");
   $('#table th:nth-child('+$(this).val()+')').toggle("slow");
   });  
   
   var checkBoxes2 = $("#select2 input[type='checkbox']");
   for (var i = 0; i < checkBoxes2.length; i++){
      if(!checkBoxes2[i].checked){
          $('#table2 td:nth-child('+$(checkBoxes2[i]).val()+')').toggle("slow");
   $('#table2 th:nth-child('+$(checkBoxes2[i]).val()+')').toggle("slow");
      }
   }
   $("#select2 input[type='checkbox']").click(function(){                        
      $('#table2 td:nth-child('+$(this).val()+')').toggle("slow");
   $('#table2 th:nth-child('+$(this).val()+')').toggle("slow");
   });	   
   });
   
   var main = function() { //главная функция
 
    $('.icon-menu').click(function() { /* выбираем класс icon-menu и
               добавляем метод click с функцией, вызываемой при клике */
 
        $('.menu').animate({ //выбираем класс menu и метод animate
 
            left: '0px' /* теперь при клике по иконке, меню, скрытое за
               левой границей на 285px, изменит свое положение на 0px и станет видимым */
 
        }, 200); //скорость движения меню в мс
         
        $('body').animate({ //выбираем тег body и метод animate
 
            left: '285px' /* чтобы всё содержимое также сдвигалось вправо
               при открытии меню, установим ему положение 285px */
 
        }, 200); //скорость движения меню в мс
    });
 
 
/* Закрытие меню */
 
    $('.icon-close').click(function() { //выбираем класс icon-close и метод click
 
        $('.menu').animate({ //выбираем класс menu и метод animate
 
            left: '-285px' /* при клике на крестик меню вернется назад в свое
               положение и скроется */
 
        }, 200); //скорость движения меню в мс
         
    $('body').animate({ //выбираем тег body и метод animate
 
            left: '0px' //а содержимое страницы снова вернется в положение 0px
 
        }, 200); //скорость движения меню в мс
    });
};
 
$(document).ready(main); /* как только страница полностью загрузится, будет
               вызвана функция main, отвечающая за работу меню */
</script>