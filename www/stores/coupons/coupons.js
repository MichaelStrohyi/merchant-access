window.openImageWindow = function (src) {
  var image = new Image();
  image.src = src;
  var width = image.width;
  var height = image.height;
  window.open(src,"Image","width=" + width + ",height=" + height);
}

window.removeImage = function (coupon_id, image_id, url) {
  $("#Image" + coupon_id).replaceWith('<div id="Image' + coupon_id + '"><img height="50px" src="' + url + '?id=1"><button type="button" onclick="returnImage(' + "'" + coupon_id + "', '" + image_id + "', '" + url + "'" + ');">Return Image</button><input type="hidden" class="form-control" id="removeImage' + coupon_id + '" name="coupons[' + coupon_id + '][removeImage]"></div>');
};

window.clearImage = function(coupon_id) {
  $("#inputImage" + coupon_id).val("");
};

window.returnImage = function (coupon_id, image_id, url) {
  $("#Image" + coupon_id).replaceWith('<div id="Image' + coupon_id + '"><img width="50px" src="' + url + '?id=' + image_id + '" onclick="openImageWindow(this.src);"><button type="button" onclick="removeImage(' + "'" + coupon_id + "', '" + image_id + "', '" + url + "'"  +  ');">Remove</button></div>');
};

window.changeActivity = function (coupon_id) {
  if ($("#inputActivity" + coupon_id).val() == 1) {
    $("#inputActivity" + coupon_id).val('0');
    $("#activationLink" + coupon_id).text('Activate');
    $("#tr" + coupon_id + "_1").css("background-color", '#ddd');
    $("#tr" + coupon_id + "_2").css("background-color", '#ddd');
    return;
  }
  
  $("#inputActivity" + coupon_id).val('1');
  $("#activationLink" + coupon_id).text('Deactivate');
  $("#tr" + coupon_id + "_1").css("background-color", '');
  $("#tr" + coupon_id + "_2").css("background-color", '');
};

window.pageReset = function () { document.location.reload();}

window.removeCoupon = function (coupon_id) {
  var current_pos = + $("input[name='coupons[" + coupon_id + "][position]']").val();
  $("#tr" + coupon_id + "_1").remove();
  $("#tr" + coupon_id + "_2").remove();
  var r_coupons = $("#rCoupons" ).val();
  r_coupons = r_coupons == '' ? coupon_id : r_coupons + "," + coupon_id;
  $("#rCoupons" ).val(r_coupons);

  for (var i = current_pos * 2 - 1; i < $("#couponsTable tr").length; i = i + 2) {
    $("#couponsTable tr:eq(" + i + ") td:eq(1) input[class=inputPosition]").val((i + 1) / 2);
    $("#couponsTable tr:eq(" + i + ") td:eq(0)").text((i + 1) / 2);
  }
};

window.moveUp = function (coupon_id) {
  var current_pos = + $("input[name='coupons[" + coupon_id + "][position]']").val();
  if (current_pos == 1) { return; }

  var prev_pos = current_pos - 1;
  var prev_coupon_id = $("#couponsTable tr:eq(" + (prev_pos * 2 - 1) + ") td:eq(1) input[class=inputPosition]").attr('title');
  $("#tr" + prev_coupon_id + "_2").insertAfter($("#tr" + coupon_id + "_2"));
  $("#tr" + prev_coupon_id + "_1").insertAfter($("#tr" + coupon_id + "_2"));
  $("#inputPosition" + coupon_id).val(prev_pos);
  $("#inputPosition" + prev_coupon_id).val(current_pos);
  $("#tr" + coupon_id + "_1 td:eq(0)").text(prev_pos);
  $("#tr" + prev_coupon_id + "_1 td:eq(0)").text(current_pos);
};

window.moveDown = function (coupon_id) {
  var current_pos = + $("input[name='coupons[" + coupon_id + "][position]']").val();
  if (current_pos * 2 == $("#couponsTable tr").length - 1) { return; }

  var next_pos = current_pos + 1;
  var next_coupon_id = $("#couponsTable tr:eq(" + (next_pos * 2 - 1) + ") td:eq(1) input[class=inputPosition]").attr('title');
  $("#tr" + coupon_id + "_2").insertAfter($("#tr" + next_coupon_id + "_2"));
  $("#tr" + coupon_id + "_1").insertAfter($("#tr" + next_coupon_id + "_2"));
  $("#inputPosition" + coupon_id).val(next_pos);
  $("#inputPosition" + next_coupon_id).val(current_pos);
  $("#tr"  + coupon_id + "_1 td:eq(0)").text(next_pos);
  $("#tr"  + next_coupon_id + "_1 td:eq(0)").text(current_pos);
};

window.makeFirst = function (coupon_id) {
  var current_pos = + $("input[name='coupons[" + coupon_id + "][position]']").val();
  if (current_pos == 1) { return; }

  var first_coupon_id = $("#couponsTable tr:eq(1) td:eq(1) input[class=inputPosition]").attr('title');
  $("#tr" + coupon_id + "_1").insertBefore($("#tr" + first_coupon_id + "_1"));
  $("#tr" + coupon_id + "_2").insertBefore($("#tr" + first_coupon_id + "_1"));

  for (var i = 1; i <= current_pos * 2 - 1; i = i + 2) {
    $("#couponsTable tr:eq(" + i + ") td:eq(1) input[class=inputPosition]").val((i + 1) / 2);
    $("#couponsTable tr:eq(" + i + ") td:eq(0)").text((i + 1) / 2);
  }
};

window.makeLast = function (coupon_id) {
  var current_pos = + $("input[name='coupons[" + coupon_id + "][position]']").val();
  var last_row = $("#couponsTable tr").length - 1;
  if (current_pos * 2 == last_row) { return; }

  var last_coupon_id = $("#couponsTable tr:eq(" + (last_row - 1) + ") td:eq(1) input[class=inputPosition]").attr('title');
  $("#tr" + coupon_id + "_2").insertAfter($("#tr" + last_coupon_id + "_2"));
  $("#tr" + coupon_id + "_1").insertAfter($("#tr" + last_coupon_id + "_2"));
  $("#inputPosition" + current_pos).attr("id", "inputPosition0");

  for (var i = current_pos * 2 - 1; i < last_row; i = i + 2) {
    $("#couponsTable tr:eq(" + i + ") td:eq(1) input[class=inputPosition]").val((i + 1) / 2);
    $("#couponsTable tr:eq(" + i + ") td:eq(0)").text((i + 1) / 2);
  }
};