$(document).ready(function () {
    var getUrl = window.location,
        baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
    // baseUrl = baseUrl + "lppm_usu/public";

    //Handle search member
    var autocomp_opt = {
        source: function (request, response) {
            $.ajax({
                url: baseUrl + '/ajax/members/search',
                dataType: "json",
                data: {
                    key_input: request.term
                },
                success: function (data) {
                    console.log(data);
                    var transformed = $.map(data, function (el) {
                        return {
                            label: el.full_name,
                            id: el.employee_card_serial_number
                        };
                    });
                    response(transformed);
                }
            });
        },
        select: function (event, ui) {
            $(this).siblings(".input-value").val(ui.item.id);
            $('.input-member').trigger('change');
        }
    };

    $('.input-member').autocomplete(autocomp_opt);
    //End Handle search member

    //Handle search reviewer
    var autocomp_reviewer = {
        source: function (request, response) {
            $.ajax({
                url: baseUrl + '/ajax/reviewers/search',
                dataType: "json",
                data: {
                    key_input: request.term
                },
                success: function (data) {
                    console.log(data);
                    var transformed = $.map(data, function (el) {
                        return {
                            label: el.full_name,
                            id: el.nidn
                        };
                    });
                    response(transformed);
                    console.log("nidn"+id);
                }
            });
        },
        select: function (event, ui) {
            $(this).siblings(".input-reviewer-value").val(ui.item.id);
            $('.input-reviewer-auto').trigger('change');
        }
    };

    $('.input-reviewer-auto').autocomplete(autocomp_reviewer);
    //End Handle search reviewer

    //Handle add new reviewer
    $(".add-reviewer-button").click(function (e) {
        e.preventDefault();
        countChild = $(".reviewer-wrapper div.form-group").length;
        x = countChild;
        if (x < 10) { //max input box allowed
            var reviewer_clone = $(".reviewer-wrapper").find("div.form-group:first").clone();
            reviewer_clone.find("select").remove();
            reviewer_clone.find(".chosen-container").remove();
            reviewer_clone = reviewer_clone.appendTo(".reviewer-wrapper");

            var reviewer_option = $(".reviewer-wrapper").find("div.form-group:first").find("select.chosen-select").find("option").clone();
            var reviewer_select = $("<select name='nidn[]' class='chosen-select'>").appendTo(reviewer_clone.find('.chosen-select-container'));
            reviewer_option.each(function (index) {
                reviewer_select.append($("<option>").attr('value', reviewer_option[index].value).text(this.text))
            });
            $(".chosen-select").chosen();
        }
    });

    $('.reviewer-wrapper').on("click", ".remove_field", function (e) { //user click on remove text
        e.preventDefault();
        countChild = $(".reviewer-wrapper div.form-group").length;
        x = countChild;
        if (x > 1) {
            $(this).parents('div.form-group').remove();
            x--;
        }
    })
    //End Handle add new reviewer

    //Handle add new member
    $(".add-member-button").click(function (e) {
        e.preventDefault();
        countChild = $(".member-wrapper div.clone-member-wrapper").length;
        x = countChild;
        if (x < 10) { //max input box allowed
            x++; //text box increment

            //Get latest ID number of external indicator
            var id = 0;
            for (var i = 0; i < 100; i++) {
                if ($("input#external" + i).length) {
                    id = i;
                }
            }
            id++;
            //End Get

            var clone_member = $(".member-wrapper").find(".clone-member-wrapper:last").clone();
            var is_external = clone_member.find("input[name^=external]").length;
            if(is_external)
            {
                var key = clone_member.find("input[name^=external]").attr("name").substring(9,10);
                var idx = clone_member.find("input[name^=external]").attr("name").substring(12,13);
                idx++;
                clone_member.find("input[name^=external]").attr("name", "external[" + key + "][" + idx + "]");
                clone_member.find("input[name^=external]").attr("id", "external[" + key + "][" + idx + "]");
                clone_member.find("input[name^=external]").prop("checked", false);
                clone_member.find("label[for^=external]").attr("for", "external[" + key + "][" + idx + "]");
            }
            clone_member.find("input[name^=member_nidn]").val("");
            clone_member.find("input[name^=member_display]").val("");
            clone_member.find("input[name^=member_areas_of_expertise]").val("");
            clone_member.find("input[name^=external]").val("");
            clone_member.find(".external-member-wrapper").hide();
            clone_member.find(".internal-member-wrapper").show();

            $(".member-wrapper").append(clone_member); //add input box
            $('.input-member').autocomplete(autocomp_opt);
            BlankonApp.handleSound();
        }
    });

    $('.member-wrapper').on("click", ".remove_field", function (e) { //user click on remove text
        e.preventDefault();
        countChild = $(".member-wrapper div.clone-member-wrapper").length;
        x = countChild;
        if (x > 1) {
            $(this).parents('div.clone-member-wrapper').remove();
            x--;
        }
    })
    //End Handle add new member

    // var function_add_output_member = $(".add-output-member-button").click(function(e){
    $(document).on("click", ".add-output-member-button", function(e){
        e.preventDefault();
        var clone_member = $(this).parent().parent().find(".member-wrapper").find(".clone-member-wrapper:last").clone();
        var key = clone_member.find("input[name^=is_external]").attr("name").substring(12,13);
        var idx = clone_member.find("input[name^=is_external]").attr("name").substring(15,16);
        idx++;
        clone_member.find("input[name^=is_external]").attr("name", "is_external[" + key + "][" + idx + "]");
        clone_member.find("input[name^=is_external]").attr("id", "is_external[" + key + "][" + idx + "]");
        clone_member.find("input[name^=is_external]").prop("checked", false);
        clone_member.find("label[for^=is_external]").attr("for", "is_external[" + key + "][" + idx + "]");
        clone_member.find("input[name^=nidn]").val("");
        clone_member.find("input[name^=external]").val("");
        clone_member.find(".external-member-wrapper").hide();
        clone_member.find(".internal-member-wrapper").show();
        $(this).parent().parent().find(".member-wrapper").append(clone_member);
        $('.input-member').autocomplete(autocomp_opt);
        BlankonApp.handleSound();
    });

    $(document).on("click", ".add-research-general-button", function (e) {
        e.preventDefault();
        countChild = $(".research-general-wrapper div.form-group").length;
        x = countChild;
        if (x < 20) { //max input box allowed
            x++; //text box increment
            var research_clone = $('.research-general-wrapper').find('div.form-group:last').clone();
            var idx = research_clone.find('input[name^=status]:first').attr("name").substring(7,8);
            idx++;
            research_clone.find('input[name^=status]').attr("name", "status[" + idx + "]");
            research_clone.find('input[id^=radio-draft]').attr("id", "radio-draft[" + idx + "]");
            research_clone.find('label[for^=radio-draft]').attr("for", "radio-draft[" + idx + "]");
            research_clone.find('input[id^=radio-submitted]').attr("id", "radio-submitted[" + idx + "]");
            research_clone.find('label[for^=radio-submitted]').attr("for", "radio-submitted[" + idx + "]");
            research_clone.find('input[id^=radio-accepted]').attr("id", "radio-accepted[" + idx + "]");
            research_clone.find('label[for^=radio-accepted]').attr("for", "radio-accepted[" + idx + "]");
            research_clone.find('input[id^=radio-publish]').attr("id", "radio-publish[" + idx + "]");
            research_clone.find('label[for^=radio-publish]').attr("for", "radio-publish[" + idx + "]");
            // research_clone.find('.output-year-wrapper').children().remove();
            // research_clone.find('.output-year-wrapper').append('<div class="clearfix"></div> <label for="year[]" class="control-label col-sm-4 col-md-3">Tahun Luaran</label> <div class="col-sm-6 mb-10"> <input name="year[]" class="form-control input-sm" type="text" maxlength="4" value=""> </div>');
            // research_clone.find('.output-year-wrapper').find(':input').inputmask({'alias': 'decimal', 'rightAlign': false});
            research_clone.find('input[name^=year]').val("");
            research_clone.find('input[name^=output_description]').val("");
            research_clone.find('input[name^=url_address]').val("");
            research_clone.find('.remove-output-wrapper').remove();
            research_clone.find('.download-output-wrapper').remove();

            var member_clone = research_clone.find(".clone-member-wrapper:first").clone();
            member_clone.find("input.external-output-checkbox").prop("checked", false);
            member_clone.find("input[name^=nidn]").val("");
            member_clone.find("input[name^=external]").val("");
            research_clone.find(".clone-member-wrapper").each(function(){
                $(this).remove();
            });
            member_clone.find("input[name^=is_external]").attr("name", "is_external[" + idx + "][" + "0" + "]");
            member_clone.find("input[name^=is_external]").attr("id", "is_external[" + idx + "][" + "0" + "]");
            member_clone.find("input[name^=is_external]").prop("checked", false);
            member_clone.find("label[for^=is_external]").attr("for", "is_external[" + idx + "][" + "0" + "]");
            member_clone.find("input[name^=nidn]").each(function() {
                var ch = $(this).attr("name").substring(4,5);
                if(ch == "["){
                    $(this).attr("name", "nidn[" + idx + "][]");
                }else{
                    $(this).attr("name", "nidn_display[" + idx + "][]");
                }
            });
            member_clone.find("input[name^=external]").attr("name", "external[" + idx + "][]");
            member_clone.find(".external-member-wrapper").hide();
            member_clone.find(".internal-member-wrapper").show();
            research_clone.find(".member-wrapper").append(member_clone);

            $('.research-general-wrapper').append(research_clone);

            if(! research_clone.find(".remove-output-button-wrapper").length)
            {
                var remove_output_button = '<div class="remove-output-button-wrapper"><div class="clearfix"></div><label class="control-label col-sm-4 col-md-3">Hapus Luaran</label><div class="col-sm-1"><a href="#" class="remove_field btn btn-sm btn-danger btn-stroke"><i class="fa fa-minus"></i></a></div></div>';
                $('.research-general-wrapper').find('div.form-group:last').append(remove_output_button);
            }

            $('.input-member').autocomplete(autocomp_opt);
            BlankonApp.handleSound();
            BlankonFormAdvanced.inputMask();
        }
    });

    $('.research-general-wrapper').on("click", ".remove_field", function (e) { //user click on remove text
        e.preventDefault();
        countChild = $(".research-general-wrapper div.form-group").length;
        x = countChild;
        if (x > 1) {
            $(this).parents('div.form-group').remove();
            x--;
        }
    })

    $(document).on("click", ".remove-output-member", function (e) { //user click on remove text
        e.preventDefault();
        countChild = $(this).parents(".member-wrapper").find(".clone-member-wrapper").length;
        if (countChild > 1) {
            $(this).parents('div.clone-member-wrapper').remove();
            x--;
        }
    })
});