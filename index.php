<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SkyNet</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?
    // FUNCTION TO GET PROPER FORM OF WORD "месяц"
    function getMonths($qty){ 
        switch ($qty){
            case 1: return " месяц";
            break;
            case ($qty > 1 && $qty < 5): return " месяца";
            break;
            case ($qty >= 5 && $qty <= 12): return " месяцев";
            break;
            default: return " мес";
        }
    }
        
    // SET OF COLOR TO COLOR SPEED AREA DYNAMICALLY
    $speedColors = ["brown", "blue", "orange", "blue", "orange"];
    
    $speedUnits = " Мбит/с";
    $currency = " &#8381";
        
    // GET AND DECODE JSON DATA
    $data = file_get_contents('http://sknt.ru/job/frontend/data.json');
    $decodedData = json_decode($data);
    
    $tariffGroups = $decodedData->tarifs;
    ?>
    
    <div class="pageWrapper">
        <section class="screen tariffGroups">
            <?foreach($tariffGroups as $key => $tariffGroup){
                $tariffs = $tariffGroup -> tarifs; // ALL TARIFFS IN GROUP
                $freeOptions = $tariffGroup -> free_options; // FREE OPTIONS IN TARIFF
                
                // CREATE LIST OF PRICES TO GENERATE RANGE
                $prices = [];
                foreach($tariffs as $tariff) {
                    $price = $tariff -> price / $tariff -> pay_period;
                    $prices[] = $price;
                }?>
                <div class="tariff" id="tariff-<?echo($key + 1)?>">
                    <div class="tariff__header">
                        <h2 class="tariff__name">Тариф "<?echo($tariffGroup -> title)?>"</h2>
                    </div>
                    <div class="tariff__info" data-tariff_id="<?echo($key + 1)?>">
                        <div class="tariff__speed <?echo($speedColors[$key])?>">
                            <?echo($tariffGroup -> speed . $speedUnits)?>
                        </div>
                        <div class="tariff__prices">
                            <?echo(min($prices) . " &#8381 &#8213 " . max($prices) . $currency . "/мес")?>
                        </div>
                        <div class="tariff__options">
                        <?if($freeOptions){
                            foreach($freeOptions as $freeOption){?>
                                <p class="option"><?echo($freeOption)?></p>
                            <?}
                        }?>
                        <i class="arrow arrow-right next"></i>
                    </div>
                    </div>
                    <div class="tariff__more">
                        <a href="<?echo($tariffGroup -> link)?>" target="_blank" class="tariff__link">узнать подробнее на сайте www.sknt.ru</a>
                    </div>
                </div> 
            <?}?>
        </section>
        
        <section class="screen tariffPeriods">
            <?foreach($tariffGroups as $key => $tariffGroup){
                $periods = $tariffGroup -> tarifs;

                // ASC SORTING OF PAY PERIODS BY ID
                usort($periods,function($first,$second){
                    return $first->ID > $second->ID;
                });
                $maxPrice = $periods[0] -> price;
            ?>
                <div class="tariffDetails" id="tariffDetails-<?echo($key + 1);?>">
                    <header class="screenHeader">
                        <i class="back arrow arrow-left jsBackToGroups"></i>
                        <h2>Тариф "<?echo($tariffGroup -> title)?>"</h2>
                    </header>
                    <div class="periods">
                    <?foreach($periods as $key => $period){
                        $price = $period -> price / $period -> pay_period;
                        $discount = $maxPrice - $price;
                    ?>
                        <div class="period" data-period_id="<?echo($key + 1)?>">
                            <div class="period__long">
                                <?echo($period -> pay_period . getMonths($period -> pay_period))?>
                            </div>
                            <div class="period__info">
                                <div class="period__monthPrice">
                                    <?echo($price . $currency . "/мес")?>
                                </div>
                                <div class="period__allPrice">
                                    <span>разовый платеж &#8213 </span><?echo($period -> price . $currency)?>
                                </div>
                                <?if($discount > 0){?>
                                    <div class="period__discount">
                                        <span>скидка &#8213 </span><?echo($discount * $period -> pay_period . $currency)?>
                                    </div>
                                <?}?>
                                <i class="arrow-right arrow-little next-little"></i>
                            </div>
                        </div>
                    <?}?>
                    </div>
                </div>
            <?};?>
        </section>
        
        <section class="screen selectTariff">
            <?foreach($tariffGroups as $key => $tariffGroup){
                $periods = $tariffGroup -> tarifs;

                // ASC SORTING OF PAY PERIODS BY ID
                usort($periods,function($first,$second){
                    return $first->ID > $second->ID;
                });
                $maxPrice = $periods[0] -> price;
            ?>
                <div class="selectionDetails" id="selectionDetails-<?echo($key + 1);?>">
                    <header class="screenHeader">
                        <i class="back arrow arrow-left jsBackToPeriods"></i>
                        <h2>Выбор тарифа</h2>
                    </header>
                    <div class="periodDetails">
                    <?foreach($periods as $key => $period){
                        $price = $period -> price / $period -> pay_period;
                    ?>
                        <div class="periodDetail periodDetail-<?echo($key + 1)?>">
                            <div class="periodDetail__header">
                                <h2 class="periodDetail__name">Тариф "<?echo($tariffGroup -> title)?>"</h2>
                            </div>
                            <div class="periodDetail__period">
                                <div class="periodDetail__long">
                                    <p>Период оплаты &#8213 <span><?echo($period -> pay_period . getMonths($period -> pay_period))?></span></p>
                                </div>
                                <div class="periodDetail__monthPrice">
        
                                </div>
                            </div>
                            <div class="periodDetail__payment">
                                <div class="periodDetail__allPrice">
                                    <span>разовый платеж &#8213 </span><?echo($period -> price . $currency)?>
                                </div>
                                <div class="periodDetail__allPrice">
                                    <span>со счета спишется &#8213 </span><?echo($period -> price . $currency)?>
                                </div>
                            </div>
                            <div class="periodDetail__dates">
                                <div class="periodDetail__startDate">
                                    <span>вступит в силу &#8213 сегодня
                                </div>
                                <div class="periodDetail__endDate">
                                    <span>активно до &#8213 <?echo(date('d.m.Y', $period -> new_payday))?></span>
                                </div>
                            </div>
                            <div class="periodDetail__button" data-info="<?echo($tariffGroup -> title . " " . $period -> pay_period . getMonths($period -> pay_period))?>">Выбрать</div>
                        </div>
                    <?}?>
                    </div>
                </div>
            <?};?>
        </section>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>
</html>




























