function RefreshOptions() {
    $.getJSON('vote.php', function(data) {
        options = data;
        $('#opt-1').text(options[0]);
        $('#opt-2').text(options[1]);
        $('.options').fadeIn("slow");
        updateStats();
    });
}
$(document).ready(function() {
    RefreshOptions();
    $('#skip').click(function() {
        RefreshOptions();
    });
    $('#opt-1').click(function(){
        SubmitChoice(1);
    });
    $('#opt-2').click(function(){
        SubmitChoice(2);
    });
    $('#resultlink').click(function(){
        $('.options').load('stats.php');
        $('#footer').fadeOut();
    });
    $('body').keyup(function (event) {
        if (event.keyCode == 37) {
            // left arrow
            SubmitChoice(1);
        } else if (event.keyCode == 39) {
            // right arrow
            SubmitChoice(2);
        }
    });
});
var count = 0;
function SubmitChoice(choice) {        
    win = $("#opt-"+choice);
    if (choice == 1) {
        lose = $("#opt-2");
    } else {
        lose = $('#opt-1');
    }

    $(win).css("background-color","green");    
    $(lose).css("background-color","#c20");
    
    $('.options').fadeTo('fast',0,function(){
        $('.option').css("background-color","#444");
        $.post("vote.php",
            {winner: $(win).text(),
             loser: $(lose).text()
            },
            function(data){
                $('.options').fadeTo('slow',1);
                $('#opt-1').text(data.next[0]);
                $('#opt-2').text(data.next[1]);
                count++;
                $('#count').text(count);
                updateStats();
            }, "json"
        );
    });
    updateStats();
};
    

function updateStats() {
    $.getJSON('stats.php?raw', function(data) {
        $('#chart').sparkline(data, {type:'bar', barColor:'#444444'});
    });
};
