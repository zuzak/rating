$(document).ready(function() {
    $('.option').click(function(){
        $('.option').css("background-color","#c20");
        $(this).css("background-color","green");
        win = this;
        $('.options').fadeTo('slow',0,function(){
            $('.option').css("background-color","#444");
            if ($(win).attr("id") == "opt-1"){
                lose = $('#opt-2');
            } else {
                lose = $('#opt-1');
            }
            $.post("vote.php",
                {winner: $(win).text(),
                 loser: $(lose).text()
                },
                function(data){
                    $('.options').fadeTo('slow',1);
                    $('#opt-1').text(data.next[0]);
                    $('#opt-2').text(data.next[1]);
                }, "json"
            );
        });
    });
});
