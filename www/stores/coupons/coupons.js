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

  $("#tr" + coupon_id + "_1").remove();
  $("#tr" + coupon_id + "_2").remove();
  var r_coupons = $("#rCoupons" ).val();
  r_coupons = r_coupons == '' ? coupon_id : r_coupons + "," + coupon_id;
  $("#rCoupons" ).val(r_coupons);

  $(".couponPosition").each(function (i, pos) {
    i++;
    pos = $(this).text();
    $("#inputPosition" + pos).val(i);
    $("#inputPosition" + pos).attr("id", "inputPosition" + i);
    $(this).text(i);
  });
};