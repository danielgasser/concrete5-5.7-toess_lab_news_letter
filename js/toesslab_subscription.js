/**
 * Created by daniel on 25.05.17.
 */
console.log('hello world');
var fillList = function (data) {
    var html = '';
    $.each(data, function (i, n) {
        html += '<div class="row">'
            + '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 checkbox form-group">'
            + '<label>'
            + '<input type="checkbox" id="emailIDs_' + n.uID + '" name="emailIDs[]" class="ccm-input-checkbox" value="' + n.uID + '" checked="checked">'
            + n.uEmail
            + '</label>'
            + '</div>'
        + '</div>';
    });
    $('#fillList').html(html);
};
$(document).ready(function () {

});

$(document).on('click', '[id$="sub_members"]', function (e) {
   e.preventDefault();
   var id = $(this).attr('id'),
   emails = $('#mailFilter').val().split('\n');
   console.log(id, emails);
   $.ajax({
       url: window.url,
       method: 'POST',
       data: {
           emailIDs: JSON.stringify(emails),
           sub: (id.indexOf('un') > -1)
       },
       success: function (data) {
           fillList($.parseJSON(data))
       }
   })
});
