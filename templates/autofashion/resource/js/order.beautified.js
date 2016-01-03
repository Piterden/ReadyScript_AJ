$(function() {
    $('select[name="addr_country_id"]').change(function() {
        var e = $('select[name="addr_region_id"]').attr("disabled", "disabled");
        $.getJSON($(this).data("regionUrl"), {
            parent: $(this).val()
        }, function(s) {
            if (s.list.length > 0) {
                for (e.html(""), i = 0; i < s.list.length; i++) {
                    var t = $('<option value="' + s.list[i].key + '">' + s.list[i].value + "</option>");
                    e.append(t);
                }
                e.removeAttr("disabled"), $("#region-input").val("").hide(), $("#region-select").show();
            } else $("#region-input").show(), $("#region-select").hide();
        });
    }), $("#sd_region").on("change", function() {
        var e = $(this).children(":selected").text();
        $("#sd_region_name").val(e);
    }), $('input[name="use_addr"]').click(function() {
        "0" == this.value ? ($(".newAddress").removeClass("hide"), $(".sdAddress").addClass("hide")) : "-1" == this.value ? ($(".sdAddress").removeClass("hide"), 
        $(".newAddress").addClass("hide")) : ($(".newAddress").addClass("hide"), $(".sdAddress").addClass("hide"));
    }), $(".userType input").click(function() {
        $(this).closest(".checkoutBox").removeClass("person company user").addClass($(this).val()), 
        $("#doAuth").attr("disabled", "user" != $(this).val());
    }), $('input[name="reg_autologin"]').change(function() {
        $("#manual-login").toggle(!this.checked);
    }), $(".toggleView").on("click", function(e) {
        e.preventDefault();
        var s = $(this).attr("id");
        "hasAccount" == s ? ($('[name="user_type"]').removeAttr("checked").filter("#type-account").click(), 
        $("#doAuth").attr("disabled", !1)) : "contactData" == s && ($('[name="user_type"]').removeAttr("checked").filter("#type-user").click(), 
        $("#doAuth").attr("disabled", !0)), $(this).closest(".row").addClass("hide"), $("." + s).removeClass("hide");
    }), $(".lastAddress .deleteAddress").on("click", function() {
        var e = $(this).closest(".tableRow");
        return e.css("opacity", "0.5"), $.get($(this).attr("href") ? $(this).attr("href") : $(this).data("href"), function(s) {
            e.css("opacity", "1"), s.success && (e.remove(), $(".lastAddress input[name='use_addr']:eq(0)").click());
        }, "json"), !1;
    }), $(".deliveryItem").on("click", "input", function(e) {
        $(".deliveryItem .addressBlock").addClass("hide"), $(this).parents(".deliveryItem").find(".addressBlock").removeClass("hide");
    });
});