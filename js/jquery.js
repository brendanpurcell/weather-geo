$(".sidebar").hover(function() {
$(".tiptext").hover(function() {
    $(this).children(".description").show();
}).mouseout(function() {
    $(this).children(".description").hide();
});
});
//subscribe equivalent
