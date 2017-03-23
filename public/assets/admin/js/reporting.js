$(document).ready(function () {
    var getUrl = window.location,
        baseUrl = getUrl.protocol + "//" + getUrl.host + "/";

    var delays = ['300', '600', '900', '1200'];

    var dataSource, filtering;

    // dataSource = function(options, callback){
    //     var items = filtering(options);
    //     var resp = {
    //         count: items.length,
    //         items: [],
    //         page: options.pageIndex,
    //         pages: Math.ceil(items.length/(options.pageSize || 50))
    //     };
    //     var i, items, l;
    //
    //     i = options.pageIndex * (options.pageSize || 50);
    //     l = i + (options.pageSize || 50);
    //     l = (l <= resp.count) ? l : resp.count;
    //     resp.start = i + 1;
    //     resp.end = l;
    //
    //     if(options.view==='list' || options.view==='thumbnail'){
    //         if(options.view==='list'){
    //             resp.columns = columns;
    //             for(i; i<l; i++){
    //                 resp.items.push(items[i]);
    //             }
    //         }else{
    //             for(i; i<l; i++){
    //                 resp.items.push({
    //                     name: items[i].name,
    //                     src: items[i].ThumbnailImage
    //                 });
    //             }
    //         }
    //
    //         setTimeout(function(){
    //             callback(resp);
    //         }, delays[Math.floor(Math.random() * 4)]);
    //     }
    // };

    // filtering = function(options){
    //     var items = $.extend([], products);
    //     var search;
    //     if(options.filter.value!=='all'){
    //         items = $.grep(items, function(item){
    //             return (item.type.search(options.filter.value)>=0);
    //         });
    //     }
    //     if(options.search){
    //         search = options.search.toLowerCase();
    //         items = $.grep(items, function(item){
    //             return (
    //                 (item.facultyCode.toLowerCase().search(options.search)>=0) ||
    //                 (item.name.toLowerCase().search(options.search)>=0) ||
    //                 (item.available.toLowerCase().search(options.search)>=0) ||
    //                 (item.price.toLowerCase().search(options.search)>=0) ||
    //                 (item.itemCondition.toLowerCase().search(options.search)>=0) ||
    //                 (item.sold.toLowerCase().search(options.search)>=0) ||
    //                 (item.review.toLowerCase().search(options.search)>=0) ||
    //                 (item.type.toLowerCase().search(options.search)>=0)
    //             );
    //         });
    //     }
    //     if(options.sortProperty){
    //         items = $.grep(items, function(item){
    //             if(options.sortProperty==='id' || options.sortProperty==='height' || options.sortProperty==='weight'){
    //                 return parseFloat(item[options.sortProperty]);
    //             }else{
    //                 return item[options.sortProperty];
    //             }
    //         });
    //         if(options.sortDirection==='desc'){
    //             items.reverse();
    //         }
    //     }
    //
    //     return items;
    // };

    function customDataSource(options, callback) {
        // set options
        var pageIndex = options.pageIndex;
        var pageSize = options.pageSize;
        var options_ajax = {
            pageIndex: pageIndex,
            pageSize: pageSize,
            sortDirection: options.sortDirection,
            sortBy: options.sortProperty,
            filterBy: options.filter.value || '',
            searchBy: options.search || '',
            level: $("select[name='input[level]']").val(),
            faculty_code: $("select[name='input[faculty]']").val(),
            study_program: $("select[name='input[study_program]']").val(),
            lecturer: $("select[name='input[lecturer]']").val(),
            output_code: $("select[name='input[output]']").val(),
            year: $("input[name='input[year]']").val(),
        };

        // call API, posting options
        $.ajax({
            type: 'get',
            url: '/ajax/outputs/get-count',
            data: options_ajax
        })
            .done(function(data) {
                console.log(data);
                var items = data.items;
                var totalItems = data.total;
                var totalPages = Math.ceil(totalItems / pageSize);
                var startIndex = (pageIndex * pageSize) + 1;
                var endIndex = (startIndex + pageSize) - 1;

                if(endIndex > items.length) {
                    endIndex = items.length;
                }

                // configure datasource
                var dataSource = {
                    page: pageIndex,
                    pages: totalPages,
                    count: totalItems,
                    start: startIndex,
                    end: endIndex,
                    columns: data.columns,
                    items: items
                };

                // invoke callback to render repeater
                callback(dataSource);
            });
    }

    $('#count-output-repeater').repeater({
        dataSource: customDataSource
    });

    $("button[name='filter-report']").click(function (e) {
        console.log('test');
        $('#count-output-repeater').repeater('render');
    });
});