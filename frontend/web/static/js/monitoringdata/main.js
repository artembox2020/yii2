$(function() {
    $(".burger-menu").on('click', function() {
        $(".mobile-menu").toggle(2000);       
    });

    $("#technical").on("click", function() {
        $("#tab-tech").css("display", "block");
        $("#tab-gen").css("display", "none");
        $("#tab-fin").css("display", "none");
        $("a#technical").addClass("active");
        $("a#general").removeClass("active");
        $("a#finance").removeClass("active");
        
    });

    $("#finance").on("click", function() {        
        $("#tab-fin").css("display", "block");
        $("#tab-tech").css("display", "none");
        $("#tab-gen").css("display", "none");
        $("a#technical").removeClass("active");
        $("a#general").removeClass("active");
        $("a#finance").addClass("active");
    });

    $("#general").on("click", function() {        
        $("#tab-gen").css("display", "block");
        $("#tab-tech, #tab-fin").css("display", "none");
        $("#tab-fin").css("display", "none");       
        $("a#technical").removeClass("active");
        $("a#general").addClass("active");
        $("a#finance").removeClass("active");
    });

    
});