let time = 8; 
let points = 0;
let intervalTime = 0;
let intervalGame = 0;

$(".piranasPlant").hide();

$("button").click(function (){


    $("button").hide();
    intervalTime = setInterval(function () {
        time = time - 1;
        $("#intTime").html(time);
        if(time == 0) {
            $('.piranasPlant').hide(0);
            clearInterval(intervalTime);
            clearInterval(intervalGame);
            time = 7;
            $("#intTime").html(time);
            points = 0;
            $("#intScore").html(points);
            $("button").show();
        }
    }, 1000)

    intervalGame = setInterval(function () {
        let showTime = Math.floor(Math.random() * 1000) + 10;
        let hideTime = Math.floor(Math.random() * 1000) + 800;
        let randPirana =  Math.floor(Math.random() * 10) + 1;

        setTimeout(function() {
            $('#piranasPlant' + randPirana).show(0);

            setTimeout(function () {
                $('#piranasPlant' + randPirana).hide(0);
            }, hideTime);

        }, showTime);

    }, 1000);
                
    $('.piranasPlant').off('click');
    $('.piranasPlant').click(function() {
        $(this).hide();
        points++;
        $("#intScore").html(points);
    });

});