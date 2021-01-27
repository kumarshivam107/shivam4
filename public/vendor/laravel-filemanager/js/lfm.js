(function( $ ){

    $.fn.filemanager = function(type, options) {
        type = type || 'file';

        $(this).on('click', function(e) {
            var route_prefix = (options && options.prefix) ? options.prefix : '/laravel-filemanager';
            localStorage.setItem('type', type);
            localStorage.setItem('target_container', $(this).closest('.lfm-container').attr('id'));
            localStorage.setItem('target_input', $(this).data('input'));
            localStorage.setItem('target_preview', $(this).data('preview'));
            window.open(route_prefix + '?type=' + type, 'FileManager', 'width=900,height=600');
            window.SetUrl = function (url, file_path) {
                var target_container = $('#' + localStorage.getItem('target_container'));

                //set the value of the desired input to image url
                var target_input = target_container.find('.' + localStorage.getItem('target_input'));
                target_input.val(file_path).trigger('change');

                type = localStorage.getItem('type');
                var target_preview = target_container.find('.' + localStorage.getItem('target_preview'));
                if(type != 'file'){
                    //set or change the preview image src
                    target_preview.attr('src', url).trigger('change');
                }else{
                    var filename = url.substring(url.lastIndexOf('/')+1);
                    filename  = (filename.length > 5)? filename.substring(0, 3) + '...+' + filename.split('.').pop() : filename;
                    url = 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=' + filename.toUpperCase();

                    target_preview.attr('src', url).trigger('change');
                }
            };
            return false;
        });
    }

})(jQuery);
