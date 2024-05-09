(function ($) {
    $.fn.makeVote = function () {

        $(this).click(function () {	
               
            var caption = $(this).val();
            var cnd_id = $(this).data('pid');
            var action = (caption == "Vote") ? "add" : "rem";
            var dataString = 'candidate_id=' + cnd_id + '&action=' + action;
            $.ajax({
              type: "POST",
              url: "update-vote.php",
              data: dataString,
              done: function(){
              }
            });
            // change text and color 
            var nname = '#nvote';
            var sname = '#selected';
            var nvote = parseInt($(nname).val());
            var sel = parseInt($(sname).val());

            if (action == "add") {
                if (sel < nvote) {
                    $(this).addClass('btn-warning').removeClass('btn-success ');
                    $(this).val("Selected");
                    $(sname).val(sel + 1);
                }
                else {
                    alert("You have already selected " + sel + 
                        " candidate(s). If you want to change your vote, please deselect a candidate first.");
                }
            }
            else {
                $(this).removeClass('btn-warning').addClass('btn-success ');
                $(this).val("Vote");
                $(sname).val(sel - 1);
            }

          return false;
               
        });

    };
})(jQuery);