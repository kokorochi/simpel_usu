/**
 * Created by Surya on 24/10/2016.
 */
$(document).ready(function () {
    var getUrl = window.location,
        baseUrl = getUrl.protocol + "//" + getUrl.host;// + "/";
    // baseUrl = baseUrl + "lppm_usu/public";

    // Handle Additional Fields For Appraisal
    var max_fields = 20; //maximum input boxes allowed
    var wrapper = $(".input_fields_wrap"); //Fields wrapper
    var add_button = $(".add_field_button"); //Add button ID
    var countChild;
    var x; //initlal text box count

    $(add_button).click(function (e) { //on add input button click
        e.preventDefault();
        countChild = $(".input_fields_wrap div.form-group").length;
        x = countChild - 1;
        if (x < max_fields) { //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="form-group"><label for="aspect[]" class="col-sm-2 control-label">Deskripsi Aspek</label> <div class="col-sm-6"> <input name="aspect[]" class="form-control input-sm" type="text"> </div> <label for="quality[]" class="col-sm-1 control-label">Bobot</label> <div class="col-sm-2"> <input name="quality[]" class="form-control input-sm" type="text"> </div> <div class="col-sm-1"> <a href="#" class="remove_field btn btn-sm btn-danger btn-stroke"> <i class="fa fa-minus"></i> </a> </div> </div><!-- /.form-group -->'); //add input box
        }
    });

    $(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
        e.preventDefault();
        countChild = $(".input_fields_wrap div.form-group").length;
        x = countChild - 1;
        if (x > 1) {
            $(this).parents('div.form-group').remove();
            x--;
        }
    })
    // End Handle Additional Fields For Appraisal

    //Handle Add fields for dedication partner
    $(".add-partner-button").click(function (e) {
        e.preventDefault();
        countChild = $(".partner-wrapper div.form-group").length;
        x = countChild;
        if (x < max_fields) { //max input box allowed
            x++; //text box increment
            $(".partner-wrapper").append('<div class="form-group"><label for="partner_name[]" class="col-sm-3 control-label">Nama Mitra</label> <div class="col-sm-7"> <input name="partner_name[]" class="form-control input-sm mb-10" type="text"> </div><!-- /.col-sm-7 --> <label for="partner_territory[]" class="col-sm-3 control-label">Wilayah Mitra (Desa/Kecamatan)</label> <div class="col-sm-7"> <input name="partner_territory[]" class="form-control input-sm mb-10" type="text"> </div><!-- /.col-sm-7 --> <label for="partner_city[]" class="col-sm-3 control-label">Kabupaten/Kota</label> <div class="col-sm-7"> <input name="partner_city[]" class="form-control input-sm mb-10" type="text"> </div><!-- /.col-sm-7 --> <label for="partner_province[]" class="col-sm-3 control-label">Provinsi</label> <div class="col-sm-7"> <input name="partner_province[]" class="form-control input-sm mb-10" type="text"> </div><!-- /.col-sm-7 --> <label for="partner_distance[]" class="col-sm-3 control-label">Jarak PT ke lokasi mitra (KM)</label> <div class="col-sm-7"> <input name="partner_distance[]" class="form-control input-sm mb-10" type="text"> </div><!-- /.col-sm-7 --> <div class="clearfix"></div> <label class="control-label col-sm-4 col-md-3">Unggah Surat Kesediaan Kerjasama</label> <div class="col-sm-7"> <div class="fileinput fileinput-new input-group" data-provides="fileinput"> <div class="form-control" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span> </div> <span class="input-group-addon btn btn-success btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span> <input type="file" name="file_partner_contract[]" value=""> </span> <a href="#" class="input-group-addon btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a> </div> </div> <div class="col-sm-offset-3 col-sm-7"><a href="#" class="remove_field btn btn-sm btn-danger btn-stroke btn-slideright"> <i class="fa fa-minus"></i> </a> </div></div><!-- /.form-group -->'); //add input box
        }
    });


    $('.partner-wrapper').on("click", ".remove_field", function (e) { //user click on remove text
        e.preventDefault();
        countChild = $(".partner-wrapper div.form-group").length;
        x = countChild;
        if (x > 1) {
            $(this).parents('div.form-group').remove();
            x--;
        }
    })
    //End Handle Add fields for dedication partner

    // Handle Delete Confirmation Modal
    $(".modal_delete").on('click', function () {
        var id = $(this).data('id');
        removeForm = $("form.delete_action");
        removeForm.attr('action', removeForm.attr('action').replace(/actionid/, id));
    });
    // End Handle Delete Confirmation Modal

    if ($("#checkbox-primary1").is(":checked")) {
        $("#own-wrapper").show();
        $("#scheme-wrapper").hide();
    } else {
        $("#own-wrapper").hide();
        $("#scheme-wrapper").show();
    }
    $("#checkbox-primary1").on("click", function () {
        if ($("#checkbox-primary1").is(":checked")) {
            $("#own-wrapper").show("");
            $("#scheme-wrapper").hide("");
        } else {
            $("#own-wrapper").hide("");
            $("#scheme-wrapper").show("");
        }
    });

    for (var i = 0; i < 50; i++) {
        if ($("input#external" + i).is(":checked")) {
            // console.log($(this).closest('.form-group').find('.external-member-wrapper').length);
            $("input#external" + i).closest('.form-group').find('.external-member-wrapper').show();
            $("input#external" + i).closest('.form-group').find('.internal-member-wrapper').hide();
        } else {
            $("input#external" + i).closest('.form-group').find('.external-member-wrapper').hide();
            $("input#external" + i).closest('.form-group').find('.internal-member-wrapper').show();
        }
    }

    $("input[name^=is_external]").each(function(){
        if($(this).is(":checked")){
            $(this).closest('.clone-member-wrapper').find('.external-member-wrapper').show();
            $(this).closest('.clone-member-wrapper').find('.internal-member-wrapper').hide();
        }else{
            $(this).closest('.clone-member-wrapper').find('.external-member-wrapper').hide();
            $(this).closest('.clone-member-wrapper').find('.internal-member-wrapper').show();
        }
    });

    $(document).on("click", "input.external-checkbox", function () {
        if ($(this).is(":checked")) {
            $(this).closest('.form-group').find('.external-member-wrapper').show();
            $(this).closest('.form-group').find('.internal-member-wrapper').hide();
        } else {
            $(this).closest('.form-group').find('.external-member-wrapper').hide();
            $(this).closest('.form-group').find('.internal-member-wrapper').show();
        }
    });

    $(document).on("click", "input.external-output-checkbox", function () {
        if ($(this).is(":checked")) {
            $(this).closest('.clone-member-wrapper').find('.external-member-wrapper').show();
            $(this).closest('.clone-member-wrapper').find('.internal-member-wrapper').hide();
        } else {
            $(this).closest('.clone-member-wrapper').find('.external-member-wrapper').hide();
            $(this).closest('.clone-member-wrapper').find('.internal-member-wrapper').show();
        }
    });

    $("select[name='period_id']").change(function () {
        $.get(baseUrl + '/ajax/periods/get', {period_id: $(this).val()}, function (data) {
            // data = JSON.parse(data);
            $.each(data, function (key, value) {
                if (key == 'annotation') {
                    $("textarea[name='" + key + "']").val(data[key]);
                } else {
                    $("input[name='" + key + "']").val(data[key]);
                }
            });
        })
    });

    $(".input-reviewer").on('change', function () {
        $.get(baseUrl + '/ajax/members/lecturerNIDN', {key_input: $('.input-value').val()}, function (data) {
            $.each(data, function (key, value) {
                $("input[name='" + key + "']").val(data[key]);
            });
        })
    });

    $(".input-score").change(function () {
        if ($.isNumeric($(this).val())) {
            var quality = $(this).parent().parent().parent().find("input[name='quality[]']").val();
            $(this).parent().parent().parent().find(".output-score").val($(this).val() * quality);
        }
    });

    if ($("#radio-no").is(":checked")) {
        $("#revision-text-wrapper").show();
    } else {
        $("#revision-text-wrapper").hide();
    }
    $("input[name='is_approved']").on("change", function () {
        if ($("#radio-no").is(":checked")) {
            $("#revision-text-wrapper").show("");
        } else {
            $("#revision-text-wrapper").hide("");
        }
    });

    $("form.submit-form").submit(function (e) {
        var form = $(this);
        $(this).find('button[type="submit"]').each(function (index) {
            // Create a disabled clone of the submit button
            $(this).clone(false).removeAttr('id').prop('disabled', true).insertBefore($(this));

            // Hide the actual submit button and move it to the beginning of the form
            $(this).hide();
            form.prepend($(this));
        });
    });

    if($(".chosen-select-level").length){
        $('.chosen-select-level').chosen();
    }

    if($(".chosen-select-faculty").length){
        $('.chosen-select-faculty').chosen();
        $.get(baseUrl + '/ajax/faculties/get', function (data) {
            $('.chosen-select-faculty').empty();
            var selected_faculty;
            $.each(data['data'], function (key, value) {
                selected_faculty = value.faculty_code;
                $('.chosen-select-faculty').append('<option value="' + value.faculty_code + '">' + value.faculty_name + '</option>');
            });
            $('.chosen-select-faculty').trigger('chosen:updated');
            getAndSetChosenStudyProgram(selected_faculty);
        });
    }

    $(document).on('change', '.chosen-select-faculty', function(e){
        e.preventDefault();
        getAndSetChosenStudyProgram($(this).val());
    });

    if($('.chosen-select-lecturer').length){
        $('.chosen-select-lecturer').chosen();
    }

    $(document).on('change', '.chosen-select-study-program', function(e){
        e.preventDefault();
        getAndSetChosenLecturer($(this).val());
    });

    if($('.chosen-select-output').length){
        getAndSetChosenOutput();
    }

    showHideFilterOutputReport(1); //First inititate to level 1

    $(document).on('change', '.chosen-select-level', function(e){
        e.preventDefault();
        showHideFilterOutputReport($(this).val());
    })

    $('form.output-filter').click(function(){
        $.get(baseUrl + '/ajax/outputs/get-count',{
            'level': $('.chosen-select-level').val(),
            'faculty_code[]': $('.chosen-select-faculty').val(),
            'study_program[]': $('.chosen-select-study-program').val(),
            'lecturer_nidn[]': $('.chosen-select-lecturer').val(),
            'output_code[]': $('.chosen-select-output').val(),
            'years[]': $('input[name="input[year]"]').val()
        }, function (data) {
            console.log(data);
        });
    });

    function showHideFilterOutputReport(p1){
        if(p1 == 1){
            $('.faculty-wrapper select').prop('disabled', true).trigger('chosen:updated');
            $('.study-program-wrapper select').prop('disabled', true).trigger('chosen:updated');
            $('.lecturer-wrapper select').prop('disabled', true).trigger('chosen:updated');
        }else if(p1 == 2){
            $('.faculty-wrapper select').prop('disabled', false).trigger('chosen:updated');
            $('.study-program-wrapper select').prop('disabled', true).trigger('chosen:updated');
            $('.lecturer-wrapper select').prop('disabled', true).trigger('chosen:updated');
        }else if(p1 == 3){
            $('.faculty-wrapper select').prop('disabled', false).trigger('chosen:updated');
            $('.study-program-wrapper select').prop('disabled', false).trigger('chosen:updated');
            $('.lecturer-wrapper select').prop('disabled', true).trigger('chosen:updated');
        }else if(p1 == 4){
            $('.faculty-wrapper select').prop('disabled', false).trigger('chosen:updated');
            $('.study-program-wrapper select').prop('disabled', false).trigger('chosen:updated');
            $('.lecturer-wrapper select').prop('disabled', false).trigger('chosen:updated');
        }
    }

    function getAndSetChosenStudyProgram(p1){
        $('.chosen-select-study-program').chosen();
        $.get(baseUrl + '/ajax/study-programs/get', {'faculty_code[]': p1}, function (data) {
            $('.chosen-select-study-program').empty();
            var selected_val;
            $.each(data['data'], function (key, value) {
                if(selected_val == null) selected_val = value.study_program;
                $('.chosen-select-study-program').append('<option value="' + value.study_program + '">' + value.study_program + '</option>');
            });
            $('.chosen-select-study-program').trigger('chosen:updated');
            getAndSetChosenLecturer(selected_val);
        })
    }

    function getAndSetChosenLecturer(p1){
        $('.chosen-select-lecturer').chosen();
        $.get(baseUrl + '/ajax/lecturers/get', {'study_program[]': p1}, function (data) {
            $('.chosen-select-lecturer').empty();
            $.each(data['data'], function (key, value) {
                $('.chosen-select-lecturer').append('<option value="' + value.employee_card_serial_number + '">' + value.employee_card_serial_number + ': ' + value.full_name + '</option>');
            });
            $('.chosen-select-lecturer').trigger('chosen:updated');
        })
    }

    function getAndSetChosenOutput(){
        $('.chosen-select-output').chosen();
        $.get(baseUrl + '/ajax/outputs/get', function (data) {
            $('.chosen-select-output').empty();
            $.each(data['data'], function (key, value) {
                $('.chosen-select-output').append('<option value="' + value.id + '">' + value.output_code + ': ' + value.output_name + '</option>');
            });
            $('.chosen-select-output').trigger('chosen:updated');
        })
    }
});