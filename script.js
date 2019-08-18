$(document).ready(function(){
    let firstScreen = $(".tariffGroups"),
    secondScreen = $(".tariffPeriods"),
    thirdScreen = $(".selectTariff"),
    tariffInfos = $(".tariff__info"), // all clickable areas on main screen
    tariffPeriods = $(".tariffDetails"),
    selectionDetails = $(".selectionDetails");
    periodInfos = $(".period__info");

secondScreen.hide();
thirdScreen.hide();

tariffInfos.on("click", function(e){
    $(window).scrollTop(0);
    let tariffId = $(this).closest(".tariff__info").data("tariff_id"),
        groupToShow = "#tariffDetails-" + tariffId,
        periodToShow = "#selectionDetails-" + tariffId;
    firstScreen.hide();
    secondScreen.show();
    tariffPeriods.hide();
    selectionDetails.hide();
    $(groupToShow).show();
    $(periodToShow).show();
});

periodInfos.on("click", function(e){
    $(window).scrollTop(0);
    secondScreen.hide();
    thirdScreen.show();
    let periodId = $(this).closest(".period").data("period_id"),
        periodToLeave = ".periodDetail-" + periodId;
        console.log(periodToLeave);
    $(".periodDetail").hide();
    $(periodToLeave).show();
});

$(".jsBackToGroups").on("click", function(){
    secondScreen.hide();
    firstScreen.show();
});

$(".jsBackToPeriods").on("click", function(){
    thirdScreen.hide();
    secondScreen.show();
});
})